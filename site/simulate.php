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

				<div>
					<form name="contact" id="contact" method="post" 
					data-composite="0" data-erroreleid="error_form" action="contact_handler.php">

						<ul>
							<li>
								<label class="">First Name<em>*</em></label>
								<label class="">Last Name<em>*</em></label>                                    
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

							<li>
								<label class="">Email<em>*</em></label>
							</li>
							<li>
								<input
									name="email" type="text" title="Enter Email" class="" 
									maxlength="32" data-caption="Email" data-id="email" data-req="1"
									data-vx="vxEmail" data-len="32" autocomplete="off">

							</li>
							<li>
								<label class="">Subject<em>*</em></label> 
							</li>
							<li>
								<input name="contact_subject" type="text" title="Enter Subject" class=""
								maxlength="32" data-caption="Subject" data-id="subject" data-req="1"
								data-vx="vxAlphaNum" data-len="32" autocomplete="off">

							</li>
							<li>
								<label class="">Message<em>*</em></label> 
							</li>
							<li>
								<textarea name="contact_message" rows="8" cols="48"
								data-caption="Message" data-id="contact_message" data-req="1" data-vx="vxText" data-len="512"></textarea>

							</li>
							<li>
								<li><input type="submit" value="SUBMIT"></li> 
							</li>
							<li>
								<span class="error-msg"></span>
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
