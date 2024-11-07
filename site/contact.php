<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/la.js"></script>
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/hdrftr.js"></script>
    <script type="text/javascript" src="js/LAValidation.js"></script>
    

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

    <main>

        <div id="contact_w">

            <section>

                <h1 class="page-section-heading">Contact Us</h1>

                <div id="contact_form_w">

                    <div id="contact_form">

                        <form name="contact" action="">

                            <ul>
                                <li>
                                    <ul>
                                        <fieldset>
                                            <li class="clear">
                                                <label class="adjacent-first">First Name<em>*</em></label>
                                                <label class="adjacent-second">Last Name<em>*</em></label>
                                            </li>
                                            <li class="clear">
                                                <input name="contact_fname" type="text" title="Enter First Name" class="adjacent-first" maxlength="32">                              
                                                <input name="contact_lname" type="text" title="Enter Last Name" class="adjacent-second" maxlength="32">
                                            </li>
                                        </fieldset>
                                    </ul>
                                </li> 
                                
                                <li>
                                    <label class="only">Email<em>*</em></label>
                                </li>
                                <li>
                                    <input name="contact_email" type="text" title="Enter Email" class="only" maxlength="32">
                                </li>
                                <li>
                                    <label class="only">Subject<em>*</em></label>                    
                                </li>
                                <li>
                                    <input name="contact_subject" type="text" title="Enter Subject" class="only" maxlength="32">
                                </li>                    
                                <li>
                                    <label class="only">Message<em>*</em></label> 
                                    <textarea name="contact_msg" rows="8" cols="48"></textarea>
                                </li>
                                <li><input name="contact_submit" type="submit" value="SUBMIT"></li> 
                                <li class="form-submit-msg"></li>         
                        
                            </ul>
                        
                        </form> 

                    </div> <!-- End contact_form -->

                    <div id="contact_com_w">
                        <p class="site-font-emph">Please contact us with any questions, statements, or suggestions you may have. We would love to hear from you!</p>
                    </div>

                </div> <!-- End contact_form_w -->

            </section>

        </div> <!-- contact_w -->

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>