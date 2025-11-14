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

		.post-code {
			margin-block: var(--sz-400);
			padding: var(--sz-300);
			background-color: #111218; /* or var(--clr-code-bg) */
			color: #e6e6e6;            /* or var(--clr-code-fg) */
			border-radius: var(--sz-150);
			box-shadow: var(--site-box-shadow-soft);
		
			/* important bits for mobile */
			max-width: 100%;
			max-height: 18rem;
			overflow: auto;
			-webkit-overflow-scrolling: touch;
			scrollbar-gutter: stable;
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

# KMS key id.
the_key = 'keyid'

# Prompt for the connection params.
print('Please provide connection parameters for database:\n')
db_host = getpass('Please enter database host:')
db_name = getpass('Please enter database name:')
db_user = getpass('Please enter database user name:')
db_auth = getpass('Please enter user password:')

# Config file.
config_dir = ['HOME'] + '/' + 'conn' + '/' + db_host
config_file = config_dir + '/' + db_name + '.ini'

# Concat + delimit params, convert to bytes, then base64 encode.
delim = chr(7)
params = db_host + delim + db_name + delim + db_user + delim + db_auth
byte_params = params.encode('utf-8')
encode_params = base64.b64encode(byte_params)

# Encrypt the params.
encryption_data = aws_kms.encrypt(
	KeyId = the_key,
	Plaintext = encode_params,
	EncryptionAlgorithm = 'SYMMETRIC_DEFAULT'
)

# Write the encryption params as binary to the config file.
with open(config_file, 'wb') as file:
	file.write(encryption_data['CiphertextBlob'])

# Reset file perms.
os.chmod(config_file, 000)

</code></pre>
	
	
			</section>
		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>
