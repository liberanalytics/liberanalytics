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
	<link href="css/forms.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
	<script type="text/javascript" src="js/LAStyles.js"></script>
	<script type="text/javascript" src="js/la.js"></script> 	
	<script type="text/javascript" src="js/LAvx.js"></script>   
	<script type="text/javascript" src="js/contact.js"></script>
</head>

<body>

	<?php include_once('site_header.php'); ?>

	<main>
	<div id="page_w">
		<div id="contact_w">
			<section>
				<h1 class="page-section-heading">Contact Us</h1>
				<div id="contact_form_w">
					<div id="contact_form">

						<p class="site-font-emph">
							Please contact us with any questions, statements, or suggestions. We would love to hear from you!
						</p>

						<form name="contact" id="contact" method="post" 
						 data-composite="0" data-erroreleid="error_form" action="contact_handler.php">

							<ul>
								<li>
									<label class="label-first">First Name<em>*</em></label>
									<label class="label-second">Last Name<em>*</em></label>                                    
								</li>
								<li>
									<input name="contact_fname" type="text" title="Enter First Name" class="input-first" 
									maxlength="32" data-caption="First Name" data-id="first_name" data-req="1" data-vx="vxName" data-len="32">

									<input name="contact_lname" type="text" title="Enter Last Name" class="input-second" 
									maxlength="32" data-caption="Last Name" data-id="last_name" data-req="1" data-vx="vxName" data-len="32">

      
								</li>

								<li>
									<label class="label-first">Email<em>*</em></label>
								</li>
								<li>
									<input name="contact_email" type="text" title="Enter Email" class="input-only" 
									maxlength="32" data-caption="Email" data-id="email" data-req="1" data-vx="vxEmail" data-len="32">

								</li>
								<li>
									<label class="label-first">Subject<em>*</em></label> 
								</li>
								<li>
									<input name="contact_subject" type="text" title="Enter Subject" class="input-only"
									 maxlength="32" data-caption="Subject" data-id="subject" data-req="1" data-vx="vxAlphaNum" data-len="32">

								</li>
								<li>
									<label class="label-first">Message<em>*</em></label> 
								</li>
								<li>
									<textarea name="contact_msg" rows="8" cols="48"
									data-caption="Message" data-id="message" data-req="1" data-vx="vxText" data-len="512"></textarea>

								</li>
								<li>
									<li><input name="contact_submit" type="submit" value="SUBMIT"></li> 
								</li>
								<li>
									<span id="error_form" class="error-msg"></span>
								</li>								
							</ul>                            
						
						</form> 

					</div> <!-- End contact_form -->
				</div> <!-- End contact_form_w -->

			</section>

		</div><!-- End contact_w -->
	</div><!-- End page_w -->
	</main>

	<?php include_once('site_footer.php'); ?>

</body>
</html>