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

            <h1 class="blog-heading site-font-fancyl">Store database logs in Amazon Web Services CloudWatch Logs</h1>
            <h3 class="blog-date">December22, 2024</h3>
            <h2 class="blog-desc site-font-emph">
            Exemplifies a PHP way to use Amazon Web Services CloudWatch Logs to organize database queries and errors. (Yes, PHP in 2025!).
            </h2>

        </header>        

        <section>

            <img src="assets/aws_cw_720.png" class="blog-img">

            <p style="margin: 10px 0 10px 0px;"> 
                <em class="site-font-fancyl">PHP</em> - is it really still used in the real world? The answer
                is a resounding yes due to the language's simplicity and ease of use. Tired of storing application
                database error and query logs on the filesystem? Using Amazon Web Services (AWS) CloudWatch Logs can
                make capturing, organizing, and accessing log files simpler than a traditional filesystem approach. The
                advantages of using AWS to manage application database error and query logs are several, including:
            </p>

            <ul>
                <li>No server filesystem writes or reads.</li>
                <li>No server filesystem permission management.</li>
                <li>Logs are available to AWS IAM users - helpful when working within
                    a team of developers.
                </li>
                <li>Integrates as part of a comprehensive logging strucuture using AWS CloudWatch Logs.</li>
                <li>Can use CloudWatch metrics to create reports of data within the logs.</li>
            </ul>                    

            <p>
                The content will draw on a previous post, 
                <a href="pythonpostgreconnect.php" class="blog-intralink">Connecting Python to a Postgresql database</a>
                to create and decrypt database connection parameters with AWS Key Management Service (KMS) encryption key.
            </p>

            <h1 class="blog-subheading">Requirements</h1>

            <ul>
                <li>PHP 8 (preferably running on Ubuntu)</li>
                <li>PHP composer</li>
                <li>PHP composer packages: aws/aws-sdk-php, legalthings/cloudwatch-logger</li>
                <li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
                <li>AWS: CloudWatch Logs - Log Groups and Streams</li>
                <li>Postgresql database cluster (needs to accept TCP/IP connections)</li> 
                <li>Database created on Postgresql cluster</li>
            </ul>        

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    