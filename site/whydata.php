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
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" type="text/css" charset="utf-8"> 
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">	
	<link href="css/la_post.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script>
</head>

<body>

	<?php include_once('laheader.php'); ?>

	<main>
	<div id="page_w">
		<div id="blog_w">
			<article>
				<header>
					<?php
						$postName = basename($_SERVER['PHP_SELF'], ".php");
						$post = $laData->crudData(
							CrudOperation::GETPOSTS,
							CrudType::CRUDREAD,
							CrudResultsetType::RESULTSET_ROWS,
							CrudFetchStyle::FETCHSTYLE_ASSOC,
							[ 'post_name' => $postName]						
						)[0];
					?>

					<h1 class="blog-heading site-font-fancyl"><?php echo $post['short_desc']; ?></h1>
					<h3 class="blog-date"><?php echo date("F j, Y", strtotime($post['post_created'])); ?></h3>
					<h2 class="blog-desc site-font-emph"><?php echo $post['long_desc']; ?></h2>
					<img <?php echo "src=\"assets/{$post['post_img']}\""; ?> class="blog-img">
				</header>        

				<section>

					<p> 
						<em class="site-font-fancyl">Why</em> do we go through extensive efforts to collect, transform, load, store, and aalyze volumes of data?
						Startups and corporations employ teams of professionals who often specialize in niche functions within
						data analytics such as data architect, data analyst, or data scientist in order to provide maximum value
						of their respective function to the enterprise. There must exist a valid and profit driven reason to place
						such a premium on the function and impact of data. The reason is data provides answers in the form of information
						that is paramount to the resolution of real-world problems while contributing to the sustainability and growth
						of organizations.
					</p>

					<h1 class="blog-subheading">Data Integrity</h1>

					<p>
						High integrity data is a purely objective form of facts not influenced by human subjectivity. 
						What we mean by integrity in the context of data is that the predicate of a given piece of data can be relied on to be
						true which entails accuracy, exclusivity or non-duplication, and consistency. Accuracy gives reliability to the literal
						qualitative or quantitative value. Exclusivity indicates the values are not represented anywhere else in the working dataset.
						And consistency means that the values of each individual datum in the working dataset exist within the realm of possible phenomena.            
					</p>

					<h1 class="blog-subheading">Necessity of data in decision making</h1>

					<p>
						Whether making journal entries to a general ledger, recording a hospital's daily admissions and discharges, or predicting the extent of
						raw materials needed for a factory's anticipated production, data is central to facilitating correct decisions. Driving decisions
						according to conclusions drawn from data instills confidence of correctness - data are facts and decisions based on facts
						are likely to produce correct results. Data's role in providing answers to questions in a decision making process may be illustrated
						like the below.
					</p>

					<img src="assets/whydata_ps.png" style="display: block; margin: 0 auto 0 auto;">

					<h1 class="blog-subheading">Data versus information</h1>

					<p>
						The words data and information are often used interchangeably. However, in the context of data analytics, data and information become terms
						denoting different things and should be treated with care by the practitioner. Data means raw objective facts such as rows stored in tables
						in a database or data warehouse and results in information usable by stakeholders to make insightful decisions. Information applies context
						and meaning to data ultimately leading to the fruits of data's labor. Summary statistics, graphs, charts, financial statements, and reports
						in general are examples of information that humans use to make decisions.

					<h1 class="blog-subheading">Summary</h1>

					<p>
						Using quality data to derive insights about organizations is imperative in an operational decision making process. Without data the
						ability to confidently stand on impactul decisions would be compromised and  would inevitably lead to less than desirable outcomes.
						With the advent of big data and AI data that is effectively analysed and translated to meaningful business information data has never
						been more critical.
					</p>

				</section>
		
			</article>
		</div><!-- End blog_w -->
	</div><!-- End page_w -->
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
