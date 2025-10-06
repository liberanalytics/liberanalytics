<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LiberAnalytics | Terms of Use</title>
	<link href="css/la_tokens.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_fonts.css" rel="stylesheet" type="text/css" charset="utf-8">		
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_tags.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_ajax.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="js/la.js"></script>

	<style>
		.sim-container {
			--max-width: 980px;
			--padding: 1rem;

			width: min(var(--max-width), 100% - (var(--padding) * 2));
			margin-inline: auto;
		}

		.sim-even-columns {
			display: grid;
			gap: var(--sz-400);
		}

		.sim-liber-philosophy {

		}

		.sim-liber-stack {
			
		}

		.sim-liber-philosophy,
		.sim-liber-stack {
			margin-block: var(--sz-800);
		}

		@media (min-width: 60em) {
			.sim-even-columns {
				grid-auto-flow: column;
				grid-auto-columns: 1fr;
			}
		}

	</style>
</head>

<body class="sim-container site-font-std">

	<?php include_once('laheader.php'); ?>

	<main class="sim-even-columns">

		<section>

			<div class="sim-liber-philosophy site-block-std site-stack-mt site-fs-med">
			
			<h1 class="site-primary-heading">About LiberAnalytics</h1>

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

	<section>

		<div class="sim-liber-stack site-block-std site-stack-mt site-fs-med">
		
			<h1 class="site-primary-heading">LiberAnalytics Philosophy</h1>

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
				And from Nietzsche, LiberAnallytics embodies amor fati — love of one’s fate.
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

	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>
