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

					<div class="post-snippet site-stack-mt">

						<p><span class="code-comment"># ------------------------------------------------------------------</span></p>
						<p><span class="code-comment"># aws_postgresql_connect.py</span></p>
						<p><span class="code-comment">#</span></p>
						<p><span class="code-comment"># Creates a .ini file containing encrypted credentials used
						# to connect to an AWS EC2 hosted postgresql database.</span></p>
						<p><span class="code-comment"># ------------------------------------------------------------------</span></p>
	
						<!-- imports -->
						<div>
							<p class="site-mt">
								<span class="code-keyword">import</span>
								<span class="code-type">pwd</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">sys</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">os</span>
							</p>					
							<p>
								<span class="code-keyword">from</span>
								<span class="code-type">getpass</span>
								<span class="code-keyword">import</span>
								<span class="code-func">getpass</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">json</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">traceback</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">base64</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">boto3</span>
							</p>
						</div>
	
						<!-- Function definition -->
						<div>
							<p>
								<span class="code-special">def </span>
								<span class="code-func">main</span><span class="code-default">():</span>
							</p>
						</div>
	
						<!-- Instantiate KMS client -->
						<div>
							<p>
								<span class="code-comment"># Instantiate an AWS KMS client instance.</span>
							</p>
							<p>
								<span class="code-var">aws_kms </span>
								<span class="code-op">= </span>
								<span class="code-type">boto3</span><span class="code-default">.</span>
								<span class="code-func">client</span><span class="code-default">(</span>
								<span class="code-string">'kms'</span><span class="code-default">, </span>
								<span class="code-string">'region'</span><span class="code-default">)</span>
							</p>
						</div>
	
						<!-- KMS key id -->
						<div>
							<p>
								<span class="code-comment"># KMS key id.</span>
							</p>
							<p>
								<span class="code-var">the_key </span>
								<span class="code-op">= </span>
								<span class="code-string">'keyid'</span>
							</p>
						</div>
	
						<!-- Prompt for connection params -->
						<div>
							<p>
								<span class="code-comment"># Prompt for the connection params.</span>
							</p>
							<p>
								<span class="code-func">print</span><span class="code-default">(</span>
								<span class="code-string">'Please provide connection parameters for database:'</span>
								<span class="code-default"> </span><span class="code-op">+ </span>
								<span class="code-string">'\n'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">db_host </span>
								<span class="code-op">= </span>
								<span class="code-func">getpass</span><span class="code-default">(</span>
								<span class="code-string">'Please enter database host:'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">db_name </span>
								<span class="code-op">= </span>
								<span class="code-func">getpass</span><span class="code-default">(</span>
								<span class="code-string">'Please enter database name:'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">db_user </span>
								<span class="code-op">= </span>
								<span class="code-func">getpass</span><span class="code-default">(</span>
								<span class="code-string">'Please enter database user name:'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">db_auth </span>
								<span class="code-op">= </span>
								<span class="code-func">getpass</span><span class="code-default">(</span>
								<span class="code-string">'Please enter user password:'</span><span class="code-default">)</span>
							</p>
						</div>
	
						<!-- Config file -->
						<div>
							<p>
								<span class="code-comment"># Config file.</span>
							</p>
							<p>
								<span class="code-var">config_dir </span>
								<span class="code-op">= </span>
								<span class="code-default">[</span>
								<span class="code-string">'HOME'</span>
								<span class="code-default">] </span>
								<span class="code-op">+ </span>
								<span class="code-string">'/'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'conn'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'/'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-var">db_host</span>
							</p>
							<p>
								<span class="code-var">config_file </span>
								<span class="code-op">= </span>
								<span class="code-var">config_dir</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'/'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-var">db_name</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'.ini'</span>
							</p>
						</div>
	
						<!-- Concat + delimit and encode -->
						<div>
							<p>
								<span class="code-comment"># Concat + delimit params, convert to bytes, then base64 encode.</span>
							</p>
							<p>
								<span class="code-var">delim </span>
								<span class="code-op">= </span>
								<span class="code-func">chr</span><span class="code-default">(</span>
								<span class="code-number">7</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">params </span>
								<span class="code-op">= </span>
								<span class="code-var">db_host</span>
								<span class="code-op"> + </span>
								<span class="code-var">delim</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_name</span>
								<span class="code-op"> + </span>
								<span class="code-var">delim</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_user</span>
								<span class="code-op"> + </span>
								<span class="code-var">delim</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_auth</span>
							</p>
							<p>
								<span class="code-var">byte_params </span>
								<span class="code-op">= </span>
								<span class="code-var">params</span><span class="code-default">.</span>
								<span class="code-func">encode</span><span class="code-default">(</span>
								<span class="code-string">'utf-8'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">encode_params </span>
								<span class="code-op">= </span>
								<span class="code-type">base64</span><span class="code-default">.</span>
								<span class="code-func">b64encode</span><span class="code-default">(</span>
								<span class="code-var">byte_params</span><span class="code-default">)</span>
							</p>
						</div>
	
						<!-- Encrypt the params -->
						<div>
							<p>
								<span class="code-comment"># Encrypt the params.</span>
							</p>
							<p>
								<span class="code-var">encryption_data </span>
								<span class="code-op">= </span>
								<span class="code-var">aws_kms</span><span class="code-default">.</span>
								<span class="code-func">encrypt</span><span class="code-default">(</span>
							</p>
							<p>
								<span class="code-var">KeyId</span><span class="code-default"> = </span>
								<span class="code-var">the_key</span><span class="code-default">,</span>
							</p>
							<p>
								<span class="code-var">Plaintext</span><span class="code-default"> = </span>
								<span class="code-var">encode_params</span><span class="code-default">,</span>
							</p>
							<p>
								<span class="code-var">EncryptionAlgorithm</span><span class="code-default"> = </span>
								<span class="code-string">'SYMMETRIC_DEFAULT'</span><span class="code-default">)</span>
							</p>
						</div>
	
						<!-- Write encrypted params to file -->
						<div>
							<p>
								<span class="code-comment"># Write the encryption params as binary to the config file.</span>
							</p>
							<p>
								<span class="code-keyword">with </span>
								<span class="code-func">open</span><span class="code-default">(</span>
								<span class="code-var">config_file</span><span class="code-default">, </span>
								<span class="code-string">'wb'</span><span class="code-default">)</span>
								<span class="code-keyword"> as </span>
								<span class="code-var">file</span><span class="code-default">:</span>
							</p>
							<p>
								<span class="code-var">file</span><span class="code-default">.</span>
								<span class="code-func">write</span><span class="code-default">(</span>
								<span class="code-var">encryption_data</span><span class="code-default">[</span>
								<span class="code-string">'CiphertextBlob'</span><span class="code-default">])</span>
							</p>
						</div>
	
						<!-- Reset file perms -->
						<div>
							<p>
								<span class="code-comment"># Reset file perms.</span>
							</p>
							<p>
								<span class="code-type">os</span><span class="code-default">.</span>
								<span class="code-func">chmod</span><span class="code-default">(</span>
								<span class="code-var">config_file</span><span class="code-default">, </span>
								<span class="code-number">000</span><span class="code-default">)</span>
							</p>
						</div>					
					</div>					

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
					
					<div class="post-snippet site-stack-mt">

						<!-- Header -->
						<div>
							<p><span class="code-comment"># ------------------------------------------------------------------</span></p>
							<p><span class="code-comment"># aws_postgresql_connect.py</span></p>
							<p><span class="code-comment">#</span></p>
							<p><span class="code-comment"># Connects to an AWS EC2 hosted postgresql database by reading</span></p>
							<p><span class="code-comment"># and decrypting credentials stored in a config file. Dependent</span></p>
							<p><span class="code-comment"># on the aws_postgresql_config.py having been run first.</span></p>
							<p><span class="code-comment"># ------------------------------------------------------------------</span></p>
						</div>

						<!-- Imports -->
						<div>
							<p class="site-mt">
								<span class="code-keyword">import</span>
								<span class="code-type">pwd</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">sys</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">os</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">base64</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">boto3</span>
							</p>
							<p>
								<span class="code-keyword">import</span>
								<span class="code-type">psycopg2</span>
							</p>
						</div>

						<!-- main() definition -->
						<div>
							<p>
								<span class="code-special">def </span>
								<span class="code-func">main</span><span class="code-default">():</span>
							</p>
						</div>

						<!-- Instantiate KMS client -->
						<div>
							<p>
								<span class="code-comment"># Instantiate an AWS KMS client instance.</span>
							</p>
							<p>
								<span class="code-var">aws_kms</span>
								<span class="code-op"> = </span>
								<span class="code-type">boto3</span><span class="code-default">.</span>
								<span class="code-func">client</span><span class="code-default">(</span>
								<span class="code-string">'kms'</span><span class="code-default">, </span>
								<span class="code-string">'region'</span><span class="code-default">)</span>
							</p>
						</div>

						<!-- KMS key id -->
						<div>
							<p>
								<span class="code-comment"># KMS key id.</span>
							</p>
							<p>
								<span class="code-var">the_key</span>
								<span class="code-op"> = </span>
								<span class="code-string">'keyid'</span>
							</p>
						</div>

						<!-- Config file path -->
						<div>
							<p>
								<span class="code-comment"># Config file.</span>
							</p>
							<p>
								<span class="code-var">config_dir</span>
								<span class="code-op"> = </span>
								<span class="code-default">[</span>
								<span class="code-string">'HOME'</span>
								<span class="code-default">] </span>
								<span class="code-op">+ </span>
								<span class="code-string">'/'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'conn'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'/'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-var">db_host</span>
							</p>
							<p>
								<span class="code-var">config_file</span>
								<span class="code-op"> = </span>
								<span class="code-var">config_dir</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'/'</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-var">db_name</span>
								<span class="code-default"> </span>
								<span class="code-op">+ </span>
								<span class="code-string">'.ini'</span>
							</p>
						</div>

						<!-- Make config file readable -->
						<div>
							<p>
								<span class="code-comment"># Make config file readable.</span>
							</p>
							<p>
								<span class="code-type">os</span><span class="code-default">.</span>
								<span class="code-func">chmod</span><span class="code-default">(</span>
								<span class="code-var">config_file</span><span class="code-default">, </span>
								<span class="code-number">440</span><span class="code-default">)</span>
							</p>
						</div>

						<!-- Get connection credentials from config file -->
						<div>
							<p>
								<span class="code-comment"># Get the connection credentials from config file.</span>
							</p>
							<p>
								<span class="code-keyword">with</span>
								<span class="code-func">open</span><span class="code-default">(</span>
								<span class="code-var">config_file</span><span class="code-default">, </span>
								<span class="code-string">'rb'</span><span class="code-default">) </span>
								<span class="code-keyword">as</span>
								<span class="code-var">file</span><span class="code-default">:</span>
							</p>
							<p>
								<span class="code-var">credentials_bytes</span>
								<span class="code-op"> = </span>
								<span class="code-var">file</span><span class="code-default">.</span>
								<span class="code-func">read</span><span class="code-default">()</span>
							</p>
						</div>

						<!-- Reset config file perms -->
						<div>
							<p>
								<span class="code-comment"># Reset config file perms.</span>
							</p>
							<p>
								<span class="code-type">os</span><span class="code-default">.</span>
								<span class="code-func">chmod</span><span class="code-default">(</span>
								<span class="code-var">config_file</span><span class="code-default">, </span>
								<span class="code-number">000</span><span class="code-default">)</span>
							</p>
						</div>

						<!-- Decrypt the connection credentials -->
						<div>
							<p>
								<span class="code-comment"># Decrypt the connection credentials.</span>
							</p>
							<p>
								<span class="code-var">credentials_decrypt</span>
								<span class="code-op"> = </span>
								<span class="code-var">aws_kms</span><span class="code-default">.</span>
								<span class="code-func">decrypt</span><span class="code-default">(</span>
							</p>
							<p>
								<span class="code-var">CiphertextBlob</span>
								<span class="code-default"> = </span>
								<span class="code-var">credentials_bytes</span><span class="code-default">,</span>
							</p>
							<p>
								<span class="code-var">KeyId</span>
								<span class="code-default"> = </span>
								<span class="code-special">f</span><span class="code-string">'{</span>
								<span class="code-var">the_key</span><span class="code-string">}'</span>
								<span class="code-default">,</span>
							</p>
							<p>
								<span class="code-var">EncryptionAlgorithm</span>
								<span class="code-op">=</span>
								<span class="code-string">'SYMMETRIC_DEFAULT'</span><span class="code-default">)</span>
							</p>
						</div>

						<!-- Decode and split credentials -->
						<div>
							<p>
								<span class="code-comment"># Decode the credentials and split by delimiter.</span>
							</p>
							<p>
								<span class="code-var">delimiter</span>
								<span class="code-op"> = </span>
								<span class="code-func">chr</span><span class="code-default">(</span>
								<span class="code-number">7</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">credentials_decode</span>
								<span class="code-op"> = </span>
								<span class="code-type">base64</span><span class="code-default">.</span>
								<span class="code-func">b64decode</span><span class="code-default">(</span>
								<span class="code-var">credentials_decrypt</span><span class="code-default">[</span>
								<span class="code-string">'Plaintext'</span><span class="code-default">])</span>
							</p>
							<p>
								<span class="code-var">credentials</span>
								<span class="code-op"> = </span>
								<span class="code-type">bytes</span><span class="code-default">.</span>
								<span class="code-func">decode</span><span class="code-default">(</span>
								<span class="code-var">credentials_decode</span><span class="code-default">, </span>
								<span class="code-string">'utf-8'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">db_data</span>
								<span class="code-op"> = </span>
								<span class="code-var">credentials</span><span class="code-default">.</span>
								<span class="code-func">split</span><span class="code-default">(</span>
								<span class="code-var">delimiter</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">db_host</span>
								<span class="code-op"> = </span>
								<span class="code-var">db_data</span><span class="code-default">[</span>
								<span class="code-number">0</span><span class="code-default">]</span>
							</p>
							<p>
								<span class="code-var">db_name</span>
								<span class="code-op"> = </span>
								<span class="code-var">db_data</span><span class="code-default">[</span>
								<span class="code-number">1</span><span class="code-default">]</span>
							</p>
							<p>
								<span class="code-var">db_user</span>
								<span class="code-op"> = </span>
								<span class="code-var">db_data</span><span class="code-default">[</span>
								<span class="code-number">2</span><span class="code-default">]</span>
							</p>
							<p>
								<span class="code-var">db_auth</span>
								<span class="code-op"> = </span>
								<span class="code-var">db_data</span><span class="code-default">[</span>
								<span class="code-number">3</span><span class="code-default">]</span>
							</p>
						</div>

						<!-- Form connection string & connect -->
						<div>
							<p>
								<span class="code-comment"># Form a connection string &amp; connect.</span>
							</p>
							<p>
								<span class="code-var">connection_string</span>
								<span class="code-op"> = </span><span class="code-default">\</span>
							</p>
							<p>
								<span class="code-string">" host="</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_host</span>
								<span class="code-op"> + </span><span class="code-default">\</span>
							</p>
							<p>
								<span class="code-string">" dbname="</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_name</span>
								<span class="code-op"> + </span><span class="code-default">\</span>
							</p>
							<p>
								<span class="code-string">" user="</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_user</span>
								<span class="code-op"> + </span><span class="code-default">\</span>
							</p>
							<p>
								<span class="code-string">" password="</span>
								<span class="code-op"> + </span>
								<span class="code-var">db_auth</span>
								<span class="code-op"> + </span><span class="code-default">\</span>
							</p>
							<p>
								<span class="code-string">" port=5432"</span>
							</p>
							<p>
								<span class="code-var">conn</span>
								<span class="code-op"> = </span>
								<span class="code-type">psycopg2</span><span class="code-default">.</span>
								<span class="code-func">connect</span><span class="code-default">(</span>
								<span class="code-var">connection_string</span><span class="code-default">)</span>
							</p>
						</div>

						<!-- Example query -->
						<div>
							<p>
								<span class="code-comment"># Example query.</span>
							</p>
							<p>
								<span class="code-var">cur</span>
								<span class="code-op"> = </span>
								<span class="code-var">conn</span><span class="code-default">.</span>
								<span class="code-func">cursor</span><span class="code-default">()</span>
							</p>
							<p>
								<span class="code-var">cur</span><span class="code-default">.</span>
								<span class="code-func">execute</span><span class="code-default">(</span>
								<span class="code-string">'SELECT CURRENT_DATE;'</span><span class="code-default">)</span>
							</p>
							<p>
								<span class="code-var">rs</span>
								<span class="code-op"> = </span>
								<span class="code-var">cur</span><span class="code-default">.</span>
								<span class="code-func">fetchall</span><span class="code-default">()</span>
							</p>
						</div>

						<!-- Iterate through resultset -->
						<div>
							<p>
								<span class="code-comment"># Iterate through resultset.</span>
							</p>
							<p>
								<span class="code-keyword">for</span>
								<span class="code-var"> row</span>
								<span class="code-keyword"> in</span>
								<span class="code-var"> rs</span><span class="code-default">:</span>
							</p>
							<p>
								<span class="code-func">print</span><span class="code-default">(</span>
								<span class="code-var">row</span><span class="code-default">)</span>
							</p>
						</div>

						<!-- Close cursor and connection -->
						<div>
							<p>
								<span class="code-comment"># Close cursor and connection.</span>
							</p>
							<p>
								<span class="code-var">cur</span><span class="code-default">.</span>
								<span class="code-func">close</span><span class="code-default">()</span>
							</p>
							<p>
								<span class="code-var">conn</span><span class="code-default">.</span>
								<span class="code-func">close</span><span class="code-default">()</span>
							</p>
						</div>



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