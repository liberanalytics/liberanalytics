<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once(dirname(__DIR__, 1) . "/" . 'vendor/autoload.php');

use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;	

$laData = new classes\LaData();
$laViewConverter = new classes\LaViewConverter();

//
$postName = basename($_SERVER['PHP_SELF'], ".php");
$post = $laData->crudData(
	CrudOperation::GETPOSTS,
	CrudType::CRUDREAD,
	CrudResultsetType::RESULTSET_ROWS,
	CrudFetchStyle::FETCHSTYLE_ASSOC,
	['post_name' => $postName]						
)[0];

$postTitle = $laViewConverter->escAttr($post['short_desc']);
$postMetaDesc = $laViewConverter->escAttr($post['long_desc']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Postgresdeploy | LiberAnalytics</title>
	<meta name="description" content="<?php echo $postMetaDesc; ?>">
	<link rel="canonical" href="https://liberanalytics.com/postgredeploy">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Postgresdeploy | LiberAnalytics">
	<meta property="og:description" content="<?php echo $postMetaDesc; ?>">
	<meta property="og:url" content="https://liberanalytics.com/postgresdeploy">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="LiberAnalytics">
	<meta property="og:locale" content="en_US">

	<!-- Social media image. -->
	<meta property="og:image" content="https://liberanalytics.com/assets/social/og_core.jpg">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:image:alt" content="LiberAnalytics wordmark on a clean, minimalist background">

	<!-- Browser UI styling for address bar. -->
	<meta name="theme-color" content="#333333">
	<meta name="theme-color" content="#333333" media="(prefers-color-scheme: dark)">
	<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">

	<!-- Referrer policy: omit referrer header when navigating from HTTPS to HTTP. -->
	<meta name="referrer" content="no-referrer-when-downgrade">
	
	<!-- Stylesheets and javascript modules/files. -->
	<link href="/css/la_tokens.css" rel="stylesheet">
	<link href="/css/la_fonts.css" rel="stylesheet">	
	<link href="/css/la.css" rel="stylesheet">
	<link href="/css/la_hdrftr.css" rel="stylesheet">
	<link href="/css/la_forms.css" rel="stylesheet">
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/post.css" rel="stylesheet">
	<script type="module" src="/js/tagsearch.js"></script>
	<script type="module" src="/js/semanticsearch.js"></script>
</head>

<body class="page page-post site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<article>
				<header class="site-stack-start-mt">
							
					<?php						
						//
						echo $laViewConverter->getPostHeader($post);
					?>

				</header>

				<section class="site-stack-start-mt">

					<p class="site-mt"> 
						<span class="site-font-brand">Deploying</span>
						a database can seem like a daunting task that turns into a trial-and-error ritual:
						run SQL script(s) to create database schema objects — tables, foreign keys, indexes,
						functions, and stored procedures. Then hope that script_01.sql, script_02.sql,
						and so on are in the appropriate sequence to create prerequisite objects before
						the objects that depend on them.
					</p>

					<p>
						What inevitably happens is that a prerequisite object has not yet been created,
						an environment configuration has not been set up, or database authentication fails, 
						causing the whole pipeline to get stuck and leaving the practitioner to diagnose the 
						failure point and decide how to handle it. Should we raise an exception? Write to a log?
						Roll the entire operation back, or allow the script(s) to run to a checkpoint?
					</p>

					<p>
						In this article we present a case study from a real production rollout and
						the deployment process developed to make future PostgreSQL deployments more
						reliable, repeatable, and controlled.
					</p>

					<h3 class="site-font-secondary-heading">Requirements</h3>

					<p>
						Before the deployment scripts can run, the deployment client must be able to reach
						PostgreSQL and authenticate against the databases used during the rollout.
						Because the process initially connects to the postgres maintenance database
						in order to drop and recreate liberanalytics, pg_hba.conf must permit the deployment
						role to connect to both postgres and liberanalytics. Network access, PostgreSQL
						host-based authentication, and role privileges therefore form the bootstrap requirements
						for the deployment process.
					</p>

<!-- pg_hba.conf -->
<div class="post-snippet">
	<pre class="post-snippet-font-std code-config-default-fc">TYPE        DATABASE        USER            ADDRESS                 METHOD
hostssl     postgres        liberanalytics  &lt;client-ip&gt;/32          scram-sha-256
hostssl     liberanalytics  liberanalytics  &lt;client-ip&gt;/32          scram-sha-256</pre>
</div>

					<p>The deployment client must already be configured to authenticate to PostgreSQL.
					   We will be using the psql client which is the terminal-based command-line interface (CLI) for PostgreSQL.
					</p>

					<h3 class="site-font-secondary-heading">Bash Orchestration Script</h3>

					<p>
						We will use a Bash script to help orchestrate the database rollout. The script
						shown below manages several important parts of the deployment process:
						it defines the psql connection parameters as variables, executes the SQL driver
						script within an if/then control structure, and reports the result of that execution.
						On success, the script returns a clear completion message. On failure, it captures
						and displays the exact psql exit code before terminating with that same code.
					</p>

					<p class="site-mt">
						The failure-handling component is particularly useful because it provides additional
						context when diagnosing a deployment failure and allows the shell-level orchestration
						to clearly distinguish between a successful and unsuccessful database rollout.
					</p>

					<div class="post-snippet">

						<!-- Shebang -->
						<p>
							<span class="post-snippet-font-std code-bash-comment-fc">#!/bin/bash</span>
						</p>

						<div class="site-mt">

							<!-- Shell options -->
							<p>
								<span class="post-snippet-font-std code-bash-command-fc">set&nbsp;</span>
								<span class="post-snippet-font-std code-bash-option-fc">-uo&nbsp;pipefail</span>
							</p>

						</div>

						<div class="site-mt">

							<!-- Database variables -->
							<p>
								<span class="post-snippet-font-std code-bash-var-fc">DB_HOST</span>
								<span class="post-snippet-font-std code-bash-default-fc">=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"hostname"</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-var-fc">DB_USER</span>
								<span class="post-snippet-font-std code-bash-default-fc">=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"liberanalytics"</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-var-fc">DB_NAME</span>
								<span class="post-snippet-font-std code-bash-default-fc">=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"postgres"</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-var-fc">DB_FILE</span>
								<span class="post-snippet-font-std code-bash-default-fc">=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"init.sql"</span>
							</p>

						</div>

						<div class="site-mt">

							<!-- psql execution -->
							<p>
								<span class="post-snippet-font-std code-bash-keyword-fc">if&nbsp;</span>
								<span class="post-snippet-font-std code-bash-command-fc">psql&nbsp;</span>
								<span class="post-snippet-font-std code-bash-default-fc">\</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-option-fc">--host=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"$DB_HOST"&nbsp;</span>
								<span class="post-snippet-font-std code-bash-default-fc">\</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-option-fc">--username=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"$DB_USER"&nbsp;</span>
								<span class="post-snippet-font-std code-bash-default-fc">\</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-option-fc">--dbname=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"$DB_NAME"&nbsp;</span>
								<span class="post-snippet-font-std code-bash-default-fc">\</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-option-fc">--file=</span>
								<span class="post-snippet-font-std code-bash-string-fc">"$DB_FILE"</span>
							</p>

							<!-- Success -->
							<p>
								<span class="post-snippet-font-std code-bash-keyword-fc">then</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-command-fc">echo&nbsp;</span>
								<span class="post-snippet-font-std code-bash-string-fc">"Deployment completed successfully."</span>
							</p>

							<!-- Failure -->
							<p>
								<span class="post-snippet-font-std code-bash-keyword-fc">else</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-var-fc">PSQL_EXIT_CODE</span>
								<span class="post-snippet-font-std code-bash-default-fc">=</span>
								<span class="post-snippet-font-std code-bash-special-fc">$?</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-command-fc">echo&nbsp;</span>
								<span class="post-snippet-font-std code-bash-string-fc">"Deployment failed. psql exit code: $PSQL_EXIT_CODE"</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-bash-command-fc">exit&nbsp;</span>
								<span class="post-snippet-font-std code-bash-string-fc">"$PSQL_EXIT_CODE"</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-bash-keyword-fc">fi</span>
							</p>

						</div>

					</div>

					<h3 class="site-font-secondary-heading">SQL Driver Script</h3>

					<p>
						The SQL driver script is where the real database work is defined. It is designed
						with idempotent behavior where practical and manages several critical deployment components:
					</p>

					
					
					<ul class="site-list">
						<li>
							Sets psql error-message verbosity to verbose, providing additional diagnostic
							details for errors and notices.
						</li>
						<li>
							Connects to the postgres database so the existing liberanalytics database can be dropped
							and terminates any active sessions on the liberanalytics database.
						</li>
						<li>
							Drops database liberanalytics if it exists, runs the database creation .sql script
							with the psql \ir command, connects to database liberanalytics, then prints
							the current connection context.
						</li>
						<li>
							Starts a transaction so the remaining database rollout can succeed or fail as
							a single unit, providing atomicity.
						</li>						
						<li>
							Creates the schemas, tables, and functions using the psql \ir command to include
							each .sql script by relative path.
						</li>
						<li>
							Performs a smoke test in an anonymous PL/pgSQL block to verify that the final table
							and function DDL included by the \ir commands completed successfully.
						</li>
						<li>
							Populates the database with initial reference and operational data.
						</li>
						<li>
							Performs a final smoke test to verify that the last seed data was inserted successfully.
						</li>
						<li>
							Commits the transaction, permanently applying all changes; if an earlier statement
							caused the transaction to fail, the changes are rolled back instead.
						</li>

					</ul>

					<div class="post-snippet">

						<!-- psql settings -->
						<div>
							<p>
								<span class="post-snippet-font-std code-postgres-meta-fc">\set&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">VERBOSITY&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">verbose</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-meta-fc">\set&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">ON_ERROR_STOP&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">on</span>
							</p>
						</div>


						<!-- Connect to postgres -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Make sure we're not in the target database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-meta-fc">\connect&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">postgres</span>
							</p>
						</div>


						<!-- Terminate active sessions -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Kill any sessions still active on the database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-func-fc">pg_terminate_backend</span>
								<span class="post-snippet-font-std code-postgres-default-fc">(pid)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg_stat_activity</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">datname = </span>
								<span class="post-snippet-font-std code-postgres-string-fc">'liberanalytics'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pid &lt;&gt; </span>
								<span class="post-snippet-font-std code-postgres-func-fc">pg_backend_pid</span>
								<span class="post-snippet-font-std code-postgres-default-fc">();</span>
							</p>
						</div>


						<!-- Drop database -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Drop the existing liberanalytics database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">DROP DATABASE IF EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">liberanalytics;</span>
							</p>
						</div>


						<!-- Create database -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Create the liberanalytics database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-path-fc">../ddl/db/liberanalytics_db.sql</span>
							</p>
						</div>


						<!-- Connect to liberanalytics -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Connect to database liberanalytics.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-meta-fc">\connect&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">liberanalytics</span>
							</p>
						</div>


						<!-- Verify connection -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Verify connection context in deployment output.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-func-fc">current_database</span>
								<span class="post-snippet-font-std code-postgres-default-fc">()&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">current_db, current_user&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">current_user;</span>
							</p>
						</div>


						<!-- Transaction -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Wrap the rollout in a transaction to provide all-or-nothing atomicity.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">BEGIN;</span>
							</p>
						</div>


						<!-- Schemas -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Create schemas.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">Creating schemas...</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-path-fc">../ddl/schema/meta.sql</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-path-fc">../ddl/schema/refdata.sql</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-path-fc">../ddl/schema/liberanalytics.sql</span>
							</p>
						</div>


						<!-- Tables -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Create tables.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">Creating tables...</span>
							</p>

							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/tables/refdata.post_categories.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/tables/liberanalytics.posts.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/tables/refdata.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/tables/liberanalytics.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/tables/liberanalytics.post_img.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/tables/liberanalytics.contact.sql</span></p>
						</div>


						<!-- Functions -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Create fxs.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">Creating fxs...</span>
							</p>

							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/meta.fx_get_dynamic_sql_clause.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/meta.fx_get_fx_pxs.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/refdata.fx_get_post_categories.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/refdata.fx_get_post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/refdata.fx_get_post_tags_like.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/liberanalytics.fx_get_posts.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../ddl/fx/liberanalytics.fx_create_contact.sql</span></p>
						</div>


						<!-- Artifact verification -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Confirm final table &amp; fx artifacts were created as quick pass/fail assertion.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">DO&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">$$</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">BEGIN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Check last table was created.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">IF NOT EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">1&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg_catalog.pg_tables</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">1=1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">schemaname = </span>
								<span class="post-snippet-font-std code-postgres-string-fc">'liberanalytics'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">tablename = </span>
								<span class="post-snippet-font-std code-postgres-string-fc">'contact'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">THEN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">RAISE EXCEPTION&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-string-fc">'Schema creation failed: table liberanalytics.contact was not created.'</span>
								<span class="post-snippet-font-std code-postgres-default-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">END IF;</span>
							</p>


							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Check last fx was created.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">IF NOT EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg_catalog.pg_proc&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">INNER JOIN&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg_catalog.pg_namespace&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">ns</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">ON&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg.pronamespace = ns.oid</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">1=1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">ns.nspname = </span>
								<span class="post-snippet-font-std code-postgres-string-fc">'liberanalytics'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">pg.proname = </span>
								<span class="post-snippet-font-std code-postgres-string-fc">'fx_create_contact'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Verify args are same as last fx created in case of overloading.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-func-fc">pg_get_function_identity_arguments</span>
								<span class="post-snippet-font-std code-postgres-default-fc">(pg.oid)&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">LIKE&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-string-fc">'%text, %text, %text, %text, %text'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">THEN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">RAISE EXCEPTION&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-string-fc">'Fx creation failed: liberanalytics.fx fx_create_contact was not created.'</span>
								<span class="post-snippet-font-std code-postgres-default-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">END IF;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">END&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">$$&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">LANGUAGE&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">plpgsql;</span>
							</p>
						</div>


						<!-- Seed data -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Seed data insertions.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">Populating seed data...</span>
							</p>

							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../dml/seed/refdata.post_categories.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../dml/seed/refdata.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../dml/seed/liberanalytics.posts.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../dml/seed/liberanalytics.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-postgres-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-postgres-path-fc">../dml/seed/liberanalytics.post_img.sql</span></p>
						</div>


						<!-- Smoke test -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-comment-fc">-- Smoke test: confirm the final seed table contains at least one row.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">DO&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">$$</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">BEGIN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">IF NOT EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">1&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">liberanalytics.post_tags</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">)&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">THEN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">RAISE EXCEPTION&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-string-fc">'Seed failed: liberanalytics.post_img has no rows.'</span>
								<span class="post-snippet-font-std code-postgres-default-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">END IF;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-postgres-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">END&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">$$&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-keyword-fc">LANGUAGE&nbsp;</span>
								<span class="post-snippet-font-std code-postgres-default-fc">plpgsql;</span>
							</p>
						</div>


						<!-- Commit -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-postgres-keyword-fc">COMMIT;</span>
							</p>
						</div>

					</div>

					<h3 class="site-font-secondary-heading">Understanding the SQL Driver Script</h3>

					<p>
						Once the transaction has been opened with the BEGIN; statement, the individual .sql
						scripts are executed through the psql include-relative \ir meta-command, beginning
						with schema creation, followed by tables, and ending with functions. During this
						phase, the execution order of the scripts is critical. For example, if a table
						contains a foreign-key reference, the referenced table must be created before the
						table containing the foreign-key definition. If the	sequence is incorrect, PostgreSQL
						raises an error and the transaction enters an aborted state, causing the statements
						executed within the BEGIN; block to be rolled back.						
					</p>

					<p>
						In the DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM liberanalytics.post_img) … block, the
						assertion is intentionally lightweight. It verifies that the final seeded table contains
						data and provides a simple rollout smoke test; it does not establish that every expected
						row or value was loaded correctly. More rigorous deployments could add row-count
						assertions, key-record checks, referential-integrity checks, or source-to-target
						reconciliation.
					</p>

					<h3 class="site-font-secondary-heading">Failed Versus Successful Deployment Example</h3>

					<p>
						During a test rollout, a missing comma in a seed-data script caused PostgreSQL
						to raise a syntax error. Because ON_ERROR_STOP was enabled, psql terminated
						execution and returned a non-zero exit code. The Bash orchestration layer
						captured that result and surfaced a concise deployment-level failure message
						shown below as "Deployment failed. psql exit code: 3", while preserving the underlying PostgreSQL diagnostic.
					</p>

					<div class="post-snippet">

						<!-- Deployment failure output -->
						<div>
							<p>
								<span class="post-snippet-font-std code-config-default-fc">Populating seed data...</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 7</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 7</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 4</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">psql:../dml/seed/liberanalytics.post_tags.sql:26: ERROR:&nbsp;&nbsp;42601: syntax error at or near "("</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">LINE 22:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('postgresdeploy', 'PostgreSQL', DEFAULT);</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;^</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">LOCATION:&nbsp;&nbsp;scanner_yyerror, scan.l:1244</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">Deployment failed. psql exit code: 3</span>
							</p>
						</div>

					</div>

					<p>
						On a successful deployment, the Bash terminal displays PostgreSQL
						command-status messages confirming that each seed-data insert completed.
						For example, INSERT 0 7 indicates that seven rows were inserted; the 0
						represents the legacy OID field, which is no longer populated for ordinary
						user tables. More importantly, the final line shown below — "Deployment
						completed successfully." — is generated by the if / then / else block in
						init.sh when psql returns a successful exit code, confirming that the
						database rollout completed as intended.
					</p>

					<div class="post-snippet">

						<!-- Deployment success output -->
						<div>
							<p>
								<span class="post-snippet-font-std code-config-default-fc">Populating seed data...</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 7</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 7</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 4</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 10</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">INSERT 0 28</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">DO</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">COMMIT</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-config-default-fc">Deployment completed successfully.</span>
							</p>
						</div>

					</div>

					<h3 class="site-font-secondary-heading">Deployment Lessons and Final Considerations</h3>

					<p>
						A stable production database deployment should be managed as an all-or-nothing atomic
						operation where possible. Deployments should also be idempotent where practical so
						repeated executions are easier to manage, particularly after a failure. Using a Bash
						shell script can simplify connection handling, deployment flow, and SQL script sequencing
						while also providing diagnostics beyond the database engine and client messages.
					</p>

				</section>
		
			</article>

			<div class="site-ajax-spinner-8spoke site-ajax-hidden">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div class="site-ajax-overlay-flex site-ajax-hidden">
			</div>
						
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
