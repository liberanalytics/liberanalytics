<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	include_once('../src/classes/LaData.php');
	//$laData = new LaData();
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

	</style>

</head>

<body>

	<?php include_once('../src/site_header.php'); ?>

	<main>
	<div id="page_w">
	<div id="simulate_ww">

		<section>

			<h1 class="page-section-heading">Simulate</h1>

			<div id="simulate_w">
			<div id="simulate">

			<form id="data_form">

				<label>First Name</label>
				<input type="text" name="first_name">

				<label>Last Name</label>
				<input type="text" name="last_name">

				<label>Email</label>
				<input type="email" name="email">

				<input type="submit" value="submit" id="data_submission">

			</form>

			<div id="simulate_data">

				<?php

					foreach($_SERVER as $key => $val)
					{
						echo "<h1>{$key} =. {$val}";
					}

				?>

			</div>
				
			</div>
			</div>

		</section>

	</div>
	</div><!-- End page_w -->
	</main>

	<?php include_once('../src/site_footer.php'); ?>

</body>
</html>