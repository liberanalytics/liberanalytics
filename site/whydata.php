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
	[ 'post_name' => $postName]						
)[0];

$postMetaDesc = $laViewConverter->escAttr($post['long_desc']);
$postTitle = $laViewConverter->escAttr($post['short_desc']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Whydata | LiberAnalytics</title>
	<meta name="description" content="<?php echo $postMetaDesc; ?>">
	<link rel="canonical" href="https://liberanalytics.com/whydata">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Whydata | LiberAnalytics">
	<meta property="og:description" content="<?php echo $postMetaDesc; ?>">
	<meta property="og:url" content="https://liberanalytics.com/whydata">
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
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/post.css" rel="stylesheet">
	<script type="module" src="/js/la.js"></script>
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
						<span class="site-font-brand">Why</span> do we go through extensive efforts to collect, transform, load, store, and aalyze volumes of data?
						Startups and corporations employ teams of professionals who often specialize in niche functions within
						data analytics such as data architect, data analyst, or data scientist in order to provide maximum value
						of their respective function to the enterprise. There must exist a valid and profit driven reason to place
						such a premium on the function and impact of data. The reason is data provides answers in the form of information
						that is paramount to the resolution of real-world problems while contributing to the sustainability and growth
						of organizations.
					</p>

					<h4 class="site-font-secondary-heading">Data Integrity</h4>

					<p class="site-font-std">
						High integrity data is a purely objective form of facts not influenced by human subjectivity. 
						What we mean by integrity in the context of data is that the predicate of a given piece of data can be relied on to be
						true which entails accuracy, exclusivity or non-duplication, and consistency. Accuracy gives reliability to the literal
						qualitative or quantitative value. Exclusivity indicates the values are not represented anywhere else in the working dataset.
						And consistency means that the values of each individual datum in the working dataset exist within the realm of possible phenomena.            
					</p>

					<h4 class="site-font-secondary-heading">Necessity of data in decision making</h4>

					<p class="site-font-std">
						Whether making journal entries to a general ledger, recording a hospital's daily admissions and discharges, or predicting the extent of
						raw materials needed for a factory's anticipated production, data is central to facilitating correct decisions. Driving decisions
						according to conclusions drawn from data instills confidence of correctness - data are facts and decisions based on facts
						are likely to produce correct results. Data's role in providing answers to questions in a decision making process may be illustrated
						like the below.
					</p>

					<img 
						src="/assets/whydata_ps.png" 
						alt="Screenshot of basic information into data flow." 
						class="post-img"
					>

					<h4 class="site-font-secondary-heading">Data versus information</h4>

					<p class="site-font-std">
						The words data and information are often used interchangeably. However, in the context of data analytics, data and information become terms
						denoting different things and should be treated with care by the practitioner. Data means raw objective facts such as rows stored in tables
						in a database or data warehouse and results in information usable by stakeholders to make insightful decisions. Information applies context
						and meaning to data ultimately leading to the fruits of data's labor. Summary statistics, graphs, charts, financial statements, and reports
						in general are examples of information that humans use to make decisions.

					<h4 class="site-font-secondary-heading">Summary</h4>

					<p class="site-font-std">
						Using quality data to derive insights about organizations is imperative in an operational decision making process. Without data the
						ability to confidently stand on impactul decisions would be compromised and  would inevitably lead to less than desirable outcomes.
						With the advent of big data and AI data that is effectively analysed and translated to meaningful business information data has never
						been more critical.
					</p>

				</section>
		
			</article>
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
