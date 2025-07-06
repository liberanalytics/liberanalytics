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
	<link href="css/la_forms.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="js/la.js"></script> 	  
	<script type="module" src="js/contactsubmit.js"></script>
</head>

<body>

	<?php include_once('laheader.php'); ?>

	<main>
	<div id="page_w">
		<div id="contact_w">
			<section>
				<h1 class="page-section-heading">Thank you</h1>
				<div id="contact_submit_w" class="site-block-std">

					<div id ="contact_submit">

					<p class="site-font-emph" style="line-height: 1.5;">
						Thank you for contacting LiberAnalytics, 
						<span id="contact_name" class="site-font-fancyl"></span>!
						We are glad to hear from you. We'll get back to you shortly if a response is needed.
						In the meantime, feel free to explore our latest 
						<a href="postsearch.php" style="font-size: 1.25rem;">insights</a>.
					</p>

					</div> <!-- End contact_submit -->
					
				</div> <!-- End contact_submit_w -->

			</section>

		</div><!-- End contact_w -->
	</div><!-- End page_w -->
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>