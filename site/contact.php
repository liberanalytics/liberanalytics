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
	<link href="css/la_tokens.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_fonts.css" rel="stylesheet" type="text/css" charset="utf-8">		
	<link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_hdrftr.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_forms.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_tags.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/la_ajax.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="module" src="js/la.js"></script> 	  
	<script type="module" src="js/contact.js"></script>
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

				<div class="contact-form-container site-block-std site-gradient-border-bottom">
					<form
						id="contact_form"
						name="contact" 
						method="post"
						data-composite="0"
						data-erroreleid="error_form"
						action="/api/contact.php"
						class="site-form-std"
					>

						<ul class="site-font-form-std contact-form-stack">
							<li><label for="first_name" class="site-fw-bold">First Name<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input 
									id="first_name" name="first_name" type="text" title="Enter First Name" class="" 
									maxlength="32" data-caption="First Name" data-id="first_name" data-req="1"
									data-vx="vxName" data-len="32" autocomplete="given-name"
								>
							</li>

							<li><label for="last_name" class="site-fw-bold">Last Name<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input 
									id="last_name" name="last_name" type="text" title="Enter Last Name" class="" 
									maxlength="32" data-caption="Last Name" data-id="last_name" data-req="1"
									data-vx="vxName" data-len="32" autocomplete="family-name"
								>      
							</li>
							<li><label for="email" class="site-fw-bold">Email<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input
									id="email" name="email" type="text" title="Enter Email" class="" 
									maxlength="32" data-caption="Email" data-id="email" data-req="1"
									data-vx="vxEmail" data-len="32" autocomplete="email"
								>							
							</li>
							<li><label for="subject" class="site-fw-bold">Subject<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input
									id="subject" name="contact_subject" type="text" title="Enter Subject" class=""
									maxlength="32" data-caption="Subject" data-id="subject" data-req="1"
									data-vx="vxAlphaNum" data-len="32" autocomplete="off"
							>							
							</li>
							<li><label for="message" class="site-fw-bold">Message<em class="site-fc-med-red">*</em></label></li>
							<li>
								<textarea 
									id="subject" name="contact_message" rows="8" class=""
									data-caption="Message" data-id="contact_message" data-req="1"
									data-vx="vxText" data-len="512"
								>
								</textarea>							
							</li>
							<li><input type="submit" value="SUBMIT" class="site-fc-xlight site-fw-bold"></li> 
							<li><span class="error-msg" aria-live="polite"></span></li>														
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