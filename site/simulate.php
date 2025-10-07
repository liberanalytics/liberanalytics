<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>About | LiberAnalytics</title>
	<meta name="description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<link rel="canonical" href="https://liberanalytics.com/about">

	<meta name="theme-color" content="#333333">
	<meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
	<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">

	<link href="css/la_tokens.css" rel="stylesheet">
	<link href="css/la_fonts.css" rel="stylesheet">
	<link href="css/la.css" rel="stylesheet">
	<link href="css/la_hdrftr.css" rel="stylesheet">
	<link href="css/la_tags.css" rel="stylesheet">
	<link href="css/la_ajax.css" rel="stylesheet">
	<script type="module" src="js/la.js"></script>

	<style>

		.sim-even-cols {
			display: grid;
		}

		.sim-intro {
			margin-bottom: var(--sz-600);
		}

		.sim-anchor-link {
			display: inline-block;
			margin-bottom: var(--sz-600);
		}

		@media (min-width: 61.25em) {
			.sim-even-cols {
				grid-template-columns: 1fr 1fr;
				align-items: start;
				gap: var(--sz-600);
			}

			.page-wrap::after {
				display: block;
				content: "";
				height: 20vh;
			}
		}

		html {
			scroll-snap-type: y proximity;
			scroll-behavior: smooth;
		}

		section[id="liber_about"],
		section[id="liber_philosophy"] {
    		scroll-snap-align: start;
		}
		
		@media (prefers-reduced-motion: reduce) {
			html { scroll-snap-type: none; scroll-behavior: auto; }
			section[id="liber_about"],
			section[id="liber_philosophy"] { scroll-snap-align: none; }
		}

	</style>
</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap site-fs-med">

			<nav class="sim-even-cols">

				<div>
					<h2 class="site-primary-heading">About LiberAnalytics</h2>

					<div class="site-block-std">
						<p class="sim-intro site-fw-bold">LiberAnalytics is a data focused website with the following goals:</p>
						<ul class="site-stack-mtmb">
							<li>Educate data practioners on best practices when working with data.</li>
							<li>
								Demonstrate through example how to avoid common pitfalls
								while traversing the domain of data analytics.
							</li>
							<li>
								Inspire data practioners to think in terms of non-deterministic
								creative problem solving when tackling cleansing, analysis,
								predictive, and prescriptive data challenges.
							</li>
						</ul>
						<a href="#liber_about" class="sim-anchor-link | site-font-link-std site-fs-med">Jump to section -></a>
					</div>
				</div>

				<div>
					<h2 class="site-primary-heading">LiberAnalytics Philosophy</h2>
					<div class="site-block-std">
						<p class="sim-intro site-fw-bold">LiberAnalytics philosophy is simple to comprehend while profound
							in underlying principle: to motivate others in solving data
							problems by tapping into the creative self.
						</p>
						<ul class="site-stack-mtmb">
							<li>
								Nietzsche's amor fati - to embrace one's fate and accept
								that difficulty, challenges, and detours in data and one's
								life are the path forward and can be sublimated into creative
								drive.
							</li>
							<li>
								Jung's individuation - the fragmented parts of ourselves can
								be synthesized into a greater, synergistic whole that is
								greater than the sum of the parts.
							</li>
							<li>
								Emerson's transcendentalism - to look within oneself in the pursuit
								of individual meaning and productivity.
							</li>
						</ul>
						<a href="#liber_philosophy" class="sim-anchor-link | site-font-link-std site-fs-med">Jump to section -></a>
					</div>
				</div>

			</nav>

			<section id="liber_about">				
				<h1 class="site-primary-heading">What we are about</h1>
				<div class="site-stack-mtmb">
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

			<section id="liber_philosophy">
				<h1 class="site-primary-heading">Philosophies that form our core values</h1>
				<div class="site-stack-mtmb">
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