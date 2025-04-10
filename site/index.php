<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	include_once('../src/classes/LaData.php');
	$laData = new LaData();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LiberAnalytics</title>
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/index.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script>
</head>

<body>

	<?php include_once('../src/site_header.php'); ?>

	<main>
	<div id="page_w">
		<div id="posts_w">
			<section id="posts_l">
				<h1 class="page-section-heading">Featured</h1>
				<?php

					/**
					 * 
					 */
					$laData->openConn();
					$featuredPosts = $laData->getPosts
					(
						$pFeatured = TRUE, 
						$pPostName = NULL ,           
						$pCategory = NULL, 
						$pOrderBy = ['featured' => 'ASC'],
						$pLimit = NULL
					);
					$iter = 1;
					while ($post = $featuredPosts->fetch(PDO::FETCH_ASSOC))
					{                     
						if ($iter == 1) {
							$htmlFormatting = "";
						} else {
							$htmlFormatting = "\n\t\t\t\t";
						}

						echo $htmlFormatting .'<article class="featured-post clear">'
						. "\n\t\t\t\t\t" . '<div class="featured-post-content clear">'                       
						. "\n\t\t\t\t\t\t" . '<div class="featured-post-img">'
						. "\n\t\t\t\t\t\t\t" . "<a href=\"{$post['post_name']}.php\">"
						. "<img src=\"assets/{$post['featured_img']}\"></a>"
						. "\n\t\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t\t\t" . '<div class="featured-post-desc">'
						. "\n\t\t\t\t\t\t\t" . "<a class=\"featured-post-hdrtxt\" href=\"{$post['post_name']}.php\">"
						. "{$post['short_desc']}</a>"
						. "\n\t\t\t\t\t\t\t" . "<p class=\"featured-post-desctxt\">"
						. "\n\t\t\t\t\t\t\t\t" . "{$post['long_desc']}"
						. "\n\t\t\t\t\t\t\t" . "</p>"
						. "\n\t\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t\t" . '<div class="featured-post-attrs clear">'
						. "\n\t\t\t\t\t\t" . "<span class=\"featured-post-date\">"
						. date("F j, Y", strtotime($post['post_created'])) . "</span>"
						. "\n\t\t\t\t\t\t" . "<a href=\"blog?category={$post['post_category']}\" class=\"featured-post-cat\">"
						. "{$post['post_category']}</a>"
						. "\n\t\t\t\t\t" . '</div>'
						. "\n\t\t\t\t" . '</article>';

						$iter += 1;
					}
				
				?>

			</section>

			<section id="posts_r">

				<h1 class="page-section-heading">Latest Posts</h1>
				<div id="latest_posts_nav">
				<?php

					$latestPosts = $laData->getPosts
					(
						$pFeatured = TRUE,                  // ^ to false once more posts have been +'d.
						$pPostName = NULL ,           
						$pCategory = NULL, 
						$pOrderBy = ['featured' => 'DESC'], // ^ to post_created once more posts have been +'d.
						$pLimit = NULL
					);
					$iter = 1;
					while ($post = $latestPosts->fetch(PDO::FETCH_ASSOC))
					{
						if ($iter == 1) {
							$htmlFormatting = "\t";
						}
						else {
							$htmlFormatting = "\n\t\t\t\t\t"; 
						}

						echo $htmlFormatting . "<a href=\"{$post['post_name']}.php\" class=\"site-href\">{$post['short_desc']}</a>";

						$iter += 1;
					}
					$laData->closeConn();
				?>

				</div><!-- End latest_posts_nav -->      

			</section>

		</div><!-- End posts_w -->

		<section id="mission_w">

			<h1 class="page-section-heading">Our Mission</h1>

			<div id="mission" class="site-block-std">

				<p class="site-font-emph">
					<em class="site-font-fancyl">LiberAnalytics</em>' goal is to show that having a creative mindset while working with data can be fascinating and enhance an
					individual's ability to solve problems. Most solutions to challenges in data are by their nature non-deterministic and
					are resolvable in many different ways. Hence, we aspire to consider multiple possible data-driven techniques when approaching problems in the domain of data analytics.
					We hope that the content published will illuminate how to exercise a <em class="site-font-fancyl">liber</em> (free)
					mind while working with data as well as inspire individuals to keep improving!
				</p>

				<div id="mission_graphic">
					<img src="assets/mission.png">
				</div>                    

			</div><!-- End mission -->

		</section>

	</div><!-- End page_w -->
	</main>

	<?php include_once('../src/site_footer.php'); ?>

</body>
</html>