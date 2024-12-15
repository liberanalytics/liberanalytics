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

            <h1 class="blog-heading site-font-fancyl">Connecting Python to a Postgresql database</h1>
            <h3 class="blog-date">December 15, 2024</h3>
            <h2 class="blog-desc site-font-emph">
                Discover how to connect Python to a Postgresql database without storing credentials or keys on filesystem.
            </h2>
            

        </header>

        <section>

            <img src="assets/pythonpostgreconnect720.png" class="blog-img">

            <p>
                <em class="site-font-fancyl">Connecting</em> Python to Postgresql is made seamless with the psycopg2 library.
                This article demonstrates how to connect to a Postgresql database using Python without storing credentials
                in plain text or in the .pgpass file. The architecture we will be using consists of a Postgresql database
                hosted on an Ubuntu 24 (Noble) Amazon Web Services (AWS) EC2 instance and Python 3.10 running on Ubuntu 22 (Jammy).
                We will use an asymmetric AWS KMS key to encrypt and decrypt database credentials.
            </p>

            <h1 class="blog-subheading">Requirements</h1>

            <ul>
                <li>Python 3 (preferably running on Ubuntu)</li>
                <li>Python libraries: psycopg2, boto3</li>
                <li>AWS: Key Management Service (KMS) Customer managed asymmetric key</li>
                <li>Postgresql database (needs to accept TCP/IP connections)</li>
            </ul>

            <h1 class="blog-subheading">Get database connection parameters</h1>

            <p>
                First we will need to know the database host, database name, user name, and password so a
                connection can be established. Python's getpass module will be used to avoid echoing back
                the sensitive user inputs.
            </p>

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    