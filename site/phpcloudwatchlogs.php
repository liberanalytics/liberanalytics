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
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PHP CloudWatch Logs | LiberAnalytics</title>
	<meta name="description" content="How to send PHP database query and error logs to AWS CloudWatch Logs using PDO, KMS, and the LegalThings CloudWatchLogger client.">
	<link rel="canonical" href="https://liberanalytics.com/phpcloudwatchlogs">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/icon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="PHP CloudWatch Logs | LiberAnalytics">
	<meta property="og:description" content="Learn how to log PostgreSQL queries and errors from PHP to AWS CloudWatch Logs using PDO, KMS, and CloudWatchLogger.">
	<meta property="og:url" content="https://liberanalytics.com/phpcloudwatchlogs">
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
	<link href="/css/la_tokens.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="/css/la_fonts.css" rel="stylesheet" type="text/css" charset="utf-8">	
	<link href="/css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="/css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="/css/la_tags.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="/css/la_ajax.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="/css/post.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="/js/la.js"></script>
</head>

<body class="page page-post site-font-std">

    <?php include_once('laheader.php'); ?>

    <main>
		<div class="page-wrap">
			<article>
				<header class="site-stack-mt">
					<?php
						//
						$postName = basename($_SERVER['PHP_SELF'], ".php");
						$post = $laData->crudData(
							CrudOperation::GETPOSTS,
							CrudType::CRUDREAD,
							CrudResultsetType::RESULTSET_ROWS,
							CrudFetchStyle::FETCHSTYLE_ASSOC,
							[ 'post_name' => $postName]						
						)[0];

						//
						$postShortDesc	= htmlspecialchars($post['short_desc'] ?? '', ENT_QUOTES, 'UTF-8');
						$postLongDesc	= htmlspecialchars($post['long_desc'] ?? '', ENT_QUOTES, 'UTF-8');
						$postImg    	= htmlspecialchars($post['post_img'] ?? '', ENT_QUOTES, 'UTF-8');
						$postAlt		= $postShortDesc;
						$postDate		= htmlspecialchars(date("F j, Y", strtotime($post['post_created'] ?? 'now')), ENT_QUOTES, 'UTF-8');
						$postISODate	= htmlspecialchars(date("Y-m-d", strtotime($post['post_created'] ?? 'now')), ENT_QUOTES, 'UTF-8');
						[$postImgWidth, $postImgHeight]
										= @getimagesize($_SERVER['DOCUMENT_ROOT']."/assets/"."{$postImg}");
						$postImgWidth	= (int)$postImgWidth;
						$postImgHeight	= (int)$postImgHeight;
					?>

					<h1 class="post-heading site-font-brand"><?php echo "{$postShortDesc}"; ?></h1>
					<p class="post-date site-font-date">
						<time datetime="<?php echo "{$postISODate}"; ?>">
							<?php echo "{$postDate}"; ?>
						</time>
					</p>
					<h2 class="post-desc site-font-emph"><?php echo "{$postLongDesc}"; ?></h2>
					<img 
						<?php
							//
							echo " src=\"/assets/{$postImg}\"";
							echo " alt=\"{$postAlt}\"";
							echo " width=\"{$postImgWidth}\"";
							echo " height=\"{$postImgHeight}\"";
						?>
						class="post-img"
						loading="lazy"
						decoding="async"
					>
				</header>     

				<section class="site-stack-mt">

					<p class="site-mt">
						<span class="site-font-brand">Ever</span> grow tired of storing logs on a server then having to manage
						access to the logs for individual users working in a collaborative development team? This post explores
						an alternative method to manage database logging by making use of the Amazon Web Services (AWS) CloudWatch
						Logs service. After reading this post you should have an idea of how to code PHP to send database logs
						to AWS CloudWatch. This approach offers several advantages over storing logs on a server filesystem, including:               
					</p>

					<ul>
						<li>Infrastructure is managed by AWS.</li>
						<li>
							Permissions to logs are orchestrated through AWS IAM which can greatly improve means of access
							when working within a development team.
						</li>
						<li>Can be integrated as part of an organization wide logging structure with AWS CloudWatch Logs.</li>
						<li>Can use CloudWatch metric filters to create data visualizations of the log data
							and generate alarms from those metrics using CloudWatch Alarms.
						</li>
					</ul>
					
					<p>
						This post depends on setting up a database configuration file which is covered in a previous post:  
						<a href="pythonpostgreconnect" class="site-font-link-std">Connecting Python to a Postgresql database</a>.					
					</p>

					<h3 class="site-font-secondary-heading">Requirements</h3>

					<ul>
						<li>PHP 8 (preferably running on Ubuntu)</li>
						<li>PHP composer</li>
						<li>PHP composer packages: aws/aws-sdk-php, legalthings/cloudwatch-logger</li>
						<li>AWS: Programmatic Access</li>
						<li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
						<li>AWS: CloudWatch Logs - Log Groups and Streams</li>
						<li>Postgresql database cluster (needs to accept TCP/IP connections)</li> 
						<li>Database created on Postgresql cluster</li>
					</ul>

					<h3 class="site-font-secondary-heading">Set up Log Group and Log Streams in AWS</h3>

					<p>
						First we need to create a log group and two log streams within the group which will store our database sql queries and errors.
						A log group named 'sdk' with two log streams named 'php/database/sql/' and 'php/database/errors/'
						have been created. After creating the log group and streams, ensure that the programmatic AWS user has the
						necessary AWS IAM credentials to write to the log streams which is the PutLogEvents API call. The simplest way
						to try out the example in this post is to attach the managed policy CloudWatchLogsFullAccess to the IAM user
						or group. The log stream structure looks like the below from the AWS console:
					</p>

					<img
						src="assets/phpcloudwatchlogs_logstreams.png"
						alt="Screenshot of AWS CloudWatch Logs log streams for php/database/sql and php/database/errors."
						width="820"
						height="200"
					>

					<h3 class="site-font-secondary-heading">Create CloudWatch Logs / KMS / CloudWatchLogger instances in PHP</h3>

					<p>
						Use PHP composer to install the AWS PHP SDK and logging packages - note the cloudwatch-logger package 
						from legalthings is used here. Then create the CloudWatch Logs / KMS / CloudWatchLogger instances in the PHP program.
						Since the instances are essential to the rest of the program, a try / catch block with an exit statement is used
						in their instantiation. If unable to connect to the AWS CloudWatch Logs or KMS services, check the IaM permissions
						for the programmatic user.
					</p>
								
					<div class="post-snippet site-stack-mt">
						<!-- Docblock -->
						<div>
							<p><span class="code-php-comment-fc">/**</span></p>					
							<p><span class="code-php-comment-fc"> * phppdocloudwatchlogs.php</span></p>
							<p><span class="code-php-comment-fc"> *</span></p>
							<p><span class="code-php-comment-fc"> * Creates a pdo connection to a postgresql database and runs a sample</span></p>
							<p><span class="code-php-comment-fc"> * query. Demonstrates how to use AWS Key Management Service keys to </span></p>
							<p><span class="code-php-comment-fc"> * decrypt database connection parameters from a configuration file on</span></p>						
							<p><span class="code-php-comment-fc"> * filesystem. The configuration file must have been created first</span></p>						
							<p><span class="code-php-comment-fc"> * with the aws_postgresql_config.py program. Shows how to loq errors</span></p>						
							<p><span class="code-php-comment-fc"> * encountered as well as queries run to CloudWatch Logs.</span></p>						
							<p><span class="code-php-comment-fc"> */</span></p>							
						</div>

						<!-- require autoload -->
						<div>
							<p>
								<span class="code-php-keyword-fc">require</span>
								<span class="code-php-default-fc">&nbsp;(</span>
								<span class="code-php-string-fc">'../vendor/autoload.php'</span>
								<span class="code-php-default-fc">);</span>
							</p>
						</div>

						<!-- Create AWS clients / loggers -->
						<div>
							<p><span class="code-php-comment-fc">// Create CloudWatch Logs / KMS / CloudWatchLogger instances.</span></p>							
							<p>
								<span class="code-php-keyword-fc">try</span>
								<span class="code-php-default-fc">&nbsp;{</span>
							</p>
						</div>

						<!-- CloudWatchLogsClient -->
						<div>
							<p>
								<span class="code-php-var-fc">$aws_cwlogs_client</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc">&nbsp;Aws\CloudWatchLogs\</span>
								<span class="code-php-type-fc">CloudWatchLogsClient</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">]);</span></p>
						</div>

						<!-- KmsClient -->
						<div>
							<p>
								<span class="code-php-var-fc">$aws_kms_client</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc">&nbsp;Aws\Kms\</span>
								<span class="code-php-type-fc">KmsClient</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">]);</span></p>
						</div>

						<!-- error_logger -->
						<div>
							<p>
								<span class="code-php-var-fc">$error_logger</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc">&nbsp;LegalThings\</span>
								<span class="code-php-type-fc">CloudWatchLogger</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'aws'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-default-fc">[</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">],</span></p>
							<p>
								<span class="code-php-string-fc">'group_name'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'sdk'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'stream_name'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'php/database/errors/'</span>
							</p>
							<p><span class="code-php-default-fc">]);</span></p>
						</div>

						<!-- spacer line -->
						<div>
							<p></p>
						</div>

						<!-- sql_logger -->
						<div>
							<p>
								<span class="code-php-var-fc">$sql_logger</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc">&nbsp;LegalThings\</span>
								<span class="code-php-type-fc">CloudWatchLogger</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'aws'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-default-fc">[</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">],</span></p>
							<p>
								<span class="code-php-string-fc">'group_name'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'sdk'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'stream_name'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'php/database/sql/'</span>
							</p>
							<p><span class="code-php-default-fc">]);</span></p>
							<p><span class="code-php-default-fc">}</span></p>
						</div>

						<!-- catch / error handling -->
						<div>
							<p><span class="code-php-comment-fc">// Report on any exceptions - exit program since all instances are needed.</span></p>
							<p>
								<span class="code-php-keyword-fc">catch</span>
								<span class="code-php-default-fc">&nbsp;(</span>
								<span class="code-php-type-fc">Exception</span>
								<span class="code-php-default-fc">&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-default-fc">)&nbsp;{</span>
							</p>
							<p>
								<span class="code-php-func-fc">echo</span>
								<span class="code-php-default-fc">&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">getTraceAsString</span>
								<span class="code-php-default-fc">();</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">exit</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-string-fc">'Couldn\'t instantiate a needed AWS or Logger class. Exiting.'</span>
								<span class="code-php-default-fc">);</span>
							</p>
							<p><span class="code-php-default-fc">}</span></p>
						</div>	
					</div>

					<h3 class="site-font-secondary-heading">Read database connection properties from encrypted configuration file</h3>

					<p>
						Now we will need to identify the database and user details used to connect to a PostgreSQL cluster and database.
						From the previous post, <a href="/pythonpostgreconnect" class="site-font-link-std">Connecting Python to a Postgresql database</a>, the
						connection details will be read from a file storing the sensitive data in encrypted format. Then the AWS KMS key used to encrypt the data will be used to decrypt the connection details so a connection can be made to the PostgreSQL database.
					</p>
					

					 <div class="post-snippet site-stack-mt">
						<!-- Open/read config file + perms -->
						<div>
							<p>
								<span class="code-php-comment-fc">// Open and read the database config file: reset perms on database config</span>
							</p>
							<p>
								<span class="code-php-comment-fc">// file when finished opening.</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_config_file</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-string-fc">'la_demo_db.ini'</span>
								<span class="code-php-default-fc">;</span>
							</p>
							<p>
								<span class="code-php-func-fc">chmod</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$db_config_file</span>
								<span class="code-php-default-fc">,&nbsp;</span>
								<span class="code-php-number-fc">0440</span>
								<span class="code-php-default-fc">);</span>
							</p>
							<p>
								<span class="code-php-var-fc">$handle</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-func-fc">fopen</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$db_config_file</span>
								<span class="code-php-default-fc">,&nbsp;</span>
								<span class="code-php-string-fc">'rb'</span>
								<span class="code-php-default-fc">);</span>
							</p>
							<p>
								<span class="code-php-func-fc">chmod</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$db_config_file</span>
								<span class="code-php-default-fc">,&nbsp;</span>
								<span class="code-php-number-fc">0000</span>
								<span class="code-php-default-fc">);</span>
							</p>
							<p>
								<span class="code-php-var-fc">$binary_data</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-func-fc">fread</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$handle</span>
								<span class="code-php-default-fc">,&nbsp;</span>
								<span class="code-php-func-fc">filesize</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$db_config_file</span>
								<span class="code-php-default-fc">));</span>
							</p>
						</div>

						<!-- Decrypt binary data -->
						<div>
							<p>
								<span class="code-php-comment-fc">// Decrypt the binary data in the database config file.</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">try</span>
								<span class="code-php-default-fc">&nbsp;{</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_config_data</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-var-fc">$aws_kms_client</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">decrypt</span>
								<span class="code-php-default-fc">([</span>
							</p>
						</div>

						<!-- KMS decrypt params -->
						<div>
							<p>
								<span class="code-php-comment-fc">// AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.</span>
							</p>
							<p>
								<span class="code-php-string-fc">'CiphertextBlob'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-var-fc">$binary_data</span>
								<span class="code-php-default-fc">,</span>
							</p>

							<p>
								<span class="code-php-string-fc">'EncryptionAlgorithm'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'SYMMETRIC_DEFAULT'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'KeyId'</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-string-fc">'keyid'</span>
							</p>
							<p>
								<span class="code-php-default-fc">]);</span>
							</p>
							<p>
								<span class="code-php-default-fc">}</span>
							</p>
						</div>

						<!-- Logging decrypt failures -->
						<div>
							<p>
								<span class="code-php-comment-fc">// Log any exceptions to the error log group / stream. Exit on failure</span>
							</p>
							<p>
								<span class="code-php-comment-fc">// since remaining program depends on decrypted data.</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">catch</span>
								<span class="code-php-default-fc">&nbsp;(</span>
								<span class="code-php-type-fc">Exception</span>
								<span class="code-php-default-fc">&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-default-fc">)&nbsp;{</span>
							</p>
							<p>
								<span class="code-php-var-fc">$error_logger</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">log</span>
								<span class="code-php-default-fc">([</span>
								<span class="code-php-string-fc">'Problem decrypting : '</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">getAwsErrorMessage</span>
								<span class="code-php-default-fc">()]);</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">exit</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-string-fc">"Unable to decrypt the data in {$db_config_file}."</span>
								<span class="code-php-default-fc">);</span>
							</p>
						</div>

						<div>
							<p>
								<span class="code-php-default-fc">}</span>
							</p>
						</div>

						<!-- Decode + split -->
						<div>
							<p>
								<span class="code-php-comment-fc">// Decode using base64 decoding, then split data into components per delimiter.</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_config_data</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-func-fc">base64_decode</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$db_config_data</span>
								<span class="code-php-default-fc">[</span>
								<span class="code-php-string-fc">'Plaintext'</span>
								<span class="code-php-default-fc">]);</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_params</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-func-fc">explode</span>
								<span class="code-php-default-fc">(&nbsp;</span>
								<span class="code-php-func-fc">chr</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-number-fc">7</span>
								<span class="code-php-default-fc">),&nbsp;</span>
								<span class="code-php-var-fc">$db_config_data</span>
								<span class="code-php-default-fc">&nbsp;);</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_host</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-var-fc">$db_params</span>
								<span class="code-php-default-fc">[</span>
								<span class="code-php-number-fc">0</span>
								<span class="code-php-default-fc">];</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_name</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-var-fc">$db_params</span>
								<span class="code-php-default-fc">[</span>
								<span class="code-php-number-fc">1</span>
								<span class="code-php-default-fc">];</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_user</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-var-fc">$db_params</span>
								<span class="code-php-default-fc">[</span>
								<span class="code-php-number-fc">2</span>
								<span class="code-php-default-fc">];</span>
							</p>
							<p>
								<span class="code-php-var-fc">$db_auth</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-var-fc">$db_params</span>
								<span class="code-php-default-fc">[</span>
								<span class="code-php-number-fc">3</span>
								<span class="code-php-default-fc">];</span>
							</p>
						</div>
					</div>

					<h3 class="site-font-secondary-heading">Capture decryption errors in AWS CloudWatch Logs</h3>

					<p>
						If an error was encountered during the encryption attempt there will be a log in the AWS CloudWatch Logs log stream
						previously configured: php/database/errors/. For simulation's sake we have generated an error to create a log record
						which appears in the AWS CloudWatch logs console as shown below.
					</p>

					<img 
						src="assets/phpcloudwatchlogs_decrypt_error.png"
						alt="phpcloudwatchlogs_decrypt_error"
						width="820"
						height="170"
					>

					<h3 class="site-font-secondary-heading">Connect to PostgreSQL database and capture connection errors in AWS CloudWatch Logs</h3>

					<p>
						Finally it is time to connect to the database. PHP's PDO data access layer is great for abstracting calls to database
						operations and easy to instantiate. To show the usefulness of CloudWatch logs a simulation of a failed connection attempt
						is run here.
					</p>

					<div class="post-snippet site-stack-mt">
						<div>
							<p>
								<span class="code-php-comment-fc">// Try to connect to postgres database. Log any errors to CloudWatch Logs.</span>
							</p>
						</div>
					
						<p>
							<span class="code-php-keyword-fc">try</span>
							<span class="code-php-default-fc">&nbsp;{</span>
						</p>
					
						<p>
							<span class="code-php-var-fc">$pdo</span>
							<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="code-php-keyword-fc">new</span>
							<span class="code-php-type-fc">&nbsp;PDO</span>
							<span class="code-php-default-fc">(</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">"pgsql:host=</span>
							<span class="code-php-var-fc">{$db_host}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    port=65535;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    dbname=</span>
							<span class="code-php-var-fc">{$db_name}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    user=</span>
							<span class="code-php-var-fc">{$db_user}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    password=</span>
							<span class="code-php-var-fc">{$db_auth}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    sslmode=require"</span>
						</p>
					
						<p>
							<span class="code-php-default-fc">);</span>
						</p>
					
						<div>
							<p>
								<span class="code-php-comment-fc">// Set PDO error reporting to Exception.</span>
							</p>
						
							<p>
								<span class="code-php-var-fc">$pdo</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">setAttribute</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-type-fc">PDO</span>
								<span class="code-php-op-fc">::</span>
								<span class="code-php-default-fc">ATTR_ERRMODE,&nbsp;</span>
								<span class="code-php-type-fc">PDO</span>
								<span class="code-php-op-fc">::</span>
								<span class="code-php-default-fc">ERRMODE_EXCEPTION);</span>
							</p>
						
							<p>
								<span class="code-php-default-fc">}</span>
							</p>
						</div>
					
						<div>
							<p>
								<span class="code-php-comment-fc">// Connection attempt failed - log failure to CloudWatch Logs and exit.</span>
							</p>
						
							<p>
								<span class="code-php-keyword-fc">catch</span>
								<span class="code-php-default-fc">&nbsp;(</span>
								<span class="code-php-type-fc">PDOException</span>
								<span class="code-php-default-fc">&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-default-fc">)&nbsp;{</span>
							</p>
						
							<p>
								<span class="code-php-var-fc">$error_logger</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">log</span>
								<span class="code-php-default-fc">([</span>
								<span class="code-php-string-fc">'Problem connecting : '</span>
								<span class="code-php-default-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">getMessage</span>
								<span class="code-php-default-fc">()]);</span>
							</p>
						
							<p>
								<span class="code-php-keyword-fc">exit</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-string-fc">"Unable to connect to host : </span>
								<span class="code-php-var-fc">{$db_host}</span>
								<span class="code-php-string-fc">."</span>
								<span class="code-php-default-fc">);</span>
							</p>
						
							<p>
								<span class="code-php-default-fc">}</span>
							</p>						
						</div>
					</div>
				
					<p>
						In using a bogus port of 65535 while attempting to connect via PHP PDO to a PostgreSQL database
						the try / catch entered the catch block and generated a log to the php/database/errors/ logstream. The
						log from the AWS CloudWatch logs console is shown below.
					</p>

					<img
						src="assets/phpcloudwatchlogs_connect_error.png"
						alt="Screenshot of decryption error in AWS CloudWatch Logs for php/database/errors stream."
						width="820"
						height="170"
					>

					<h3 class="site-font-secondary-heading">Log a successfully run query</h3>
					
					<div class="post-snippet site-stack-mt">
						<!-- Run example query + log success -->
						<div>
							<p>
								<span class="code-php-comment-fc">// Run an example query. Log successful queries to the sql log stream</span>
							</p>
							<p>
								<span class="code-php-comment-fc">// and any errors to the error log stream in CloudWatch Logs.</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">try</span>
								<span class="code-php-default-fc">&nbsp;{</span>
							</p>
							<p>
								<span class="code-php-var-fc">$qry</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-string-fc">'SELECT datname, datcollate FROM pg_database;'</span>
								<span class="code-php-default-fc">;</span>
							</p>
							<p>
								<span class="code-php-var-fc">$stmt</span>
								<span class="code-php-op-fc">&nbsp;=&nbsp;</span>
								<span class="code-php-var-fc">$pdo</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">query</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-var-fc">$qry</span>
								<span class="code-php-default-fc">);</span>
							</p>
							<p>
								<span class="code-php-var-fc">$sql_logger</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">log</span>
								<span class="code-php-default-fc">([</span>
								<span class="code-php-string-fc">"User: {$db_user}: Query: "</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-var-fc">$qry</span>
								<span class="code-php-default-fc">]);</span>
							</p>
							<p>
								<span class="code-php-default-fc">}</span>
							</p>
						</div>

						<!-- Log failed queries -->
						<div>
							<p>
								<span class="code-php-comment-fc">// Log failed query prepare statements to CloudWatch Logs.</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">catch</span>
								<span class="code-php-default-fc">&nbsp;(</span>
								<span class="code-php-type-fc">PDOException</span>
								<span class="code-php-default-fc">&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-default-fc">)&nbsp;{</span>
							</p>
							<p>
								<span class="code-php-var-fc">$error_logger</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">log</span>
								<span class="code-php-default-fc">([</span>
								<span class="code-php-string-fc">'Problem with query '</span>
								<span class="code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-op-fc">-&gt;</span>
								<span class="code-php-func-fc">getMessage</span>
								<span class="code-php-default-fc">()]);</span>
							</p>
							<p>
								<span class="code-php-keyword-fc">exit</span>
								<span class="code-php-default-fc">(</span>
								<span class="code-php-string-fc">"Unable to prepare query : {$qry}."</span>
								<span class="code-php-default-fc">);</span>
							</p>
							<p>
								<span class="code-php-default-fc">}</span>
							</p>
						</div>
					</div>

					<p>
						And this time the log goes to the php/database/sql/ logstream.
					</p>
						
					<img
						src="assets/phpcloudwatchlogs_query.png"
						alt="Screenshot of successful query log in php/database/sql stream."
						width="820"
						height="175"
					>

					<h3 class="site-font-secondary-heading">What can be learned from this article</h3>

					<p>
						Making use of AWS CloudWatch logs can simplify database error and query logging, especially
						if working collaboratively within a team which uses an AWS infrastructure. Instead of manually logging
						to files on a workstation's filesystem during development, the approach in this post emphasizes sending
						log data to CloudWatch logs where the logs can be analyzed further. AWS SDK's make logging simple
						when combined with a robust logging client such as the CloudWatchLogger from LegalThings. 
					</p>            

				</section>
			</article>
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
