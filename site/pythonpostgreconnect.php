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
    <title>Connecting Python to a Postgresql database | LiberAnalytics</title>
    <meta name="description" content="<?php echo $postMetaDesc; ?>">
    <link rel="canonical" href="https://liberanalytics.com/pythonpostgreconnect">

    <!-- Favicon. -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

    <!-- Social media Open Graph data points. -->
    <meta property="og:title" content="Connecting Python to a Postgresql database | LiberAnalytics">
    <meta property="og:description" content="<?php echo $postMetaDesc; ?>">
    <meta property="og:url" content="https://liberanalytics.com/pythonpostgreconnect">
    <meta property="og:type" content="article">
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
					<span class="site-font-brand">Connecting</span> Python to PostgreSQL is made seamless with the psycopg2 library.
					This article demonstrates how to connect to a PostgreSQL database using Python without storing credentials
					in plain text or in the .pgpass file. The architecture we will be using consists of a PostgreSQL database
					hosted on an Ubuntu 24 (Noble) Amazon Web Services (AWS) EC2 instance and Python 3.10 running on Ubuntu 22 (Jammy).
					We will use a symmetric AWS KMS key to encrypt and decrypt database credentials.
					</p>

					<h3 class="site-font-secondary-heading">Requirements</h3>

					<ul class="site-list">
						<li>Python 3 (preferably running on Ubuntu)</li>
						<li>Python libraries: psycopg2, boto3</li>
						<li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
						<li>PostgreSQL database cluster (needs to accept TCP/IP connections)</li>
					</ul>

					<h3 class="site-font-secondary-heading">Get database connection parameters</h3>

					<p>
					First we will need to know the database host and database name which will be used to create a config file
					with .ini extension in the user's home directory. Then we will prompt for the user name and password using
					Python's getpass to avoid echoing back the data input by the user. We'll use our AWS KMS key to perform
					encryption of the user name and password. Lastly the encrypted data will be written to the config file
					w/ permissions set to non-readable, non-writable, and non-executable. The following script is an example you can adapt.
					Replace placeholders like 'region' and 'keyid' with values from your own AWS and PostgreSQL setup. You may also want
					to add your own error handling and logging.
					</p>

					<div class="post-snippet">

						<!-- Comments -->
						<div>
							<p><span class="post-snippet-font-std code-python-comment-fc"># ------------------------------------------------------------------</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># aws_postgresql_config.py</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc">#</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># Creates a .ini file containing encrypted credentials used
							# to connect to an AWS EC2 hosted PostgreSQL database.</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># ------------------------------------------------------------------</span></p>
						</div>

						<!-- imports -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">pwd</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">sys</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">os</span>
							</p>					
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">from&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">getpass&nbsp;</span>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">getpass</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">json</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">traceback</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">base64</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">boto3</span>
							</p>
						</div>
	
						<!-- Function definition -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-special-fc">def&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">main</span><span class="post-snippet-font-std code-python-default-fc">():</span>
							</p>
						</div>
	
						<!-- Instantiate KMS client -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Instantiate an AWS KMS client instance.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">aws_kms&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">boto3</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">client</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'kms'</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'region'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>
	
						<!-- KMS key id -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># KMS key id.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">the_key&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'keyid'</span>
							</p>
						</div>
	
						<!-- Prompt for connection params -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Prompt for the connection params.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-func-fc">print</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'Please&nbsp;provide&nbsp;connection&nbsp;parameters&nbsp;for&nbsp;database:'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'\n'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_host&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">getpass</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'Please&nbsp;enter&nbsp;database&nbsp;host:'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_name&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">getpass</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'Please&nbsp;enter&nbsp;database&nbsp;name:'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_user&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">getpass</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'Please&nbsp;enter&nbsp;database&nbsp;user&nbsp;name:'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_auth&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">getpass</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'Please&nbsp;enter&nbsp;user&nbsp;password:'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>
	
						<!-- Config file -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Config file.</span>
							</p>														
							<p>
								<span class="post-snippet-font-std code-python-var-fc">config_dir&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">os</span>
								<span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">getenv</span>
								<span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'HOME'</span>
								<span class="post-snippet-font-std code-python-default-fc">)</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'/'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'conn'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'/'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_host</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">config_file&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">config_dir</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'/'</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_name</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'.ini'</span>
							</p>
						</div>
	
						<!-- Concat + delimit and encode -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Concat + delimit params, convert to bytes, then base64 encode.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">delim&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">chr</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-number-fc">7</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">params&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_host</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">delim</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_name</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">delim</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_user</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">delim</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_auth</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">byte_params&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">params</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">encode</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'utf-8'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">encode_params&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">base64</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">b64encode</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">byte_params</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>
	
						<!-- Encrypt the params -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Encrypt the params.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">encryption_data&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">aws_kms</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">encrypt</span><span class="post-snippet-font-std code-python-default-fc">(</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">KeyId</span><span class="post-snippet-font-std code-python-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">the_key</span><span class="post-snippet-font-std code-python-default-fc">,</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">Plaintext</span><span class="post-snippet-font-std code-python-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">encode_params</span><span class="post-snippet-font-std code-python-default-fc">,</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">EncryptionAlgorithm</span><span class="post-snippet-font-std code-python-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'SYMMETRIC_DEFAULT'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>
	
						<!-- Write encrypted params to file -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Write the encryption params as binary to the config file.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">with&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">open</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">config_file</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'wb'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
								<span class="post-snippet-font-std code-python-keyword-fc">&nbsp;as&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">file</span><span class="post-snippet-font-std code-python-default-fc">:</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">file</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">write</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">encryption_data</span><span class="post-snippet-font-std code-python-default-fc">[</span>
								<span class="post-snippet-font-std code-python-string-fc">'CiphertextBlob'</span><span class="post-snippet-font-std code-python-default-fc">])</span>
							</p>
						</div>
	
						<!-- Reset file perms -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Reset file perms.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-type-fc">os</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">chmod</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">config_file</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-number-fc">0o000</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>					
					</div>					

					<h3 class="site-font-secondary-heading">Connecting to the PostgreSQL database</h3>

					<p>
					Now that we have the connection config file created we are ready to connect to the PostgreSQL
					database cluster and begin running SQL queries. The below aws_postgresql_connect.py program reads the
					encrypted data from the config file created by the aws_postgresql_config.py program then
					decrypts the byte data with the same AWS KMS key. It is important to include the encryption
					algorithm (EncryptionAlgorithm='RSAES_OAEP_SHA_256') in the decrypt call if using an
					asymmetric key in AWS KMS. In this case we are using a symmetric key so the EncryptionAlgorithm='SYMMETRIC_DEFAULT'
					encryption algorithm property isn't needed but we still include it for posterity sake. 
					The data read is then split by delimiter to derive user name/password
					and the config file permissions are disabled. Finally we use the imported psycopg2 library to connect
					to our PostgreSQL cluster in AWS EC2 and run a sample query. This connect script assumes you already
					ran aws_postgresql_config.py with the same host and database name, and that your AWS credentials
					allow calls to KMS.
					</p>
					
					<div class="post-snippet">

						<!-- Comments -->
						<div>
							<p><span class="post-snippet-font-std code-python-comment-fc"># ------------------------------------------------------------------</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># aws_postgresql_connect.py</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc">#</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># Connects to an AWS EC2 hosted PostgreSQL database by reading</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># and decrypting credentials stored in a config file. Dependent</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># on the aws_postgresql_config.py having been run first.</span></p>
							<p><span class="post-snippet-font-std code-python-comment-fc"># ------------------------------------------------------------------</span></p>
						</div>

						<!-- Imports -->
						<div class="site-mt">
							<p class="site-mt">
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">pwd</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">sys</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">os</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">base64</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">boto3</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">import&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">psycopg2</span>
							</p>
						</div>

						<!-- main() definition -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-special-fc">def&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">main</span><span class="post-snippet-font-std code-python-default-fc">():</span>
							</p>
						</div>

						<!-- Instantiate KMS client -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Instantiate an AWS KMS client instance.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">aws_kms</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">boto3</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">client</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'kms'</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'region'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>

						<!-- KMS key id -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># KMS key id.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">the_key</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'keyid'</span>
							</p>
						</div>

						<!-- Config file path -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Config file.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Replace 'your-db-host' and 'your-db-name' with the values</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># you used when running aws_postgresql_config.py.</span>
							</p>							
							<p>
								<span class="post-snippet-font-std code-python-var-fc">config_dir</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">os</span>
								<span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">getenv</span>
								<span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'HOME'</span>
								<span class="post-snippet-font-std code-python-default-fc">)</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'/'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'conn'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'/'</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'your-db-host'</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">config_file</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">config_dir</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'/'</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'your-db-name'</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;</span>
								<span class="post-snippet-font-std code-python-op-fc">+&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'.ini'</span>
							</p>
						</div>

						<!-- Make config file readable -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Make config file readable.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-type-fc">os</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">chmod</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">config_file</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-number-fc">0o440</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>

						<!-- Get connection credentials from config file -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Get the connection credentials from config file.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">with&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">open</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">config_file</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'rb'</span><span class="post-snippet-font-std code-python-default-fc">)&nbsp;</span>
								<span class="post-snippet-font-std code-python-keyword-fc">as</span>
								<span class="post-snippet-font-std code-python-var-fc">file</span><span class="post-snippet-font-std code-python-default-fc">:</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">credentials_bytes</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">file</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">read</span><span class="post-snippet-font-std code-python-default-fc">()</span>
							</p>
						</div>

						<!-- Reset config file perms -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Reset config file perms.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-type-fc">os</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">chmod</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">config_file</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-number-fc">0o000</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>

						<!-- Decrypt the connection credentials -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Decrypt the connection credentials.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">credentials_decrypt</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">aws_kms</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">decrypt</span><span class="post-snippet-font-std code-python-default-fc">(</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">CiphertextBlob</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">credentials_bytes</span><span class="post-snippet-font-std code-python-default-fc">,</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">KeyId</span>
								<span class="post-snippet-font-std code-python-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-special-fc">f</span><span class="post-snippet-font-std code-python-string-fc">'{</span>
								<span class="post-snippet-font-std code-python-var-fc">the_key</span><span class="post-snippet-font-std code-python-string-fc">}'</span>
								<span class="post-snippet-font-std code-python-default-fc">,</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">EncryptionAlgorithm</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'SYMMETRIC_DEFAULT'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>

						<!-- Decode and split credentials -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Decode the credentials and split by delimiter.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">delimiter</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-func-fc">chr</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-number-fc">7</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">credentials_decode</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">base64</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">b64decode</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">credentials_decrypt</span><span class="post-snippet-font-std code-python-default-fc">[</span>
								<span class="post-snippet-font-std code-python-string-fc">'Plaintext'</span><span class="post-snippet-font-std code-python-default-fc">])</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">credentials</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">bytes</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">decode</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">credentials_decode</span><span class="post-snippet-font-std code-python-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-python-string-fc">'utf-8'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_data</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">credentials</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">split</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">delimiter</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_host</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_data</span><span class="post-snippet-font-std code-python-default-fc">[</span>
								<span class="post-snippet-font-std code-python-number-fc">0</span><span class="post-snippet-font-std code-python-default-fc">]</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_name</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_data</span><span class="post-snippet-font-std code-python-default-fc">[</span>
								<span class="post-snippet-font-std code-python-number-fc">1</span><span class="post-snippet-font-std code-python-default-fc">]</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_user</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_data</span><span class="post-snippet-font-std code-python-default-fc">[</span>
								<span class="post-snippet-font-std code-python-number-fc">2</span><span class="post-snippet-font-std code-python-default-fc">]</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">db_auth</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_data</span><span class="post-snippet-font-std code-python-default-fc">[</span>
								<span class="post-snippet-font-std code-python-number-fc">3</span><span class="post-snippet-font-std code-python-default-fc">]</span>
							</p>
						</div>

						<!-- Form connection string & connect -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Form a connection string &amp; connect.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">connection_string</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span><span class="post-snippet-font-std code-python-default-fc">\</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-string-fc">"&nbsp;host="</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_host</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span><span class="post-snippet-font-std code-python-default-fc">\</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-string-fc">"&nbsp;dbname="</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_name</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span><span class="post-snippet-font-std code-python-default-fc">\</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-string-fc">"&nbsp;user="</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_user</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span><span class="post-snippet-font-std code-python-default-fc">\</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-string-fc">"&nbsp;password="</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">db_auth</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;+&nbsp;</span><span class="post-snippet-font-std code-python-default-fc">\</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-string-fc">"&nbsp;port=5432"</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">conn</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-type-fc">psycopg2</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">connect</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">connection_string</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>

						<!-- Example query -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Example query.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">cur</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">conn</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">cursor</span><span class="post-snippet-font-std code-python-default-fc">()</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">cur</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">execute</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-string-fc">'SELECT&nbsp;CURRENT_DATE;'</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">rs</span>
								<span class="post-snippet-font-std code-python-op-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-python-var-fc">cur</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">fetchall</span><span class="post-snippet-font-std code-python-default-fc">()</span>
							</p>
						</div>

						<!-- Iterate through resultset -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Iterate through resultset.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-keyword-fc">for</span>
								<span class="post-snippet-font-std code-python-var-fc">&nbsp;row</span>
								<span class="post-snippet-font-std code-python-keyword-fc">&nbsp;in</span>
								<span class="post-snippet-font-std code-python-var-fc">&nbsp;rs</span><span class="post-snippet-font-std code-python-default-fc">:</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-func-fc">print</span><span class="post-snippet-font-std code-python-default-fc">(</span>
								<span class="post-snippet-font-std code-python-var-fc">row</span><span class="post-snippet-font-std code-python-default-fc">)</span>
							</p>
						</div>

						<!-- Close cursor and connection -->
						<div class="site-mt">
							<p>
								<span class="post-snippet-font-std code-python-comment-fc"># Close cursor and connection.</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">cur</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">close</span><span class="post-snippet-font-std code-python-default-fc">()</span>
							</p>
							<p>
								<span class="post-snippet-font-std code-python-var-fc">conn</span><span class="post-snippet-font-std code-python-default-fc">.</span>
								<span class="post-snippet-font-std code-python-func-fc">close</span><span class="post-snippet-font-std code-python-default-fc">()</span>
							</p>
						</div>

					</div>

					<h3 class="site-font-secondary-heading">Key Takeaways</h3>

					<p>
					In this article we have connected to a PostgreSQL database cluster hosted in AWS EC2
					without storing database credentials or encryption keys anywhere on the filesystem. Although
					the code presented could be refactored to eliminate redundancies or abstracted into a class, 
					the key points listed below could help in securely connecting to databases.
					</p>

					<ul class="site-list">
					<li>Minimize visibility and access to database credentials</li>
					<li>If hosting in a cloud service provider, make use of symmetric keys to protect
						sensitive data such as user names or passwords
					</li>
					<li>Use encryption when storing database credentials on filesystem</li>
					<li>Try to avoid storing encryption keys or plain text user names/passwords</li>
					</ul>

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
