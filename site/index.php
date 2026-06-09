<?php

use classes\LaHTML;
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once(dirname(__DIR__, 1) . "/" . 'vendor/autoload.php');

use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;	

$laData = new classes\LaData();
// $laHtml = new classes\LaHTML();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home | LiberAnalytics</title>
	<meta name="description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<link rel="canonical" href="https://liberanalytics.com/">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/icon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Home | LiberAnalytics">
	<meta property="og:description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<meta property="og:url" content="https://liberanalytics.com/">
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

	<!-- Font styles needed before the fold. -->
	<link rel="preload" href="/assets/ls.woff2"  as="font" type="font/woff2" crossorigin="anonymous">
	<link rel="preload" href="/assets/sfs.woff2"  as="font" type="font/woff2" crossorigin="anonymous">
	
	<!-- Stylesheets and javascript modules/files. -->
	<link href="/css/la_tokens.css" rel="stylesheet">
	<link href="/css/la_fonts.css" rel="stylesheet">
	<link href="/css/la.css" rel="stylesheet">
	<link href="/css/la_hdrftr.css" rel="stylesheet">
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/postcard.css" rel="stylesheet">
	<link href="/css/index.css" rel="stylesheet">
	<script type="module" src="/js/la.js"></script>

</head>

<body class="page page-index site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<section class="featured-posts-container">	
				<h1 class="site-primary-heading site-font-primary-heading">Featured</h1>
				
				<?php

				/**
				 * 
				 */
				$featuredPosts = $laData->crudData(
					CrudOperation::GETPOSTS,
					CrudType::CRUDREAD,
					CrudResultsetType::RESULTSET_ROWS,
					CrudFetchStyle::FETCHSTYLE_ASSOC,
					[
						'order_by' => 'featured',
						'order_by_ascdesc' => 'ASC',
						'limit' => 5
					]						
				);
				$iter = 1;
				foreach ($featuredPosts as $postnum => $post)
				{
					// Post image file name, slot #, image #, image width.
					$postImgSpecs = [
						$post['post_name'] => [
							// Post Image Slot #.
							1 => [
								//Post image number => image specs for given post by slot and image number.
								1 => [
									'postImgFileName' => $post['post_img'] . '_' . (string)$post['img_01_width'] . '.png',
									'postImgWidthDescriptor' => (string)$post['img_01_width'] . 'w'
								],
								2 => [
									'postImgFileName' => $post['post_img'] . '_' . (string)$post['img_02_width'] . '.png',
									'postImgWidthDescriptor' => (string)$post['img_02_width'] . 'w'
								],
								3 => [
									'postImgFileName' => $post['post_img'] . '_' . (string)$post['img_03_width'] . '.png',
									'postImgWidthDescriptor' => (string)$post['img_03_width'] . 'w'
								],
								4 => [
									'postImgFileName' => $post['post_img'] . '_' . (string)$post['img_04_width'] . '.  png',
									'postImgWidthDescriptor' => (string)$post['img_04_width'] . 'w'
								]
							]
						]
					];
				
					echo 
					'<article class="post-card site-box-shadow">'
					. '<h2 class="post-card-desc site-font-secondary-heading site-gradient-rtl-border-bottom">' . $post['short_desc'] . '</h2>'
					. '<div class="post-card-components site-gradient-ltr-border-bottom">'
					. '<a class="post-card-img" href="/' . rawurlencode($post['post_name']) . '">'
					. '<img src="/assets/' . $post['post_img'] . '">'
					. '<a class="post-card-longdesc site-font-link-std" href="/' . rawurlencode($post['post_name']) . '">' . $post['long_desc'] . '</a>'						
					. '</div>'
					. '<div class="post-card-attrs">'
					. '<time class="site-font-date-std" datetime="' . date("F j, Y", strtotime($post['post_created']))
					.  '">' . date("F j, Y", strtotime($post['post_created'])) . '</time>'
					. '<a class="post-card-cat site-font-link-std" href="/postsearch?post_category=' . rawurlencode($post['post_category']) . '">' . $post['post_category'] . '</a>'
					. '</div>'
					. '</article>';

					$iter += 1;
				}
			
			?>

			</section>

			<section class="latest-posts-container">
				<h1 class="site-primary-heading site-font-primary-heading">Latest Posts</h1>
				<ul>
					<?php

						$latestPosts = $laData->crudData(
							CrudOperation::GETPOSTS,
							CrudType::CRUDREAD,
							CrudResultsetType::RESULTSET_ROWS,
							CrudFetchStyle::FETCHSTYLE_ASSOC,
							[
								'order_by' => 'post_created',
								'order_by_ascdesc' => 'DESC',
								'limit' => 10
							]						
						);
						$iter = 1;
						foreach ($latestPosts as $postNum => $post)
						{
							echo 
							'<li class="site-gradient-border-bottom">'
							. '<a href="/' . $post['post_name'] . '" class="latest-posts-link site-font-link-std">' . $post['short_desc'] . '</a>'
							. '</li>';

							$iter += 1;
						}
					?>
				</ul> 
			</section>

			<section>
				<h2 class="site-primary-heading site-font-primary-heading">Our Mission</h2>
				<div class="mission-container site-block-std">
					<p class="mission-stmt site-font-std">
						<span class="site-font-brand">LiberAnalytics</span>' goal is to show that having a creative mindset while working with data can be fascinating and enhance an
						individual's ability to solve problems. Most solutions to challenges in data are by their nature non-deterministic and
						are resolvable in many different ways. Hence, we aspire to consider multiple possible data-driven techniques when approaching problems in the domain of data analytics.
						We hope that the content published will illuminate how to exercise a <em class="site-font-brand">liber</em> (free)
						mind while working with data as well as inspire individuals to keep improving!
					</p>
					<img
						src="/assets/mission_180x180.png"
						srcset="/assets/mission_180x180.png 1x,
								/assets/mission_360x360.png 2x,
								/assets/mission_540x540.png 3x",
						alt="Liber Analytics Mission"
						class="mission-img"
						decoding="async"
						loading="lazy"
						width="180"
						height="180"						
					>
				</div>
			</section>			
		</div><!-- End .page-wrap -->
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>