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
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_forms.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script>
	<script type="text/javascript" src="js/simulate.js"></script>

	<style>

		.featured-post {
			padding: 10px 5px 10px 5px;
			border: 1px solid rgba(248,248,248, 1);
			margin: 30px 0 30px 0;   
			border-radius: 3px;
			box-shadow: 0 4px 8px 0 rgb(0 0 0 / 30%);
			background-color: #fff;
		}

		.featured-post-hdrtxt:link, .featured-post-hdrtxt:visited {
			display: block;
			padding: 0 0 5px 0;
			border-bottom-width: 2px;
			border-bottom-style: solid;
			border-image: linear-gradient( to left, rgba(196,196,196, 1) , rgba(248,248,248, 1) ) 1;    
			font-family: 'Times New Roman', Times, serif;
			font-size: 22px;
			font-weight: 300;
			color: rgba(51,51,51,1);
			text-decoration: none; 
		}

		.featured-post-hdrtxt:hover {
			color: rgba(101,149,202,1);
			text-decoration: underline;    
			transition-duration: .3s;  
			cursor: pointer;
		}		

		.featured-post-desc {
			float: left;
			width: 380px;
			margin: 0 0 0 10px;
		}

		.featured-post-desctxt {
			padding: 5px 0 0 0;
			font-family: Studio-Feixen-Sans;
			font-size: 18px;
			line-height: 1.5;
			color: rgb(78,79,95);
			
		}


	</style>

</head>

<body>

	<?php include_once('site_header.php'); ?>

	<main>
	<div id="page_w">
	<div id="simulate_ww">	
				


	</div><!-- End simulate_ww -->
	</div><!-- End page_w -->
	</main>

	<?php include_once('site_footer.php'); ?>

</body>
</html>