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
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/post.css" rel="stylesheet">
	<script type="module" src="/js/la.js"></script>
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
						In this article, we present a case study of lessons learned from a real production rollout.
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

					<p>Please note the deployment client must already be configured to authenticate to PostgreSQL.
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
						The sql driver script is where the real database work is defined and
						manages several critical deployment components:
					</p>

					
					
					<ul class="site-list">
						<li>Sets verbosity to on</li>

					</ul>

					<div class="post-snippet">

						<!-- psql settings -->
						<div>
							<p>
								<span class="post-snippet-font-std code-sql-meta-fc">\set&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">VERBOSITY&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">verbose</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-meta-fc">\set&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">ON_ERROR_STOP&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">on</span>
							</p>
						</div>


						<!-- Connect to postgres -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Make sure we're not in the target database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-meta-fc">\connect&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">postgres</span>
							</p>
						</div>


						<!-- Terminate active sessions -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Kill any sessions still active on the database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-sql-func-fc">pg_terminate_backend</span>
								<span class="post-snippet-font-std code-sql-default-fc">(pid)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg_stat_activity</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">datname = </span>
								<span class="post-snippet-font-std code-sql-string-fc">'liberanalytics'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pid &lt;&gt; </span>
								<span class="post-snippet-font-std code-sql-func-fc">pg_backend_pid</span>
								<span class="post-snippet-font-std code-sql-default-fc">();</span>
							</p>
						</div>


						<!-- Drop database -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Drop the existing liberanalytics database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">DROP DATABASE IF EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">liberanalytics;</span>
							</p>
						</div>


						<!-- Create database -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Create the liberanalytics database.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-sql-path-fc">../ddl/db/liberanalytics_db.sql</span>
							</p>
						</div>


						<!-- Connect to liberanalytics -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Connect to database liberanalytics.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-meta-fc">\connect&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">liberanalytics</span>
							</p>
						</div>


						<!-- Verify connection -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Verify connection context in deployment output.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-sql-func-fc">current_database</span>
								<span class="post-snippet-font-std code-sql-default-fc">()&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">current_db, current_user&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">current_user;</span>
							</p>
						</div>


						<!-- Transaction -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Wrap the rollout in a transaction to provide all-or-nothing atomicity.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">BEGIN;</span>
							</p>
						</div>


						<!-- Schemas -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Create schemas.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">Creating schemas...</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-sql-path-fc">../ddl/schema/meta.sql</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-sql-path-fc">../ddl/schema/refdata.sql</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span>
								<span class="post-snippet-font-std code-sql-path-fc">../ddl/schema/liberanalytics.sql</span>
							</p>
						</div>


						<!-- Tables -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Create tables.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">Creating tables...</span>
							</p>

							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/tables/refdata.post_categories.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/tables/liberanalytics.posts.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/tables/refdata.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/tables/liberanalytics.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/tables/liberanalytics.post_img.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/tables/liberanalytics.contact.sql</span></p>
						</div>


						<!-- Functions -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Create fxs.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">Creating fxs...</span>
							</p>

							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/meta.fx_get_dynamic_sql_clause.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/meta.fx_get_fx_pxs.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/refdata.fx_get_post_categories.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/refdata.fx_get_post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/refdata.fx_get_post_tags_like.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/liberanalytics.fx_get_posts.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../ddl/fx/liberanalytics.fx_create_contact.sql</span></p>
						</div>


						<!-- Artifact verification -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Confirm final table &amp; fx artifacts were created as quick pass/fail assertion.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">DO&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">$$</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">BEGIN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Check last table was created.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">IF NOT EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">1&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg_catalog.pg_tables</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">1=1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">schemaname = </span>
								<span class="post-snippet-font-std code-sql-string-fc">'liberanalytics'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">tablename = </span>
								<span class="post-snippet-font-std code-sql-string-fc">'contact'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">THEN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">RAISE EXCEPTION&nbsp;</span>
								<span class="post-snippet-font-std code-sql-string-fc">'Schema creation failed: table liberanalytics.contact was not created.'</span>
								<span class="post-snippet-font-std code-sql-default-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">END IF;</span>
							</p>


							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Check last fx was created.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">IF NOT EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg_catalog.pg_proc&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">INNER JOIN&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg_catalog.pg_namespace&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">ns</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">ON&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg.pronamespace = ns.oid</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">1=1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">ns.nspname = </span>
								<span class="post-snippet-font-std code-sql-string-fc">'liberanalytics'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">pg.proname = </span>
								<span class="post-snippet-font-std code-sql-string-fc">'fx_create_contact'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Verify args are same as last fx created in case of overloading.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-sql-func-fc">pg_get_function_identity_arguments</span>
								<span class="post-snippet-font-std code-sql-default-fc">(pg.oid)&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">LIKE&nbsp;</span>
								<span class="post-snippet-font-std code-sql-string-fc">'%text, %text, %text, %text, %text'</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">THEN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">RAISE EXCEPTION&nbsp;</span>
								<span class="post-snippet-font-std code-sql-string-fc">'Fx creation failed: liberanalytics.fx fx_create_contact was not created.'</span>
								<span class="post-snippet-font-std code-sql-default-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">END IF;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">END&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">$$&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">LANGUAGE&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">plpgsql;</span>
							</p>
						</div>


						<!-- Seed data -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Seed data insertions.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-meta-fc">\echo&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">Populating seed data...</span>
							</p>

							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../dml/seed/refdata.post_categories.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../dml/seed/refdata.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../dml/seed/liberanalytics.posts.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../dml/seed/liberanalytics.post_tags.sql</span></p>
							<p><span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="post-snippet-font-std code-sql-meta-fc">\ir&nbsp;</span><span class="post-snippet-font-std code-sql-path-fc">../dml/seed/liberanalytics.post_img.sql</span></p>
						</div>


						<!-- Smoke test -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-comment-fc">-- Smoke test: confirm the final seed table contains at least one row.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">DO&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">$$</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">BEGIN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">IF NOT EXISTS&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">SELECT&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">1&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">liberanalytics.post_tags</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">)&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">THEN</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">RAISE EXCEPTION&nbsp;</span>
								<span class="post-snippet-font-std code-sql-string-fc">'Seed failed: liberanalytics.post_tags has no rows.'</span>
								<span class="post-snippet-font-std code-sql-default-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">END IF;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-sql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">END&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">$$&nbsp;</span>
								<span class="post-snippet-font-std code-sql-keyword-fc">LANGUAGE&nbsp;</span>
								<span class="post-snippet-font-std code-sql-default-fc">plpgsql;</span>
							</p>
						</div>


						<!-- Commit -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-sql-keyword-fc">COMMIT;</span>
							</p>
						</div>

					</div>

					<p>
						This assertion is intentionally lightweight. It verifies that the final seeded
						table contains data and provides a simple rollout smoke test; it does not establish
						that every expected row or value was loaded correctly. More rigorous deployments
						could add row-count assertions, key-record checks, referential-integrity checks, or source-to-target reconciliation.
					</p>

					<h3 class="site-font-secondary-heading">Failed Versus Successful Deployment Example</h3>

					<p>
						During a test rollout, a missing comma in a seed-data script caused PostgreSQL
						to raise a syntax error. Because ON_ERROR_STOP was enabled, psql terminated
						execution and returned a non-zero exit code. The Bash orchestration layer
						captured that result and surfaced a concise deployment-level failure message
						shown below as "Deployment failed. psql exit code: 3" while preserving the underlying PostgreSQL diagnostic.
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
						command-status messages confirming that each seed-data insert completed
						successfully. For example, INSERT 0 7 indicates that seven rows were
						inserted; the 0 represents the legacy OID field, which is no longer
						populated for ordinary user tables. The more important result is the
						final line, "Deployment completed successfully.". This message is
						generated by the if / then / else block in init.sh when psql returns
						a successful exit code, confirming that the database rollout
						completed successfully.
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

				</section>
		
			</article>
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
