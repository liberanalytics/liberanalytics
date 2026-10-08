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
$laViewConverter = new classes\LaViewConverter();

//
$postName = basename($_SERVER['PHP_SELF'], ".php");
$post = $laData->crudData(
	CrudOperation::GETPOSTS,
	CrudType::CRUDREAD,
	CrudResultsetType::RESULTSET_ROWS,
	CrudFetchStyle::FETCHSTYLE_ASSOC,
	['post_name' => $postName]						
)[0];

$postTitle = $laViewConverter->escAttr($post['short_desc']);
$postMetaDesc = $laViewConverter->escAttr($post['long_desc']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Semanticsearch | LiberAnalytics</title>
	<meta name="description" content="<?php echo $postMetaDesc; ?>">
	<link rel="canonical" href="https://liberanalytics.com/Semanticsearch">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Semanticsearch | LiberAnalytics">
	<meta property="og:description" content="<?php echo $postMetaDesc; ?>">
	<meta property="og:url" content="https://liberanalytics.com/Semanticsearch">
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
	<link href="/css/post.css" rel="stylesheet">
	<script type="module" src="/js/tagsearch.js"></script>
	<script type="module" src="/js/semanticsearch.js"></script>
</head>

<body class="page page-post site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<article>
				<header class="site-stack-start-mt">
							
					<?php						
						//
						echo $laViewConverter->getPostHeader($post);
					?>

				</header>

				<section class="site-stack-start-mt">

					<p class="site-mt site-font-std"> 
						<span class="site-font-brand">Enterprise</span> AI adoption is increasingly constrained less
                        by access to models than by an organization's ability to make its business semantics, data,
                        workflows, and system interfaces trustworthy enough for models to operate against. The
                        architectural challenge is to reconcile meaning across heterogeneous systems, encode
	                    accepted meanings as reusable metadata, contracts, and rules, enforce deterministic behavior
                        at system boundaries where practical, and confine probabilistic AI to explicitly bounded
                        interpretation and reasoning tasks.
					</p>

                    <p class="site-font-std">
                    	LiberAnalytics currently requires users to express post searches through predefined categories,
                        tags, and other database parameters. Humans, however, do not naturally communicate in database
                        parameters. This article explores whether an LLM can serve as a constrained semantic layer,
                        translating natural-language search intent into validated parameters that can be safely consumed
                        by LiberAnalytics' existing deterministic database orchestration.
                    </p>

				</section>
		
			</article>

			<div class="site-ajax-spinner-8spoke site-ajax-hidden">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div class="site-ajax-overlay-flex site-ajax-hidden">
			</div>

		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
