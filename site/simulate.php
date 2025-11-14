<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LiberAnalytics | Contact Us</title>
	<link href="css/la_tokens.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_fonts.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_forms.css" rel="stylesheet" type="text/css" charset="utf-8">			
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_tags.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_ajax.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="js/la.js"></script>

	<style>

		/* Wrapper for the snippet */
		.post-snippet {
			margin-block: var(--sz-400);
			padding: var(--sz-300);
			background-color: #1f1f1f;
			color: #cccccc;
			border-radius: var(--sz-150);
			box-shadow: var(--site-box-shadow-soft);
			font-family: "Droid Sans Mono", monospace;
			font-weight: normal;
			line-height: 1.2; /* 19px-ish if your base is 16px */
			overflow: auto;   /* let it scroll if needed */
		}

		/* Make inner text preserve spacing/line breaks */
		.post-snippet p {
			margin: 0;
			white-space: nowrap;
		}

		.post-snippet span {
			margin: 0;
			/* white-space: pre; */
		}

		/* Token colors (matching your inline styles) */
		.post-snippet .code-default  { color: #cccccc; } /* generic text */
		.post-snippet .code-comment  { color: #6a9955; }
		.post-snippet .code-keyword  { color: #c586c0; } /* import, def, with, for, in, etc. */
		.post-snippet .code-type     { color: #4ec9b0; } /* modules, types, base64, boto3, etc */
		.code-func     { color: #dcdcaa; } /* function names, builtins */
		.code-var      { color: #9cdcfe; } /* variable names */
		.code-op       { color: #d4d4d4; } /* =, +, etc */
		.code-string   { color: #ce9178; }
		.code-number   { color: #b5cea8; }
		.code-special  { color: #569cd6; } /* def, f-strings bits, etc */

	</style>

</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<section>
				<h1 class="site-primary-heading site-f">Contact Us</h1>

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
			</section>
		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>
