<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LiberAnalytics | Contact Us</title>
	<link href="css/la_tokens.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_fonts.css" rel="stylesheet" type="text/css" charset="utf-8">		
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_tags.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_ajax.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="js/la.js"></script>

	<style>

		.contact-container {
			padding: 1.5rem 0 .625rem 0;
			border-bottom: 1px solid transparent;
			border-image: linear-gradient( to right, 
				var(--clr-lt-light) 0%, 
				var(--clr-med-light) 50%, 
				var(--clr-lt-light) 100% ) 1;
		}

	</style>

</head>

<body class="page site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<section>
				<h1 class="site-primary-heading">Contact Us</h1>

				<div class="site-block-std">
					<p>Please contact us with any questions, statements, or suggestions. We would love to hear from you!</p>
				</div>

				<div class="contact-container">
					<form name="contact" id="contact" method="post" 
					data-composite="0" data-erroreleid="error_form" action="contact_handler.php">

						<ul>
							<li>
								<label class="">First Name<em class="site-fc-med-red">*</em></label>
								<label class="">Last Name<em class="site-fc-med-red">*</em></label>                                    
							</li>
							<li>
								<input 
									name="first_name" type="text" title="Enter First Name" class="" 
									maxlength="32" data-caption="First Name" data-id="first_name" data-req="1"
									data-vx="vxName" data-len="32" autocomplete="off">

								<input 
									name="last_name" type="text" title="Enter Last Name" class="" 
									maxlength="32" data-caption="Last Name" data-id="last_name" data-req="1"
									data-vx="vxName" data-len="32" autocomplete="off">      
							</li>

														
						</ul>                            
					
					</form> 					
				</div>

				<div class="site-ajax-overlay hidden">
					<div class="site-ajax-spinner-centerer">
						<div class="site-ajax-spinner-8spoke ">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div>
					</div>
				</div>				
					
			</section>
		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>
