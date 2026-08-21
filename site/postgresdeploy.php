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
	<title>Postgredeploy | LiberAnalytics</title>
	<meta name="description" content="<?php echo $postMetaDesc; ?>">
	<link rel="canonical" href="https://liberanalytics.com/postgredeploy">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Postgredeploy | LiberAnalytics">
	<meta property="og:description" content="<?php echo $postMetaDesc; ?>">
	<meta property="og:url" content="https://liberanalytics.com/postgredeploy">
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

					<p>Please note the deployment client must already be configured to authenticate to PostgreSQL</p>

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

				</section>
		
			</article>
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
