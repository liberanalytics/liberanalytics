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
	<title>Search Blog Posts | LiberAnalytics</title>
	<meta name="description" content="Search LiberAnalytics posts by topic, tool, or keyword and find practical guides on PostgreSQL, AWS, and real-world analytics workflows.">
	<link rel="canonical" href="https://liberanalytics.com/postsearch">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/icon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Search Blog Posts | LiberAnalytics" />
	<meta property="og:description" content="Quickly search LiberAnalytics posts to find hands-on articles about PostgreSQL, AWS infrastructure, and practical data analytics patterns." />
	<meta property="og:url" content="https://liberanalytics.com/postsearch">
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
	<link href="/css/la_tokens.css" rel="stylesheet">
	<link href="/css/la_fonts.css" rel="stylesheet">
	<link href="/css/la.css" rel="stylesheet">
	<link href="/css/la_hdrftr.css" rel="stylesheet">
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/postsearch.css" rel="stylesheet">
	<script type="module" src="/js/la.js"></script>
	<script type="module" src="/js/postsearch.js"></script>	
</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
	<div class="page-wrap">
		<h1 class="site-primary-heading">Search for posts on Liber<em>Analytics</em></h1>
		<div id="postsearch_w" class="site-ajax-overlay-relative">
			

		

	</div><!-- End postsearch_w -->

	</div><!-- End page_w -->
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>