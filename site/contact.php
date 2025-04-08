<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/LAHdrFtr.js"></script>
    <script type="text/javascript" src="js/LAvx.js"></script>
    <script type="text/javascript" src="js/la.js"></script>    
    <script type="text/javascript" src="js/contact.js"></script>

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

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

                        <form name="contact" id="contact" method="post" action="contact_handler.php">

                            <ul>
                                <li>
                                    <label class="label-first">First Name<em>*</em></label>
                                    <label class="label-second">Last Name<em>*</em></label>                                    
                                </li>
                                <li>
                                    <input name="contact_fname" type="text" title="Enter First Name" class="input-first" 
                                    maxlength="32" data-id="First name" data-req="1" data-vx="vxName" data-len="32">

                                    <input name="contact_lname" type="text" title="Enter Last Name" class="input-second" 
                                    maxlength="32" data-id="Last name" data-req="1" data-vx="vxName" data-len="32">

                                    <span id="error_name" class="error-msg"></span>        
                                </li>

                                <li>
                                    <label class="label-first">Email<em>*</em></label>
                                </li>
                                <li>
                                    <input name="contact_email" type="text" title="Enter Email" class="input-only" 
                                    maxlength="32" data-id="Email" data-req="1" data-vx="vxEmail" data-len="32">
                                    <span id="error_email" class="error-msg"></span>
                                </li>
                                <li>
                                    <label class="label-first">Subject<em>*</em></label> 
                                </li>
                                <li>
                                    <input name="contact_subject" type="text" title="Enter Subject" class="input-only"
                                     maxlength="32" data-id="Subject" data-req="1" data-vx="vxAlphaNum" data-len="32">
                                    <span id="error_subject" class="error-msg"></span>
                                </li>
                                <li>
                                    <label class="label-first">Message<em>*</em></label> 
                                </li>
                                <li>
                                    <textarea name="contact_msg" rows="8" cols="48" data-id="Message" data-req="1" data-vx="vxMsg" data-len="512"></textarea>
                                    <span id="error_msg" class="error-msg"></span>
                                </li>
                                <li>
                                    <li><input name="contact_submit" type="submit" value="SUBMIT"></li> 
                                </li>

                            </ul>                            
                        
                        </form> 

                    </div> <!-- End contact_form -->

                </div> <!-- End contact_form_w -->

            </section>

        </div> <!-- contact_w -->
        </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>