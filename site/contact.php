<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/contact.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/la.js"></script>
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/LAHdrFtr.js"></script>
    <script type="text/javascript" src="js/LAvx.js"></script>
    <script type="text/javascript" src="js/contact.js"></script>
    

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

    <main>

        <div id="contact_w">

            <section>

                <h1 class="page-section-heading">Contact Us</h1>

                <div id="contact_form_w">

                    <div id="contact_form">

                        <form name="contact" id="contact" method="post">

                            <ul>
                                <li>
                                    <label class="label-first-name">First Name<em>*</em></label>
                                    <label class="label-last-name">Last Name<em>*</em></label>                                    
                                </li>
                                <li>
                                    <input name="contact_fname" type="text" title="Enter First Name"
                                    class="input-first-name" maxlength="32" data-req="1" data-vx="vxName" data-len="32">
                                    <input name="contact_lname" type="text" title="Enter Last Name"
                                    class="input-last-name" maxlength="32" data-req="1" data-vx="vxName" data-len="32">
                                    <span class="error-msg">Please enter a first name.</span>                                   
                                </li>
                            </ul>
                            
                        
                        </form> 

                    </div> <!-- End contact_form -->

                </div> <!-- End contact_form_w -->

            </section>

        </div> <!-- contact_w -->

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>