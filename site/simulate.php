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

.code-block {
	margin-block: var(--sz-400);
	background-color: var(--clr-code-bg, #1f1f1f);
	color: var(--clr-code-fg, #cccccc);
	border-radius: var(--sz-150);
	box-shadow: var(--site-box-shadow-soft);
	overflow: hidden;
}

.post-code {
    margin-block: var(--sz-400);
    padding: var(--sz-300);
    background-color: #111218; /* or var(--clr-code-bg) */
    color: #e6e6e6;            /* or var(--clr-code-fg) */
    border-radius: var(--sz-150);
    box-shadow: var(--site-box-shadow-soft);
  
    /* important bits for mobile */
    overflow-x: scroll;    /* allow horizontal scroll if line is wide */
    overflow-y: scroll; /* grow as tall as needed */
    max-width: 100%;
  }
  
  .post-code code {
    display: block;
    white-space: pre;    /* preserve indentation & line breaks */
    tab-size: 4;
    /* font-family: var(--font-mono);  <-- you can add later */
  }

	</style>

</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<section>
				<h1 class="site-primary-heading">Contact Us</h1>

				<pre class="post-code"><code>
# ------------------------------------------------------------------
# aws_postgresql_config.py
#
# Creates a .ini file containing encrypted credentials used
# to connect to an AWS EC2 hosted postgresql database.
# ------------------------------------------------------------------

import pwd
import sys
import os
from getpass import getpass
import json
import traceback
import base64
import boto3

def main():
	# Instantiate an AWS KMS client instance.
	aws_kms = boto3.client('kms', 'region')
	...</code></pre>						
			</section>
		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>
