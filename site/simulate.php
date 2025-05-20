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
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script>

	<style>

		:root {
			font-size: 16px;
		}

		#article_w {
			display: grid;
			grid-template-areas: 
			'one one five'
			'four three five'
			'four three two'
		}

		@media (max-width: 40rem) {
			#article_w {
				grid-template-areas: 
				'one'
				'two'
				'three'
				'four'
				'five';
			}
		}

		article:nth-child(1) {
			grid-area: one;
		}

		article:nth-child(2) {
			grid-area: two;
		}	
		
		article:nth-child(3) {
			grid-area: three;
		}	

		article:nth-child(4) {
			grid-area: four;
		}	

		article:nth-child(5) {
			grid-area: five;
		}			

		article {
			padding: 2rem;
			border: 1px solid #CCC;
		}

		

		article p:not(.position) {
			font-family: Studio-Feixen-Sans, Helvetica;
			font-size: 1.125em;
			font-weight: 300;
			line-height: 1.5;
			color: var(--clr-lt-dark);
		}

		/* Utility classes and selectors. */
		.name-position {
			display: flex;
			column-gap: 2rem;
			align-items: center;
		}
		.name-position > h2 {
			font-size: 2.625rem;
			font-weight: 300;
			color: var(--clr-med-green);
		}
		.name-position > p {
			margin-left: auto;
			font-family: Studio-Feixen-Sans, Helvetica;
			font-size: 1.5rem;
			font-weight: 300;
			line-height: 1.5;
			color: var(--clr-med-green);
		}

		/* Component classes */
		.desc {

		}
		.short-desc {
			padding-bottom: .675rem;
			border-bottom: 1px solid transparent;
			border-image: linear-gradient( to right, 
			var(--clr-lt-light) 0%, 
			var(--clr-med-light) 50%, 
			var(--clr-lt-light) 100% ) 1;
		}
		.long-desc {
			/* flex: 1 1 max(30rem, 65%); */
			padding-top: .675rem;
			border-left: 1px solid transparent;
			border-image: linear-gradient( to bottom, 
			var(--clr-lt-light) 0%, 
			var(--clr-med-light) 50%, 
			var(--clr-lt-light) 100% ) 1;
		}


	</style>

</head>

<body>

	<?php include_once('laheader.php'); ?>

	<main>

		<div id="page_w">
			<div id="article_w">
			
			<article class="">
				<div class="name-position">
					<h2 class="name">Daniel Clifford</h2>
					<p class="position">Verified Graduate</p>
				</div>
				<div class="desc">
					<p class="short-desc">
					I received a job offer mid-course, and the subjects I learned were current, if not more so,
					in the company I joined. I honestly feel I got every penny’s worth.
					</p>
					<p class="long-desc">
					“ I was an EMT for many years before I joined the bootcamp. I’ve been looking to make a
					transition and have heard some people who had an amazing experience here. I signed up
					for the free intro course and found it incredibly fun! I enrolled shortly thereafter.
					The next 12 weeks was the best - and most grueling - time of my life. Since completing
					the course, I’ve successfully switched careers, working as a Software Engineer at a VR startup. ”
					</p>
				</div>
			  </article>


			  <article class="">
				<div class="name-position">
					<h2 class="name">Jonathan Walters</h2>
					<p class="position">Verified Graduate</p>
				</div>
				<div class="desc">
					<p class="short-desc">
					The team was very supportive and kept me motivated
					</p>
					<p class="long-desc">
					“ I started as a total newbie with virtually no coding skills. I now work as a mobile engineer
					for a big company. This was one of the best investments I’ve made in myself. ”
					</p>
				</div>
			  </article>
			
			  <article class="">
				<div class="name-position">
					<h2 class="name">Jeanette Harmon</h2>
					<p class="position">Verified Graduate</p>
				</div>

				<div class="desc">
					<p class="short-desc">
					An overall wonderful and rewarding experience</p>
					<p class="long-desc">
					“ Thank you for the wonderful experience! I now have a job I really enjoy, and make a good living
					while doing something I love. ”
					</p>
				</div>
			  </article>
			
			  <article class="">
				<div class="name-position">
					<h2 class="name">Patrick Abrams</h2>
					<p class="position">Verified Graduate</p>
				</div>
				<div class="desc">
					<p class="short-desc">
					Awesome teaching support from TAs who did the bootcamp themselves. Getting guidance from them and
					learning from their experiences was easy.
					</p>
					<p class="long-desc">
					“ The staff seem genuinely concerned about my progress which I find really refreshing. The program
					gave me the confidence necessary to be able to go out in the world and present myself as a capable
					junior developer. The standard is above the rest. You will get the personal attention you need from
					an incredible community of smart and amazing people. ”
					</p>
				</div>
			  </article>
			
			  <article class="">
				<div class="name-position">
					<h2 class="name">Kira Whittle</h2>
					<p class="position">Verified Graduate</p>
				</div>
				<div class="desc">
					<p class="short-desc">
					Such a life-changing experience. Highly recommended!
					</p>
					<p class="long-desc">
					“ Before joining the bootcamp, I’ve never written a line of code. I needed some structure from
					professionals who can help me learn programming step by step. I was encouraged to enroll by a former
					student of theirs who can only say wonderful things about the program. The entire curriculum and staff
					did not disappoint. They were very hands-on and I never had to wait long for assistance. The agile team
					project, in particular, was outstanding. It took my learning to the next level in a way that no tutorial
					could ever have. In fact, I’ve often referred to it during interviews as an example of my developent
					experience. It certainly helped me land a job as a full-stack developer after receiving multiple offers.
					100% recommend! ”
					</p>
				</div>
			  </article>				 		
			</div>
		</div>

	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>