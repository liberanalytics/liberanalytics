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
	<link href="/css/la_forms.css" rel="stylesheet">
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/postsearch.css" rel="stylesheet">
	<script type="module" src="/js/la.js"></script>
	<script type="module" src="/js/postsearch.js"></script>	
</head>

<body class="page page-postsearch site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
	<div class="page-wrap">
		<section class="site-ajax-overlay-relative">
			<h1 class="site-primary-heading site-font-primary-heading">Search for posts on Liber<em>Analytics</em></h1>


			<div id="postsearch_controls" class="site-form-container site-block-std site-gradient-border-bottom site-position-relative">							
				<form 
					id="postsearch"
					name="postsearch"
					method="post" data-composite="1"
					data-erroreleid="error_form"
					action="/api/postsearch.php"
					class="site-form-std site-font-form-std"
				>

					<ul>
						<li><label for="postsearch_category" class="site-fw-bold">Post Category</label></li>
						<li class="site-position-relative">
							<select id="postsearch_category" class="select-control"
							data-caption="Post Category" data-datakey="post_category" data-vx="vxText" data-len="512">
							<option class="control">Select Category</option>
								
								<?php
								$postCategories = $laData->crudData(
									CrudOperation::GETPOSTCATEGORIES,
									CrudType::CRUDREAD,
									CrudResultsetType::RESULTSET_ROWS,
									CrudFetchStyle::FETCHSTYLE_ASSOC,
									[]						
								);
								foreach($postCategories as $postCategoryIndex => $postCategoryArray)
								{
									echo "<option>{$postCategoryArray['post_category']}</option>";
								}
								?>
					
							</select>
							<span class="select-arrow"></span>
						</li>

						<li><label for="postsearch_tags" class="site-fw-bold">Tags</label></li>
						<li id="postsearch_tags_container">
							<input 
								id="postsearch_tags" name="postsearch_tags" type="text" title="Enter Last Name" class="" 
								maxlength="48" data-caption="Tags" data-id="last_name" data-req="1"
								data-vx="vxTect" data-len="48"
							>      
						</li>
						<li><input type="submit" value="SUBMIT" class="site-fc-xlight site-fw-bold"></li> 
						<li><span class="error-msg" aria-live="polite"></span></li>
					</ul>

				</form>					
				<div id="postsearch_filters">

				</div>
				<div class="site-ajax-spinner-8spoke hidden">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>				
			</div><!-- End #postsearch_controls .site-form-container. -->

			<div id="postsearch_results">
			</div>

			<div class="site-ajax-overlay-flex hidden">
			</div>

		</section>
	</div>


	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>