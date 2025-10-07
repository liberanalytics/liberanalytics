<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>About | LiberAnalytics</title>
	<meta name="description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<link rel="canonical" href="https://liberanalytics.com/about">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/svg+xml" href="/icon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="About | LiberAnalytics">
	<meta property="og:description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<meta property="og:url" content="https://liberanalytics.com/about">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="LiberAnalytics">
	<meta property="og:locale" content="en_US">

	<!-- Social media image. -->
	<meta property="og:image" content="https://liberanalytics.com/assets/social/about.jpg">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:image:alt" content="LiberAnalytics wordmark on a clean, minimalist background">

	<!-- Minor quality-of-life -->
	<meta name="theme-color" content="#ffffff"> <!-- matches your light theme -->
	<meta name="color-scheme" content="light dark"> <!-- let UA pick if you add dark later -->
	<meta name="referrer" content="no-referrer-when-downgrade">
	
	<!-- Stylesheets and javascript modules/files. -->
	<link href="css/la_tokens.css" rel="stylesheet">
	<link href="css/la_fonts.css" rel="stylesheet">
	<link href="css/la.css" rel="stylesheet">
	<link href="css/la_hdrftr.css" rel="stylesheet">
	<link href="css/la_tags.css" rel="stylesheet">
	<link href="css/la_ajax.css" rel="stylesheet">
	<link href="css/about.css" rel="stylesheet">
	<script type="module" src="js/la.js"></script>
</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap site-fs-med">
			<nav class="about-summaries" aria-label="Page summary">

				<section class="about-summary-card">
					<h2 class="site-primary-heading">About LiberAnalytics</h2>
					<article class="site-block-std">
						<p class="about-summary">
							What LiberAnalytics is, why it was built, the tech stack that runs it, 
							and the inspiration and goals the site upholds.
						</p>
						<h3><a href="#liber_about" class="site-font-link-std site-fs-med">Jump to section -></a></h3>
					</article>
				</section>

				<section class="about-summary-card">
					<h2 class="site-primary-heading">LiberAnalytics Philosophy</h2>
					<article class="site-block-std">
						<p class="about-summary">
							The ideologies LiberAnalytics is grounded in during both
							the site's development and composition of its blog posts.
						</p>
						<h3><a href="#liber_philosophy" class="site-font-link-std site-fs-med">Jump to section -></a></h3>
					</article>
				</section>

			</nav>

			<section tabindex="0">
			
				<h1 id="liber_about" class="site-primary-heading">About LiberAnalytics</h1>

				<div class="site-block-std site-stack-mtmb">
			
					<p>
						<span class="site-font-brand">LiberAnalytics</span>
						was built on a simple idea: that data systems and personal
						growth both thrive on clarity, precision, and intention.
					</p>
				
					<p>
						The platform is entirely custom-built — from database architecture
						and PHP classes to responsive front-end design — to demonstrate how modern,
						efficient, human-readable systems can be built without frameworks or bloat.
					</p>
				
					<p>
						LiberAnalytics was created by Nathan W. Rose, an AWS Certified Developer and 
						data architect who believes that the craft of analytics should be as thoughtful
						as the insights it produces. After years in corporate analytics, he built 
						LiberAnalytics as a personal lab for exploring the intersection of data engineering,
						cloud design, and creative independence.
					</p>
				
					<p>
						The site runs on AWS infrastructure with a PostgreSQL backend and a lightweight PHP
						data layer designed for transparency and security. The front end follows a semantic,
						token-based CSS design system inspired by 
						<a  
							href="https://www.kevinpowell.co"
							target="_blank"
							rel="noopener noreferrer"
							class="site-font-link-std site-fs-med"
						>
						Kevin Powell
						</a>’s
						component/utility class model.
					</p>
				
					<p>
						LiberAnalytics will continue evolving — with new posts, tools, and experiments in data analytics.
						The goal is simple: to educate and inspire data practitioners through practical,
						creative demonstrations of how to solve real-world problems with data.
					</p>
				
				</div>
			
			</section>
			
			<section id="liber_philosophy" tabindex="0">
			
				<h1 class="site-primary-heading">LiberAnalytics Philosophy</h1>

				<div class="site-block-std site-stack-mtmb">
			
				<p>
					LiberAnalytics exists at the intersection of Emerson’s transcendentalism, Jung’s
					individuation, and Nietzsche’s amor fati — three philosophies that together
					define both the project and the person behind it.
				</p>
				
				<p>
					From Emerson, LiberAnalytics draws the principle of self-reliance — the conviction
					that truth and purpose originate from within, not from institutions or convention.
					The process of building LiberAnalytics is itself an act of transcendence: a refusal
					to outsource meaning or creativity, a declaration that thought and craft must arise
					from direct experience.
				</p>
				
				<p>
					From Jung, it inherits the journey of individuation — the lifelong work of 
					integrating the fragments of the self into a coherent whole. Every refactor,
					every design revision, every abstraction of code mirrors that internal ordering
					— transforming chaos into structure, and instinct into form.
				</p>
				
				<p>
					And from Nietzsche, LiberAnalytics embodies amor fati — love of one’s fate.
					The acceptance that every challenge, delay, and difficulty is not a detour
					but the path itself. Rather than resist the constraints of life and time,
					LiberAnalytics absorbs them into its creative momentum.
				</p>
				
				<p>
					Together, these philosophies converge in LiberAnalytics as a single principle:
				</p>
				
				<p>
					The act of creation as self-realization — to build freely, think deeply,
					and become whole through one’s own work.
				</p>	

			</div>
			
			</section>			

		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>