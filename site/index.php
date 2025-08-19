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
	<title>LiberAnalytics | Home</title>
	<link href="css/la_tokens.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_fonts.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_tags.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_ajax.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/index.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="js/la.js"></script>

</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<div class="site-flex site-flex-wrap site-gap-800">
				<section class="featured-posts-container | site-flex-grow-shrink">	
					<h1 class="site-primary-heading">Featured</h1>
					
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
							'order_by_ascdesc' => 'ASC'
						]						
					);
					$iter = 1;
					foreach ($featuredPosts as $postnum => $post)
					{                     
						if ($iter == 1) {
							$htmlFormatting = "";
						} else {
							$htmlFormatting = "\n\t\t\t\t";
						}

						echo $htmlFormatting .'<article class="featured-post site-box-shadow">'
						. "\n\t\t\t\t\t" . '<div class="featured-post-content">'                       
						. "\n\t\t\t\t\t\t" . '<div class="featured-post-img">'
						. "\n\t\t\t\t\t\t\t" . "<a href=\"{$post['post_name']}\">"
						. "<img src=\"assets/{$post['featured_img']}\"></a>"
						. "\n\t\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t\t\t" . '<div class="featured-post-desc-container">'
						. "\n\t\t\t\t\t\t\t" . "<a class=\"featured-post-name site-link-std site-link-fc-med\" href=\"{$post['post_name']}\">"
						. "{$post['short_desc']}</a>"
						. "\n\t\t\t\t\t\t\t" . "<p class=\"featured-post-desc site-link-std site-link-fc-med site-link-flh-med\">"
						. "\n\t\t\t\t\t\t\t\t" . "{$post['long_desc']}"
						. "\n\t\t\t\t\t\t\t" . "</p>"
						. "\n\t\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t\t" . '<div class="featured-post-attrs">'
						. "\n\t\t\t\t\t\t" . "<span class=\"featured-post-date\">"
						. date("F j, Y", strtotime($post['post_created'])) . "</span>"
						. "\n\t\t\t\t\t\t"
						. "<a href=\"postsearch?post_category="
						. rawurlencode($post['post_category']) 
						. "\" class=\"featured-post-cat site-link-std\">"
						. "{$post['post_category']}</a>"
						. "\n\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t" . '</article>';

						$iter += 1;
					}
				
				?>		

				</section>

				<section class="latest-posts-container | site-flex-grow-shrink">

					<h1 class="site-primary-heading">Latest Posts</h1>
					<div class="latest-posts">
						<ul>
							<?php
	
								$latestPosts = $laData->crudData(
									CrudOperation::GETPOSTS,
									CrudType::CRUDREAD,
									CrudResultsetType::RESULTSET_ROWS,
									CrudFetchStyle::FETCHSTYLE_ASSOC,
									[
										'order_by' => 'post_category',
										'order_by_ascdesc' => 'DESC'
									]						
								);
								$iter = 1;
								foreach ($latestPosts as $postNum => $post)
								{
									if ($iter == 1) {
										$htmlFormatting = "\t";
									}
									else {
										$htmlFormatting = "\n\t\t\t\t\t"; 
									}
	
									echo 
										"<li>" .
											 $htmlFormatting . 
											 "<a href=\"{$post['post_name']}\" class=\"latest-post-link site-link-std site-link-fc-xdark\">{$post['short_desc']}</a>" .
										"</li>";
	
									$iter += 1;
								}
							?>
						</ul>
	
					</div>  
				</section>
			</div>

			<section class="site-mt-std">
				<h1 class="site-primary-heading">Our Mission</h1>

				<div class="site-block-std site-flex site-gap-800">

					<p class="site-flex-grow-shrink site-fs-med">
						<span class="site-font-brand">LiberAnalytics</span>' goal is to show that having a creative mindset while working with data can be fascinating and enhance an
						individual's ability to solve problems. Most solutions to challenges in data are by their nature non-deterministic and
						are resolvable in many different ways. Hence, we aspire to consider multiple possible data-driven techniques when approaching problems in the domain of data analytics.
						We hope that the content published will illuminate how to exercise a <em class="site-font-brand">liber</em> (free)
						mind while working with data as well as inspire individuals to keep improving!
					</p>

					<div class="site-flex-grow-shrink">
						<img src="assets/mission.png">
					</div>                    

				</div>
			</section>
		
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>