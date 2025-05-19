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
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">	
	<link href="css/index.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script>
</head>

<body>

	<?php include_once('laheader.php'); ?>

	<main>
	<div id="page_w">
		<div id="posts_w">
			<section id="posts_l">
				<h1 class="page-section-heading">Featured</h1>
				
				<article class="featured-post site-box-shadow">
					<div class="featured-post-content">
						<a href="whydata.php"><img src="assets/whydata_195.png"></a>
						<a class="featured-post-hdrtxt" href="whydata.php">Why do we need Data?</a>
						<p class="featured-post-desctxt">
							Explores the importance of data and information in decision making.
						</p>
					</div>
				</article>


			</section>

			<section id="posts_r">

				<h1 class="page-section-heading">Latest Posts</h1>
				<div id="latest_posts_nav">
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
										 "<a href=\"{$post['post_name']}.php\" class=\"latest-post-link\">{$post['short_desc']}</a>" .
									"</li>";

								$iter += 1;
							}
						?>
					</ul>

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

	<?php include_once('lafooter.php'); ?>

</body>
</html>