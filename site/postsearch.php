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
	<link href="css/posts.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script>
	<script type="text/javascript" src="js/LAvx.js"></script>
	<script type="text/javascript" src="js/posts.js"></script>
</head>

<body>

	<?php include_once('site_header.php'); ?>

	<main>
	<div id="page_w">
		<h1 class="page-section-heading">Search for posts on Liber<em>Analytics</em></h1>
		<div id="postsearch_w">
			<div id="postsearch_form">
				<form id="postsearch" method="post" data-composite="1" data-erroreleid="error_form" action="posts_handler.php">
					<ul>
						<li>
							<label class="label-first">Post Category</label>
							<div class="select-parent">
							<select class="select-control"
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
							</div>
						</li>
						<li>
							<label class="label-nonfirst">Tags</label>
							<input type="text" class="input-control" maxlength="48"
							 data-caption="Tags" data-datakey="post_tags" data-vx="vxText" data-len="48">
						</li>
						<li>
							<label class="label-nonfirst">Keywords</label>
							<input type="text" class="input-control" maxlength="48"
							data-caption="Keywords" data-datakey="post_keywords" data-vx="vxText" data-len="48">
						</li>
						<li>
							<input type="submit" value="SUBMIT">
						</li>
						<li>
							<span id="error_form" class="error-msg"></span>
						</li>
					</ul>

				</form>
			</div><!-- End postsearch_form -->
			
		</div><!-- End postsearch_w -->

		<div id="postsearch_results_w">

			<div id="postsearch_results">

			</div><!-- End postsearch_results -->

		</div><!-- End postsearch_results_w -->


	</div><!-- End page_w -->
	</main>

	<?php include_once('site_footer.php'); ?>

</body>
</html>