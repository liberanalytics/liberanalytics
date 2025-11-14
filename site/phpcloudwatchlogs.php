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
	<meta name="description" content="Learn how LiberAnalytics collects, uses, and protects your information. Transparency and data privacy are core to our commitment to visitors.">
	<link rel="canonical" href="https://liberanalytics.com/phpcloudwatchlogs">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/icon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Python PostGre Connect | LiberAnalytics">
	<meta property="og:description" content="Learn how LiberAnalytics collects, uses, and protects your information. Transparency and data privacy are core to our commitment to visitors.">
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
	<link href="/css/la_post.css" rel="stylesheet" type="text/css" charset="utf-8">
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
						if (!$post || !isset($post)) {
							// header to 404.php
						}

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
						<a href="pythonpostgreconnect" class="site-link-std site-link-flh-med">Connecting Python to a Postgresql database</a>.					
					</p>

					<h4 class="site-font-secondary-heading">Requirements</h1>

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

					<h4 class="site-font-secondary-heading">Set up Log Group and Log Streams in AWS</h1>

					<p>
						First we need to create a log group and two log streams within the group which will store our database sql queries and errors.
						A log group named 'sdk' with two log streams named 'php/database/sql/' and 'php/database/errors/'
						have been created. After creating the log group and streams, ensure that the programmatic AWS user has the
						necessary AWS IAM credentials to write to the log streams which is the PutLogEvents API call. The simplest way
						to try out the example in this post is to attach the managed policy CloudWatchLogsFullAccess to the IAM user
						or group. The log stream structure looks like the below from the AWS console:
					</p>

					<img src="assets/phpcloudwatchlogs_logstreams.png" alt="phpcloudwatchlogs_logstreams">

					<h4 class="site-font-secondary-heading">Create CloudWatch Logs / KMS / CloudWatchLogger instances in PHP</h1>

					<p>
						Use PHP composer to install the AWS PHP SDK and logging packages - note the cloudwatch-logger package 
						from legalthings is used here. Then create the CloudWatch Logs / KMS / CloudWatchLogger instances in the PHP program.
						Since the instances are essential to the rest of the program, a try / catch block with an exit statement is used
						in their instantiation. If unable to connect to the AWS CloudWatch Logs or KMS services, check the IaM permissions
						for the programmatic user.
					</p>
								
					<div class="post-snippet site-stack-mt">
						<!-- Docblock -->
						<div class="site-mt">
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
								<span class="code-php-default-fc"> (</span>
								<span class="code-php-string-fc">'../vendor/autoload.php'</span>
								<span class="code-php-default-fc">);</span>
							</p>
						</div>

						<!-- Create AWS clients / loggers -->
						<div>
							<p><span class="code-php-comment-fc">// Create CloudWatch Logs / KMS / CloudWatchLogger instances.</span></p>							
							<p>
								<span class="code-php-keyword-fc">try</span>
								<span class="code-php-default-fc"> {</span>
							</p>
						</div>

						<!-- CloudWatchLogsClient -->
						<div>
							<p>
								<span class="code-php-var-fc">$aws_cwlogs_client</span>
								<span class="code-php-op-fc"> = </span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc"> Aws\CloudWatchLogs\</span>
								<span class="code-php-type-fc">CloudWatchLogsClient</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">]);</span></p>
						</div>

						<!-- KmsClient -->
						<div>
							<p>
								<span class="code-php-var-fc">$aws_kms_client</span>
								<span class="code-php-op-fc"> = </span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc"> Aws\Kms\</span>
								<span class="code-php-type-fc">KmsClient</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">]);</span></p>
						</div>

						<!-- error_logger -->
						<div>
							<p>
								<span class="code-php-var-fc">$error_logger</span>
								<span class="code-php-op-fc"> = </span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc"> LegalThings\</span>
								<span class="code-php-type-fc">CloudWatchLogger</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'aws'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-default-fc">[</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">],</span></p>
							<p>
								<span class="code-php-string-fc">'group_name'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'sdk'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'stream_name'</span>
								<span class="code-php-op-fc"> =&gt; </span>
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
								<span class="code-php-op-fc"> = </span>
								<span class="code-php-keyword-fc">new</span>
								<span class="code-php-default-fc"> LegalThings\</span>
								<span class="code-php-type-fc">CloudWatchLogger</span>
								<span class="code-php-default-fc">([</span>
							</p>
							<p>
								<span class="code-php-string-fc">'aws'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-default-fc">[</span>
							</p>
							<p>
								<span class="code-php-string-fc">'profile'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'default'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'region'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'us-east-2'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'version'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'latest'</span>
							</p>
							<p><span class="code-php-default-fc">],</span></p>
							<p>
								<span class="code-php-string-fc">'group_name'</span>
								<span class="code-php-op-fc"> =&gt; </span>
								<span class="code-php-string-fc">'sdk'</span>
								<span class="code-php-default-fc">,</span>
							</p>
							<p>
								<span class="code-php-string-fc">'stream_name'</span>
								<span class="code-php-op-fc"> =&gt; </span>
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
								<span class="code-php-default-fc"> (</span>
								<span class="code-php-type-fc">Exxception</span>
								<span class="code-php-default-fc"> </span>
								<span class="code-php-var-fc">$e</span>
								<span class="code-php-default-fc">) {</span>
							</p>
							<p>
								<span class="code-php-func-fc">echo</span>
								<span class="code-php-default-fc"> </span>
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

					<h4 class="site-font-secondary-heading">Read database connection properties from encrypted configuration file</h1>

					<p>
						Now we will need to identify the database and user details used to connect to a PostgreSQL cluster and database.
						From the previous post, <a href="pythonpostgreconnect">Connecting Python to a Postgresql database</a>, the
						connection details will be read from a file storing the sensitive data in encrypted format. Then the AWS KMS key used to encrypt the data will be used to decrypt the connection details so a connection can be made to the PostgreSQL database.
					</p>
					
					<div class="post-snippet" style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; font-size: 16px; line-height: 19px; margin: auto; white-space: nowrap;"><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Open&nbsp;and&nbsp;read&nbsp;the&nbsp;database&nbsp;config&nbsp;file:&nbsp;reset&nbsp;perms&nbsp;on&nbsp;database&nbsp;config</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;file&nbsp;when&nbsp;finished&nbsp;opening.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #ce9178;">'la_demo_db.ini'</span><span style="color: #d4d4d4;">;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #dcdcaa;">chmod</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #b5cea8;">0440</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$handle</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">fopen</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #ce9178;">'rb'</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #dcdcaa;">chmod</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #b5cea8;">0000</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$binary_data</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">fread</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$handle</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #dcdcaa;">filesize</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">));</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Decrypt&nbsp;the&nbsp;binary&nbsp;data&nbsp;in&nbsp;the&nbsp;database&nbsp;config&nbsp;file.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span style="color: #d4d4d4;">&nbsp;{</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$aws_kms_client</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">decrypt</span><span style="color: #d4d4d4;">([</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;AWS&nbsp;KMS&nbsp;decrypt&nbsp;P(x)'s&nbsp;-&nbsp;Include&nbsp;EncryptionAlgorithm&nbsp;as&nbsp;best&nbsp;practice.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'CiphertextBlob'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$binary_data</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'EncryptionAlgorithm'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'SYMMETRIC_DEFAULT'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'KeyId'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'keyid'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Log&nbsp;any&nbsp;exceptions&nbsp;to&nbsp;the&nbsp;error&nbsp;log&nbsp;group&nbsp;/&nbsp;stream.&nbsp;Exit&nbsp;on&nbsp;failure</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;since&nbsp;remaining&nbsp;program&nbsp;depends&nbsp;on&nbsp;decrypted&nbsp;data.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">catch</span><span style="color: #d4d4d4;">&nbsp;(</span><span style="color: #4ec9b0;">Exception</span><span style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">)&nbsp;{</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span style="color: #ce9178;">'Problem&nbsp;decrypting&nbsp;:&nbsp;'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getAwsErrorMessage</span><span style="color: #d4d4d4;">()]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span style="color: #ce9178;">"Unable&nbsp;to&nbsp;decrypt&nbsp;the&nbsp;data&nbsp;in&nbsp;</span><span style="color: #9cdcfe;">${db_config_file}</span><span style="color: #ce9178;">."</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Decode&nbsp;using&nbsp;base64&nbsp;decoding,&nbsp;then&nbsp;split&nbsp;data&nbsp;into&nbsp;components&nbsp;per&nbsp;delimiter.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">base64_decode</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">[</span><span style="color: #ce9178;">'Plaintext'</span><span style="color: #d4d4d4;">]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">explode</span><span style="color: #d4d4d4;">(&nbsp;</span><span style="color: #dcdcaa;">chr</span><span style="color: #d4d4d4;">(</span><span style="color: #b5cea8;">7</span><span style="color: #d4d4d4;">),&nbsp;</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">&nbsp;);&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_host</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">0</span><span style="color: #d4d4d4;">];</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_name</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">1</span><span style="color: #d4d4d4;">];</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_user</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">2</span><span style="color: #d4d4d4;">];</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_auth</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">3</span><span style="color: #d4d4d4;">];</span></div><div><br></div></div>            

					<h4 class="site-font-secondary-heading">Capture decryption errors in AWS CloudWatch Logs</h1>

					<p>
						If an error was encountered during the encryption attempt there will be a log in the AWS CloudWatch Logs log stream
						previously configured: php/database/errors/. For simulation's sake we have generated an error to create a log record
						which appears in the AWS CloudWatch logs console as shown below.
					</p>

					<img src="assets/phpcloudwatchlogs_decrypt_error.png" alt="phpcloudwatchlogs_decrypt_error">

					<h4 class="site-font-secondary-heading">Connect to PostgreSQL database and capture connection errors in AWS CloudWatch Logs</h1>

					<p>
						Finally it is time to connect to the database. PHP's PDO data access layer is great for abstracting calls to database
						operations and easy to instantiate. To show the usefulness of CloudWatch logs a simulation of a failed connection attempt
						is run here.
					</p>

					<div class="post-snippet">
						<p>
							<span class="code-php-comment-fc">// Try to connect to postgres database. Log any errors to CloudWatch Logs.</span>
						</p>
					
						<p>
							<span class="code-php-keyword-fc">try</span>
							<span class="code-php-default-fc"> {</span>
						</p>
					
						<p>
							<span class="code-php-var-fc">$pdo</span>
							<span class="code-php-op-fc"> = </span>
							<span class="code-php-keyword-fc">new</span>
							<span class="code-php-type-fc"> PDO</span>
							<span class="code-php-default-fc">(</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">"pgsql:host=</span>
							<span class="code-php-var-fc">${db_host}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    port=65535;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    dbname=</span>
							<span class="code-php-var-fc">${db_name}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    user=</span>
							<span class="code-php-var-fc">${db_user}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    password=</span>
							<span class="code-php-var-fc">${db_auth}</span>
							<span class="code-php-string-fc">;</span>
						</p>
					
						<p>
							<span class="code-php-string-fc">    sslmode=require"</span>
						</p>
					
						<p>
							<span class="code-php-default-fc">);</span>
						</p>
					
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
							<span class="code-php-default-fc">ATTR_ERRMODE, </span>
							<span class="code-php-type-fc">PDO</span>
							<span class="code-php-op-fc">::</span>
							<span class="code-php-default-fc">ERRMODE_EXCEPTION);</span>
						</p>
					
						<p>
							<span class="code-php-default-fc">}</span>
						</p>
					
						<p>
							<span class="code-php-comment-fc">// Connection attempt failed - log failure to CloudWatch Logs and exit.</span>
						</p>
					
						<p>
							<span class="code-php-keyword-fc">catch</span>
							<span class="code-php-default-fc"> (</span>
							<span class="code-php-type-fc">PDOException</span>
							<span class="code-php-default-fc"> </span>
							<span class="code-php-var-fc">$e</span>
							<span class="code-php-default-fc">) {</span>
						</p>
					
						<p>
							<span class="code-php-var-fc">$error_logger</span>
							<span class="code-php-op-fc">-&gt;</span>
							<span class="code-php-func-fc">log</span>
							<span class="code-php-default-fc">([</span>
							<span class="code-php-string-fc">'Problem connecting : '</span>
							<span class="code-php-default-fc"> =&gt; </span>
							<span class="code-php-var-fc">$e</span>
							<span class="code-php-op-fc">-&gt;</span>
							<span class="code-php-func-fc">getMessage</span>
							<span class="code-php-default-fc">()]);</span>
						</p>
					
						<p>
							<span class="code-php-keyword-fc">exit</span>
							<span class="code-php-default-fc">(</span>
							<span class="code-php-string-fc">"Unable to connect to host : </span>
							<span class="code-php-var-fc">${db_host}</span>
							<span class="code-php-string-fc">."</span>
							<span class="code-php-default-fc">);</span>
						</p>
					
						<p>
							<span class="code-php-default-fc">}</span>
						</p>						

					</div>
				
					<p>
						In using a bogus port of 65535 while attempting to connect via PHP PDO to a PostgreSQL database
						the try / catch entered the catch block and generated a log to the php/database/errors/ logstream. The
						log from the AWS CloudWatch logs console is shown below.
					</p>

					<img src="assets/phpcloudwatchlogs_connect_error.png" alt="phpcloudwatchlogs_connect_error">

					<h4 class="site-font-secondary-heading">Log a succesfully run query</h1>
					
					<div
						class="post-snippet"
						style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; line-height: 19px; margin: auto;">
						<div></div>
						<div><br></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #6a9955;">//&nbsp;Run&nbsp;an&nbsp;example&nbsp;query.&nbsp;Log&nbsp;successful&nbsp;queries&nbsp;to&nbsp;the&nbsp;sql&nbsp;log&nbsp;stream</span>
						</div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #6a9955;">//&nbsp;and&nbsp;any&nbsp;errors&nbsp;to&nbsp;the&nbsp;error&nbsp;log&nbsp;stream&nbsp;in&nbsp;CloudWatch&nbsp;Logs.</span>
						</div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span
										style="color: #d4d4d4;">&nbsp;{</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #9cdcfe;">$qry</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span
										style="color: #ce9178;">'</span><span style="color: #569cd6;">SELECT</span><span
										style="color: #ce9178;">&nbsp;datname,&nbsp;datcollate&nbsp;</span><span
										style="color: #569cd6;">FROM</span><span style="color: #ce9178;">&nbsp;pg_database;</span><span
										style="color: #ce9178;">'</span><span style="color: #d4d4d4;">;</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #9cdcfe;">$stmt</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span
										style="color: #9cdcfe;">$pdo</span><span style="color: #d4d4d4;">-&gt;</span><span
										style="color: #dcdcaa;">query</span><span style="color: #d4d4d4;">(</span><span
										style="color: #9cdcfe;">$qry</span><span style="color: #d4d4d4;">);</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #9cdcfe;">$sql_logger</span><span style="color: #d4d4d4;">-&gt;</span><span
										style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span
										style="color: #ce9178;">"User:&nbsp;</span><span style="color: #9cdcfe;">${db_user}</span><span
										style="color: #ce9178;">:&nbsp;Query:&nbsp;"</span><span
										style="color: #d4d4d4;">&nbsp;&nbsp;=&gt;&nbsp;</span><span
										style="color: #9cdcfe;">$qry</span><span style="color: #d4d4d4;">]);</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #6a9955;">//&nbsp;Log&nbsp;failed&nbsp;query&nbsp;prepare&nbsp;statements&nbsp;to&nbsp;CloudWatch&nbsp;Logs.</span>
						</div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #c586c0;">catch</span><span style="color: #d4d4d4;">&nbsp;(</span><span
										style="color: #4ec9b0;">PDOException</span><span style="color: #d4d4d4;">&nbsp;</span><span
										style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">)&nbsp;{</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">-&gt;</span><span
										style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span
										style="color: #ce9178;">'Problem&nbsp;with&nbsp;query&nbsp;'</span><span
										style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$e</span><span
										style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getMessage</span><span
										style="color: #d4d4d4;">()]);</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
										style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span
										style="color: #ce9178;">"Unable&nbsp;to&nbsp;prepare&nbsp;query&nbsp;:&nbsp;</span><span
										style="color: #9cdcfe;">${qry}</span><span style="color: #ce9178;">."</span><span
										style="color: #d4d4d4;">);</span></div>
						<div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div>
						<div><br></div>
						<div></div>
					</div>

					<p>
						And this time the log goes to the php/database/sql/ logstream.
					</p>
						
					<img src="assets/phpcloudwatchlogs_query.png" alt="phpcloudwatchlogs_query">

					<h4 class="site-font-secondary-heading">What can be learned from this article</h1>

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