<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" type="text/css" charset="utf-8"> 
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/blog.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/LAHdrFtr.js"></script>
    <script type="text/javascript" src="js/la.js"></script>

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

    <main>

    <div id="blog_w" class="site-font-stdl">

        <header>

            <h1 class="blog-heading site-font-fancyl">Generate flat files from a Postgresql database with PHP</h1>
            <h3 class="blog-date">December22, 2024</h3>
            <h2 class="blog-desc site-font-emph">
                Exemplifies a PHP way to create flat files from a SELECT query against a Postgresql database. (Yes, PHP in 2025!).
            </h2>

        </header>        

        <section>

            <img src="assets/phpposgredbsetup_720.png" class="blog-img">

            <p> 
                <em class="site-font-fancyl">PHP</em> - is it really still used in the real world? The answer
                is a resounding yes due to the language's simplicity and ease of use. In this post we will
                explore PHP's usefulness in performing a database set up and initialization of reference
                data in the database. The content will draw on a previous post, 
                <a href="pythonpostgreconnect.php" class="blog-intralink">Connecting Python to a Postgresql database</a> to read and decrypt database connection
                parameters encrypted with an Amazon Web Services (AWS) Key Management Service (KMS) asymmetric
                encryption key.
            </p>

            <h1 class="blog-subheading">Requirements</h1>

            <ul>
                <li>PHP 8 (preferably running on Ubuntu)</li>
                <li>PHP libraries: psycopg2, boto3</li>
                <li>AWS: Key Management Service (KMS) Customer managed asymmetric key</li>
                <li>Postgresql database cluster (needs to accept TCP/IP connections)</li> 
                <li>Database created on Postgresql cluster</li>           

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    