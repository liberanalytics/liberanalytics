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
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Contact | LiberAnalytics</title>
	<meta name="description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<link rel="canonical" href="https://liberanalytics.com/contact">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">
	
	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Contact | LiberAnalytics">
	<meta property="og:description" content="Solo-built data platform focused on clarity, precision, and creative problem-solving with data in real-world contexts.">
	<meta property="og:url" content="https://liberanalytics.com/contact">
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

	<link href="/css/la_tokens.css" rel="stylesheet">
	<link href="/css/la_fonts.css" rel="stylesheet">		
	<link href="/css/la.css" rel="stylesheet">
	<link href="/css/la_hdrftr.css" rel="stylesheet">
	<link href="/css/la_forms.css" rel="stylesheet">
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/contact.css" rel="stylesheet">
	<script type="module" src="/js/la.js"></script> 	  
	<script type="module" src="/js/contact.js"></script>
</head>

<body class="page page-about site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<section class="site-ajax-overlay-relative">
				<h1 class="site-primary-heading site-font-primary-heading">Contact Us</h1>

				<div class="site-inline-std site-gradient-border-bottom">					
					<p>Please contact us with any questions, statements, or suggestions. We would love to hear from you!</p>
					<form
						id="contact_form"
						name="contact" 
						method="post"
						data-composite="0"
						data-erroreleid="error_form"
						action="/api/contact.php"
						class="contact-form site-form-std site-font-form-std site-gradient-border-bottom"
					>
						<ul class="contact-form-controls site-form-std-controls">
							<li class="form-ele-container"><label for="first_name" class="site-fw-bold">First Name<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input 
									id="first_name" name="first_name" type="text" title="Enter First Name" class="" 
									maxlength="32" data-caption="First Name" data-id="first_name" data-req="1"
									data-vx="vxName" data-len="32" autocomplete="given-name"
								>
							</li>

							<li class="form-ele-container"><label for="last_name" class="site-fw-bold">Last Name<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input 
									id="last_name" name="last_name" type="text" title="Enter Last Name" class="" 
									maxlength="32" data-caption="Last Name" data-id="last_name" data-req="1"
									data-vx="vxName" data-len="32" autocomplete="family-name"
								>      
							</li>
							<li class="form-ele-container"><label for="email" class="site-fw-bold">Email<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input
									id="email" name="email" type="text" title="Enter Email" class="" 
									maxlength="32" data-caption="Email" data-id="email" data-req="1"
									data-vx="vxEmail" data-len="32" autocomplete="email"
								>							
							</li>
							<li class="form-ele-container"><label for="subject" class="site-fw-bold">Subject<em class="site-fc-med-red">*</em></label></li>
							<li>
								<input
									id="subject" name="contact_subject" type="text" title="Enter Subject" class=""
									maxlength="32" data-caption="Subject" data-id="subject" data-req="1"
									data-vx="vxAlphaNum" data-len="32" autocomplete="off"
							>							
							</li>
							<li class="text-area-label-container"><label for="message" class="site-fw-bold">Message<em class="site-fc-med-red">*</em></label></li>
							<li class="">
								<textarea 
									id="message" name="contact_message" rows="8"
									data-caption="Message" data-id="contact_message" data-req="1"
									data-vx="vxText" data-len="512"
								></textarea>							
							</li>
							<li><input type="submit" value="SUBMIT" class="site-fc-xlight site-fw-bold"></li> 
							<li class="form-error-container"><span id="form_error_ele" class="site-font-form-error" aria-live="polite"></span></li>														
						</ul>					
					</form>					
				</div>

				<div class="site-ajax-overlay-flex site-ajax-hidden">					
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
			</section>
		</div>
	</main>

<?php include_once('lafooter.php'); ?>

</body>
</html>