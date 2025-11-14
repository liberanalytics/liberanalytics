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
	<title>Python PostGre Connect | LiberAnalytics</title>
	<meta name="description" content="Learn how LiberAnalytics collects, uses, and protects your information. Transparency and data privacy are core to our commitment to visitors.">
	<link rel="canonical" href="https://liberanalytics.com/pythonpostgreconnect">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/icon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Python PostGre Connect | LiberAnalytics">
	<meta property="og:description" content="Learn how LiberAnalytics collects, uses, and protects your information. Transparency and data privacy are core to our commitment to visitors.">
	<meta property="og:url" content="https://liberanalytics.com/pythonpostgreconnect">
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
					<span class="site-font-brand">Connecting</span> Python to Postgresql is made seamless with the psycopg2 library.
					This article demonstrates how to connect to a Postgresql database using Python without storing credentials
					in plain text or in the .pgpass file. The architecture we will be using consists of a Postgresql database
					hosted on an Ubuntu 24 (Noble) Amazon Web Services (AWS) EC2 instance and Python 3.10 running on Ubuntu 22 (Jammy).
					We will use a symmetric AWS KMS key to encrypt and decrypt database credentials.
					</p>

					<h4 class="site-font-secondary-heading">Requirements</h1>

					<ul class="site-list">
					<li>Python 3 (preferably running on Ubuntu)</li>
					<li>Python libraries: psycopg2, boto3</li>
					<li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
					<li>Postgresql database cluster (needs to accept TCP/IP connections)</li>
					</ul>

					<h4 class="site-font-secondary-heading">Get database connection parameters</h1>

					<p>
					First we will need to know the database host and database name which will be used to create a config file
					with .ini extension in the user's home directory. Then we will prompt for the user name and password using
					Python's getpass to avoid echoing back the data input by the user. We'll use our AWS KMS key to perform
					encryption of the user name and password. Lastly the encrypted data will be written to the config file
					w/ permissions set to non-readable, non-writable, and non-executable. The following code shows how we can
					do the aforementioned in Python.
					</p>

					<h4 class="site-font-secondary-heading">Connecting to the postgresql database</h1>

					<p>
					Now that we have the connection config file created we are ready to connect to the postgresql
					database cluster and begin sql'ing away. The below aws_postgresql_connect.py program reads the
					encrypted data from the config file created by the aws_postgresql_connect_init.py program then
					decrypts the byte data with the same AWS KMS key. It is important to include the encryption
					algorithm (EncryptionAlgorithm='RSAES_OAEP_SHA_256') in the decrypt call if using an
					asymmetric key in AWS KMS. In this case we are using a symmetric key so the EncryptionAlgorithm='SYMMETRIC_DEFAULT'
					encryption algorithm property isn't needed but we still include it for posterity sake. 
					The data read is then split by delimiter to derive user name/password
					and the config file permissions are disabled. Finally we use the imported psycopg2 libarary to connect
					to our postgresql cluster in AWS EC2 and run a sample query.
					</p>
					
					<div
					class="post-snippet"
					style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; line-height: 19px; margin: auto;">
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;------------------------------------------------------------------</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;aws_postgresql_connect.py</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">#&nbsp;</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Connects&nbsp;to&nbsp;an&nbsp;AWS&nbsp;EC2&nbsp;hosted&nbsp;postgresql&nbsp;database&nbsp;by&nbsp;reading</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;and&nbsp;decrypting&nbsp;credentials&nbsp;stored&nbsp;in&nbsp;a&nbsp;config&nbsp;file.&nbsp;Dependent</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;on&nbsp;the&nbsp;aws_postgresql_config.py&nbsp;having&nbsp;been&nbsp;run&nbsp;first.</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;------------------------------------------------------------------</span>
					</div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">pwd</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">sys</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">os</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">base64</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">boto3</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">psycopg2</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #569cd6;">def</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #dcdcaa;">main</span><span
						style="color: #cccccc;">():</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Instantiate&nbsp;an&nbsp;AWS&nbsp;KMS&nbsp;client&nbsp;instance.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">aws_kms</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #4ec9b0;">boto3</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">client</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'kms'</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #ce9178;">'region'</span><span style="color: #cccccc;">)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;KMS&nbsp;key&nbsp;id.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">the_key</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #ce9178;">'keyid'</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Config&nbsp;file.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">config_dir</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;[</span><span
						style="color: #ce9178;">'HOME'</span><span style="color: #cccccc;">]&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #ce9178;">'/'</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #ce9178;">'conn'</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #ce9178;">'/'</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #d4d4d4;">+</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #9cdcfe;">db_host</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">config_file</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">config_dir</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #ce9178;">'/'</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_name</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #ce9178;">'.ini'</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Make&nbsp;config&nbsp;file&nbsp;readable.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #4ec9b0;">os</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">chmod</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">config_file</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #b5cea8;">440</span><span style="color: #cccccc;">)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Get&nbsp;the&nbsp;connection&nbsp;credentials&nbsp;from&nbsp;config&nbsp;file.</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #c586c0;">with</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">open</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">config_file</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #ce9178;">'rb'</span><span style="color: #cccccc;">)&nbsp;</span><span
						style="color: #c586c0;">as</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">file</span><span style="color: #cccccc;">:</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">credentials_bytes</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">file</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">read</span><span style="color: #cccccc;">()</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Reset&nbsp;config&nbsp;file&nbsp;perms.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #4ec9b0;">os</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">chmod</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">config_file</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #b5cea8;">000</span><span
						style="color: #cccccc;">)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Decrypt&nbsp;the&nbsp;connection&nbsp;credentials.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">credentials_decrypt</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">aws_kms</span><span style="color: #cccccc;">.decrypt(</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">CiphertextBlob</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">credentials_bytes</span><span style="color: #cccccc;">,</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">KeyId</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #569cd6;">f</span><span style="color: #ce9178;">'</span><span
						style="color: #569cd6;">{</span><span style="color: #9cdcfe;">the_key</span><span
						style="color: #569cd6;">}</span><span style="color: #ce9178;">'</span><span style="color: #cccccc;">,</span>
					</div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">EncryptionAlgorithm</span><span style="color: #d4d4d4;">=</span><span
						style="color: #ce9178;">'SYMMETRIC_DEFAULT'</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Decode&nbsp;the&nbsp;credentials&nbsp;and&nbsp;split&nbsp;by&nbsp;delimiter.</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">delimiter</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">chr</span><span style="color: #cccccc;">(</span><span
						style="color: #b5cea8;">7</span><span style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">credentials_decode</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #4ec9b0;">base64</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">b64decode</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">credentials_decrypt</span><span style="color: #cccccc;">[</span><span
						style="color: #ce9178;">'Plaintext'</span><span style="color: #cccccc;">])</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">credentials</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #4ec9b0;">bytes</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">decode</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">credentials_decode</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #ce9178;">'utf-8'</span><span style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_data</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">credentials</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">split</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">delimiter</span><span style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_host</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_data</span><span style="color: #cccccc;">[</span><span
						style="color: #b5cea8;">0</span><span style="color: #cccccc;">]</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_name</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_data</span><span style="color: #cccccc;">[</span><span
						style="color: #b5cea8;">1</span><span style="color: #cccccc;">]</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_user</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_data</span><span style="color: #cccccc;">[</span><span
						style="color: #b5cea8;">2</span><span style="color: #cccccc;">]</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_auth</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_data</span><span style="color: #cccccc;">[</span><span
						style="color: #b5cea8;">3</span><span style="color: #cccccc;">]</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Form&nbsp;a&nbsp;connection&nbsp;string&nbsp;&amp;&nbsp;connect.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">connection_string</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;\</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #ce9178;">"&nbsp;host="</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_host</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;\</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #ce9178;">"&nbsp;dbname="</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_name</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;\</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #ce9178;">"&nbsp;user="</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_user</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;\</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #ce9178;">"&nbsp;password="</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_auth</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;\</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #ce9178;">"&nbsp;port=5432"</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">conn</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #4ec9b0;">psycopg2</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">connect</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">connection_string</span><span style="color: #cccccc;">)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Example&nbsp;query.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">cur</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">conn</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">cursor</span><span style="color: #cccccc;">()</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">cur</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">execute</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'SELECT&nbsp;CURRENT_DATE;'</span><span style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">rs</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">cur</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">fetchall</span><span style="color: #cccccc;">()</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Iterate&nbsp;through&nbsp;resultset.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #c586c0;">for</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">row</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #c586c0;">in</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">rs</span><span style="color: #cccccc;">:</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #dcdcaa;">print</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">row</span><span style="color: #cccccc;">)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Close&nbsp;cursor&nbsp;and&nbsp;connection.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">cur</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">close</span><span style="color: #cccccc;">()</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">conn</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">close</span><span style="color: #cccccc;">()</span></div>
					<div><br></div>
					</div>

					<h4 class="site-font-secondary-heading">Key Takeaways</h1>

					<p>
					In this article we have connected to a postgresql database cluster hosted in AWS EC2
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
		</div>
	</main>

    <?php include_once('lafooter.php'); ?>

</body>
</html>