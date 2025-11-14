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

		}

		.post-snippet span {
			margin: 0;
			white-space: pre;
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

				<div class="post-snippet">

					<p><span class="code-comment"># ------------------------------------------------------------------</span></p>
					<p><span class="code-comment"># aws_postgresql_connect.py</span></p>
					<p><span class="code-comment">#</span></p>
					<p><span class="code-comment"># Creates a .ini file containing encrypted credentials used
					# to connect to an AWS EC2 hosted postgresql database.</span></p>
					<p><span class="code-comment"># ------------------------------------------------------------------</span></p>

					<!-- imports -->
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



			</section>
		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>
