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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LiberAnalytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" type="text/css" charset="utf-8"> 
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">	
    <link href="css/post.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/la.js"></script>
</head>

<body>

    <?php include_once('site_header.php'); ?>

    <main>
	<div id="page_w">
		<div id="blog_w" class="site-font-stdl">
			<article>
				<header>
					<?php
						$postName = basename($_SERVER['PHP_SELF'], ".php");
						$post = $laData->crudData(
							CrudOperation::GETPOSTS,
							CrudType::CRUDREAD,
							CrudResultsetType::RESULTSET_ROWS,
							CrudFetchStyle::FETCHSTYLE_ASSOC,
							[ 'post_name' => $postName]						
						)[0];
					?>

					<h1 class="blog-heading site-font-fancyl"><?php echo $post['short_desc']; ?></h1>
					<h3 class="blog-date"><?php echo date("F j, Y", strtotime($post['post_created'])); ?></h3>
					<h2 class="blog-desc site-font-emph"><?php echo $post['long_desc']; ?></h2>
					<img <?php echo "src=\"assets/{$post['post_img']}\""; ?> class="blog-img">
				</header>

				<section>

					<p>
					<em class="site-font-fancyl">Connecting</em> Python to Postgresql is made seamless with the psycopg2 library.
					This article demonstrates how to connect to a Postgresql database using Python without storing credentials
					in plain text or in the .pgpass file. The architecture we will be using consists of a Postgresql database
					hosted on an Ubuntu 24 (Noble) Amazon Web Services (AWS) EC2 instance and Python 3.10 running on Ubuntu 22 (Jammy).
					We will use a symmetric AWS KMS key to encrypt and decrypt database credentials.
					</p>

					<h1 class="blog-subheading">Requirements</h1>

					<ul>
					<li>Python 3 (preferably running on Ubuntu)</li>
					<li>Python libraries: psycopg2, boto3</li>
					<li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
					<li>Postgresql database cluster (needs to accept TCP/IP connections)</li>
					</ul>

					<h1 class="blog-subheading">Get database connection parameters</h1>

					<p>
					First we will need to know the database host and database name which will be used to create a config file
					with .ini extension in the user's home directory. Then we will prompt for the user name and password using
					Python's getpass to avoid echoing back the data input by the user. We'll use our AWS KMS key to perform
					encryption of the user name and password. Lastly the encrypted data will be written to the config file
					w/ permissions set to non-readable, non-writable, and non-executable. The following code shows how we can
					do the aforementioned in Python.
					</p>

					<div
					class="blog-snippet"
					style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; line-height: 19px; margin: auto;">
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;------------------------------------------------------------------</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;aws_postgresql_config.py</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">#</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Creates&nbsp;a&nbsp;.ini&nbsp;file&nbsp;containing&nbsp;encrypted&nbsp;credentials&nbsp;used</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;to&nbsp;connect&nbsp;to&nbsp;an&nbsp;AWS&nbsp;EC2&nbsp;hosted&nbsp;postgresql&nbsp;database.</span>
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
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">from</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">getpass</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #dcdcaa;">getpass</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">json</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">traceback</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">base64</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">import</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #4ec9b0;">boto3</span></div>
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
						style="color: #6a9955;">#&nbsp;Prompt&nbsp;for&nbsp;the&nbsp;connection&nbsp;params.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #dcdcaa;">print</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'Please&nbsp;provide&nbsp;connection&nbsp;parameters&nbsp;for&nbsp;database:'</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #d4d4d4;">+</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #ce9178;">'</span><span
						style="color: #d7ba7d;">\n</span><span style="color: #ce9178;">'</span><span
						style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_host</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">getpass</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'Please&nbsp;enter&nbsp;database&nbsp;host:'</span><span
						style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_name</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">getpass</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'Please&nbsp;enter&nbsp;database&nbsp;name:'</span><span
						style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_user</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">getpass</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'Please&nbsp;enter&nbsp;database&nbsp;user&nbsp;name:'</span><span
						style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">db_auth</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">getpass</span><span style="color: #cccccc;">(</span><span
						style="color: #ce9178;">'Please&nbsp;enter&nbsp;user&nbsp;password:'</span><span
						style="color: #cccccc;">)</span></div>
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
						style="color: #6a9955;">#&nbsp;Concat&nbsp;+&nbsp;delimit&nbsp;params,&nbsp;convert&nbsp;to&nbsp;bytes,&nbsp;then&nbsp;base64&nbsp;encode.</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">delim</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">chr</span><span style="color: #cccccc;">(</span><span
						style="color: #b5cea8;">7</span><span style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">params</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_host</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">delim</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_name</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">delim</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_user</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">delim</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">+</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">db_auth</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">byte_params</span><span style="color: #d4d4d4;">=</span><span
						style="color: #cccccc;">&nbsp;</span><span style="color: #9cdcfe;">params</span><span
						style="color: #cccccc;">.</span><span style="color: #dcdcaa;">encode</span><span
						style="color: #cccccc;">(</span><span style="color: #ce9178;">'utf-8'</span><span
						style="color: #cccccc;">)</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">encode_params</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #4ec9b0;">base64</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">b64encode</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">byte_params</span><span style="color: #cccccc;">)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Encrypt&nbsp;the&nbsp;params.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">encryption_data</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">aws_kms</span><span style="color: #cccccc;">.encrypt(</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">KeyId</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">the_key</span><span style="color: #cccccc;">,</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">Plaintext</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">encode_params</span><span style="color: #cccccc;">,</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">EncryptionAlgorithm</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #d4d4d4;">=</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #ce9178;">'SYMMETRIC_DEFAULT'</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Write&nbsp;the&nbsp;encryption&nbsp;params&nbsp;as&nbsp;binary&nbsp;to&nbsp;the&nbsp;config&nbsp;file.</span>
					</div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #c586c0;">with</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #dcdcaa;">open</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">config_file</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #ce9178;">'wb'</span><span style="color: #cccccc;">)&nbsp;</span><span
						style="color: #c586c0;">as</span><span style="color: #cccccc;">&nbsp;</span><span
						style="color: #9cdcfe;">file</span><span style="color: #cccccc;">:</span></div>
					<div><span
						style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #9cdcfe;">file</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">write</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">encryption_data</span><span style="color: #cccccc;">[</span><span
						style="color: #ce9178;">'CiphertextBlob'</span><span style="color: #cccccc;">])</span></div>
					<div><br></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #6a9955;">#&nbsp;Reset&nbsp;file&nbsp;perms.</span></div>
					<div><span style="color: #cccccc;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
						style="color: #4ec9b0;">os</span><span style="color: #cccccc;">.</span><span
						style="color: #dcdcaa;">chmod</span><span style="color: #cccccc;">(</span><span
						style="color: #9cdcfe;">config_file</span><span style="color: #cccccc;">,&nbsp;</span><span
						style="color: #b5cea8;">000</span><span style="color: #cccccc;">)</span></div>
					<div><br></div>
					</div>

					<h1 class="blog-subheading">Connecting to the postgresql database</h1>

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
					class="blog-snippet"
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

					<h1 class="blog-subheading">Key Takeaways</h1>

					<p>
					In this article we have connected to a postgresql database cluster hosted in AWS EC2
					without storing database credentials or encryption keys anywhere on the filesystem. Although
					the code presented could be refactored to eliminate redundancies or abstracted into a class, 
					the key points listed below could help in securely connecting to databases.
					</p>

					<ul>
					<li>Minimize visibility and access to database credentials</li>
					<li>If hosting in a cloud service provider, make use of symmetric keys to protect
						sensitive data such as user names or passwords
					</li>
					<li>Use encryption when storing database credentials on filesystem</li>
					<li>Try to avoid storing encryption keys or plain text user names/passwords</li>
					</ul>

				</section>
	
			</article>
		</div><!-- End blog_w -->
	</div><!-- End page_w -->
	</main>

    <?php include_once('site_footer.php'); ?>

</body>
</html>