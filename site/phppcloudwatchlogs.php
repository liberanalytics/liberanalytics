<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    include_once('../src/classes/LaData.php');
    $laData = new LaData();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" type="text/css" charset="utf-8"> 
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/post.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/LAHdrFtr.js"></script>
    <script type="text/javascript" src="js/la.js"></script>

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

    <main>
    <div id="page_w">

    <div id="blog_w" class="site-font-stdl">

        <header>
            <?php
                $postName = basename($_SERVER['PHP_SELF'], ".php");
                $laData->openConn();
                $post = $laData->getPosts
                (
                    $pFeatured = TRUE, 
                    $pPostName = $postName,           
                    $pCategory = NULL, 
                    $pOrderBy   = NULL,
                    $pLimit = NULL
                );
                $post = $post->fetch(PDO::FETCH_ASSOC);
                $laData->closeConn();
            ?>

            <h1 class="blog-heading site-font-fancyl"><?php echo $post['short_desc']; ?></h1>
            <h3 class="blog-date"><?php echo date("F j, Y", strtotime($post['post_created'])); ?></h3>
            <h2 class="blog-desc site-font-emph"><?php echo $post['long_desc']; ?></h2>
            <img <?php echo "src=\"assets/{$post['post_img']}\""; ?> class="blog-img">                      

        </header>        

        <section>

            <p>
                <em class="site-font-fancyl">Ever</em> grow tired of storing logs on a server then having to manage
                access to the logs for individual users working in a collaborative development team? This post explores
                an alternative method to manage database logging by making use of the Amazon Web Services (AWS) CloudWatch
                Logs service. After reading this post you should have an idea of how to code PHP to send database logs
                to AWS CloudWatch. This approach offers several advantages over storing logs on a server filesystem, including:               
            </p>

            <ul>
                <li>Infrastructure is managed by AWS.</li>
                <li>
                    Permissions to logs are orchestrated through AWS IAM which can greatly improve means of access
                    when working within a development team.
                </li>
                <li>Can be integrated as part of an organization wide logging structure with AWS CloudWatch Logs.</li>
                <li>Can use CloudWatch metric filters to create data visualizations of the log data
                    and generate alarms from those metrics using CloudWatch Alarms.
                </li>
            </ul>
            
            <p>
                This post depends on setting up a database configuration file which is covered in a previous post:  
                <a href="pythonpostgreconnect.php">Connecting Python to a Postgresql database</a>.
            </p>

            <h1 class="blog-subheading">Requirements</h1>

            <ul>
                <li>PHP 8 (preferably running on Ubuntu)</li>
                <li>PHP composer</li>
                <li>PHP composer packages: aws/aws-sdk-php, legalthings/cloudwatch-logger</li>
                <li>AWS: Programmatic Access</li>
                <li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
                <li>AWS: CloudWatch Logs - Log Groups and Streams</li>
                <li>Postgresql database cluster (needs to accept TCP/IP connections)</li> 
                <li>Database created on Postgresql cluster</li>
            </ul>

            <h1 class="blog-subheading">Set up Log Group and Log Streams in AWS</h1>

            <p>
                First we need to create a log group and two log streams within the group which will store our database sql queries and errors.
                A log group named 'sdk' with two log streams named 'php/database/sql/' and 'php/database/errors/'
                have been created. After creating the log group and streams, ensure that the programmatic AWS user has the
                necessary AWS IAM credentials to write to the log streams which is the PutLogEvents API call. The simplest way
                to try out the example in this post is to attach the managed policy CloudWatchLogsFullAccess to the IAM user
                or group. The log stream structure looks like the below from the AWS console:
            </p>

            <img src="assets/phpcloudwatchlogs_logstreams.png">

            <h1 class="blog-subheading">Create CloudWatch Logs / KMS / CloudWatchLogger instances in PHP</h1>

            <p>
                Use PHP composer to install the AWS PHP SDK and logging packages - note the cloudwatch-logger package 
                from legalthings is used here. Then create the CloudWatch Logs / KMS / CloudWatchLogger instances in the PHP program.
                Since the instances are essential to the rest of the program, a try / catch block with an exit statement is used
                in their instantiation. If unable to connect to the AWS CloudWatch Logs or KMS services, check the IaM permissions
                for the programmatic user.
            </p>
                        
            <div class="blog-snippet" style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; font-size: 16px; line-height: 19px; overflow: auto; white-space: nowrap;"><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">/**</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;phppdocloudwatchlogs.php</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;Creates&nbsp;a&nbsp;pdo&nbsp;connection&nbsp;to&nbsp;a&nbsp;postgresql&nbsp;database&nbsp;and&nbsp;runs&nbsp;a&nbsp;sample</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;query.&nbsp;Demonstrates&nbsp;how&nbsp;to&nbsp;use&nbsp;AWS&nbsp;Key&nbsp;Management&nbsp;Service&nbsp;keys&nbsp;to&nbsp;</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;decrypt&nbsp;database&nbsp;connection&nbsp;parameters&nbsp;from&nbsp;a&nbsp;configuration&nbsp;file&nbsp;on</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;filesystem.&nbsp;The&nbsp;configuration&nbsp;file&nbsp;must&nbsp;have&nbsp;been&nbsp;created&nbsp;first</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;with&nbsp;the&nbsp;aws_postgresql_config.py&nbsp;program.&nbsp;Shows&nbsp;how&nbsp;to&nbsp;loq&nbsp;errors</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;encountered&nbsp;as&nbsp;well&nbsp;as&nbsp;queries&nbsp;run&nbsp;to&nbsp;CloudWatch&nbsp;Logs.</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">require</span><span style="color: #d4d4d4;">&nbsp;(</span><span style="color: #ce9178;">'../vendor/autoload.php'</span><span style="color: #d4d4d4;">);</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Create&nbsp;CloudWatch&nbsp;Logs&nbsp;/&nbsp;KMS&nbsp;/&nbsp;CloudWatchLogger&nbsp;instances.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span style="color: #d4d4d4;">&nbsp;{</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$aws_cwlogs_client</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;Aws\CloudWatchLogs\</span><span style="color: #4ec9b0;">CloudWatchLogsClient</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$aws_kms_client</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;Aws\Kms\</span><span style="color: #4ec9b0;">KmsClient</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;LegalThings\</span><span style="color: #4ec9b0;">CloudWatchLogger</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'aws'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;[</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'group_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'sdk'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'stream_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'php/database/errors/'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$sql_logger</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;LegalThings\</span><span style="color: #4ec9b0;">CloudWatchLogger</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'aws'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;[</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'group_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'sdk'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'stream_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'php/database/sql/'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Report&nbsp;on&nbsp;any&nbsp;exceptions&nbsp;-&nbsp;exit&nbsp;program&nbsp;since&nbsp;all&nbsp;instances&nbsp;are&nbsp;needed.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">catch</span><span style="color: #d4d4d4;">&nbsp;(</span><span style="color: #4ec9b0;">Exxception</span><span style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">)&nbsp;{</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #dcdcaa;">echo</span><span style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getTraceAsString</span><span style="color: #d4d4d4;">();</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span style="color: #ce9178;">'Couldn</span><span style="color: #d7ba7d;">\'</span><span style="color: #ce9178;">t&nbsp;instantiate&nbsp;a&nbsp;needed&nbsp;AWS&nbsp;or&nbsp;Logger&nbsp;class.&nbsp;Exiting.'</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><br></div></div>

            <h1 class="blog-subheading">Read database connection properties from encrypted configuration file</h1>

            <p>
                Now we will need to identify the database and user details used to connect to a PostgreSQL cluster and database.
                From the previous post, <a href="pythonpostgreconnect.php">Connecting Python to a Postgresql database</a>, the
                connection details will be read from a file storing the sensitive data in encrypted format. Then the AWS KMS key used to encrypt the data will be used to decrypt the connection details so a connection can be made to the PostgreSQL database.
            </p>
            
            <div class="blog-snippet" style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; font-size: 16px; line-height: 19px; margin: auto; white-space: nowrap;"><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Open&nbsp;and&nbsp;read&nbsp;the&nbsp;database&nbsp;config&nbsp;file:&nbsp;reset&nbsp;perms&nbsp;on&nbsp;database&nbsp;config</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;file&nbsp;when&nbsp;finished&nbsp;opening.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #ce9178;">'la_demo_db.ini'</span><span style="color: #d4d4d4;">;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #dcdcaa;">chmod</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #b5cea8;">0440</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$handle</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">fopen</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #ce9178;">'rb'</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #dcdcaa;">chmod</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #b5cea8;">0000</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$binary_data</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">fread</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$handle</span><span style="color: #d4d4d4;">,&nbsp;</span><span style="color: #dcdcaa;">filesize</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_file</span><span style="color: #d4d4d4;">));</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Decrypt&nbsp;the&nbsp;binary&nbsp;data&nbsp;in&nbsp;the&nbsp;database&nbsp;config&nbsp;file.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span style="color: #d4d4d4;">&nbsp;{</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$aws_kms_client</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">decrypt</span><span style="color: #d4d4d4;">([</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;AWS&nbsp;KMS&nbsp;decrypt&nbsp;P(x)'s&nbsp;-&nbsp;Include&nbsp;EncryptionAlgorithm&nbsp;as&nbsp;best&nbsp;practice.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'CiphertextBlob'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$binary_data</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'EncryptionAlgorithm'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'SYMMETRIC_DEFAULT'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'KeyId'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'keyid'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Log&nbsp;any&nbsp;exceptions&nbsp;to&nbsp;the&nbsp;error&nbsp;log&nbsp;group&nbsp;/&nbsp;stream.&nbsp;Exit&nbsp;on&nbsp;failure</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;since&nbsp;remaining&nbsp;program&nbsp;depends&nbsp;on&nbsp;decrypted&nbsp;data.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">catch</span><span style="color: #d4d4d4;">&nbsp;(</span><span style="color: #4ec9b0;">Exception</span><span style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">)&nbsp;{</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span style="color: #ce9178;">'Problem&nbsp;decrypting&nbsp;:&nbsp;'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getAwsErrorMessage</span><span style="color: #d4d4d4;">()]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span style="color: #ce9178;">"Unable&nbsp;to&nbsp;decrypt&nbsp;the&nbsp;data&nbsp;in&nbsp;</span><span style="color: #9cdcfe;">${db_config_file}</span><span style="color: #ce9178;">."</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Decode&nbsp;using&nbsp;base64&nbsp;decoding,&nbsp;then&nbsp;split&nbsp;data&nbsp;into&nbsp;components&nbsp;per&nbsp;delimiter.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">base64_decode</span><span style="color: #d4d4d4;">(</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">[</span><span style="color: #ce9178;">'Plaintext'</span><span style="color: #d4d4d4;">]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #dcdcaa;">explode</span><span style="color: #d4d4d4;">(&nbsp;</span><span style="color: #dcdcaa;">chr</span><span style="color: #d4d4d4;">(</span><span style="color: #b5cea8;">7</span><span style="color: #d4d4d4;">),&nbsp;</span><span style="color: #9cdcfe;">$db_config_data</span><span style="color: #d4d4d4;">&nbsp;);&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_host</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">0</span><span style="color: #d4d4d4;">];</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_name</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">1</span><span style="color: #d4d4d4;">];</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_user</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">2</span><span style="color: #d4d4d4;">];</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$db_auth</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #9cdcfe;">$db_params</span><span style="color: #d4d4d4;">[</span><span style="color: #b5cea8;">3</span><span style="color: #d4d4d4;">];</span></div><div><br></div></div>            

            <h1 class="blog-subheading">Capture decryption errors in AWS CloudWatch Logs</h1>

            <p>
                If an error was encountered during the encryption attempt there will be a log in the AWS CloudWatch Logs log stream
                previously configured: php/database/errors/. For simulation's sake we have generated an error to create a log record
                which appears in the AWS CloudWatch logs console as shown below.
            </p>

            <img src="assets/phpcloudwatchlogs_decrypt_error.png">

            <h1 class="blog-subheading">Connect to PostgreSQL database and capture connection errors in AWS CloudWatch Logs</h1>

            <p>
                Finally it is time to connect to the database. PHP's PDO data access layer is great for abstracting calls to database
                operations and easy to instantiate. To show the usefulness of CloudWatch logs a simulation of a failed connection attempt
                is run here.
            </p>

            <div
                class="blog-snippet"
                style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; font-size: 16px; line-height: 19px; margin: auto; white-space: nowrap;">
                <div></div>
                <div><br></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #6a9955;">//&nbsp;Try&nbsp;to&nbsp;connect&nbsp;to&nbsp;postgres&nbsp;database.&nbsp;Log&nbsp;any&nbsp;errors&nbsp;to&nbsp;CloudWatch&nbsp;Logs.</span>
                </div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span
                        style="color: #d4d4d4;">&nbsp;{</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #9cdcfe;">$pdo</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span
                        style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;</span><span
                        style="color: #4ec9b0;">PDO</span><span style="color: #d4d4d4;">(</span></div>
                <div><span
                        style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #ce9178;">"pgsql:host=</span><span style="color: #9cdcfe;">${db_host}</span><span
                        style="color: #ce9178;">;</span></div>
                <div><span
                        style="color: #ce9178;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;port=65535;</span>
                </div>
                <div><span
                        style="color: #ce9178;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dbname=</span><span
                        style="color: #9cdcfe;">${db_name}</span><span style="color: #ce9178;">;</span></div>
                <div><span
                        style="color: #ce9178;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;user=</span><span
                        style="color: #9cdcfe;">${db_user}</span><span style="color: #ce9178;">;</span></div>
                <div><span
                        style="color: #ce9178;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;password=</span><span
                        style="color: #9cdcfe;">${db_auth}</span><span style="color: #ce9178;">;</span></div>
                <div><span
                        style="color: #ce9178;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sslmode=require"</span>
                </div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #6a9955;">//&nbsp;Set&nbsp;PDO&nbsp;error&nbsp;reporting&nbsp;to&nbsp;Exception.</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #9cdcfe;">$pdo</span><span style="color: #d4d4d4;">-&gt;</span><span
                        style="color: #dcdcaa;">setAttribute</span><span style="color: #d4d4d4;">(</span><span
                        style="color: #4ec9b0;">PDO</span><span style="color: #d4d4d4;">::ATTR_ERRMODE,&nbsp;</span><span
                        style="color: #4ec9b0;">PDO</span><span style="color: #d4d4d4;">::ERRMODE_EXCEPTION);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #6a9955;">//&nbsp;Connection&nbsp;attempt&nbsp;failed&nbsp;-&nbsp;log&nbsp;failure&nbsp;to&nbsp;CloudWatch&nbsp;Logs&nbsp;and&nbsp;exit.</span>
                </div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">catch</span><span
                        style="color: #d4d4d4;">&nbsp;(</span><span style="color: #4ec9b0;">PDOException</span><span
                        style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span
                        style="color: #d4d4d4;">)&nbsp;{</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">-&gt;</span><span
                        style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span
                        style="color: #ce9178;">'Problem&nbsp;connecting&nbsp;:&nbsp;'</span><span
                        style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$e</span><span
                        style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getMessage</span><span
                        style="color: #d4d4d4;">()]);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                        style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span
                        style="color: #ce9178;">"Unable&nbsp;to&nbsp;connect&nbsp;to&nbsp;host&nbsp;:&nbsp;</span><span
                        style="color: #9cdcfe;">${db_host}</span><span style="color: #ce9178;">."</span><span
                        style="color: #d4d4d4;">);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div>
                <div><br></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span></div>    
            </div>
          
            <p>
                In using a bogus port of 65535 while attempting to connect via PHP PDO to a PostgreSQL database
                the try / catch entered the catch block and generated a log to the php/database/errors/ logstream. The
                log from the AWS CloudWatch logs console is shown below.
            </p>

            <img src="assets/phpcloudwatchlogs_connect_error.png">

            <h1 class="blog-subheading">Log a succesfully run query</h1>
            
            <div
                class="blog-snippet"
                style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; line-height: 19px; margin: auto;">
                <div></div>
                <div><br></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #6a9955;">//&nbsp;Run&nbsp;an&nbsp;example&nbsp;query.&nbsp;Log&nbsp;successful&nbsp;queries&nbsp;to&nbsp;the&nbsp;sql&nbsp;log&nbsp;stream</span>
                </div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #6a9955;">//&nbsp;and&nbsp;any&nbsp;errors&nbsp;to&nbsp;the&nbsp;error&nbsp;log&nbsp;stream&nbsp;in&nbsp;CloudWatch&nbsp;Logs.</span>
                </div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span
                                style="color: #d4d4d4;">&nbsp;{</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #9cdcfe;">$qry</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span
                                style="color: #ce9178;">'</span><span style="color: #569cd6;">SELECT</span><span
                                style="color: #ce9178;">&nbsp;datname,&nbsp;datcollate&nbsp;</span><span
                                style="color: #569cd6;">FROM</span><span style="color: #ce9178;">&nbsp;pg_database;</span><span
                                style="color: #ce9178;">'</span><span style="color: #d4d4d4;">;</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #9cdcfe;">$stmt</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span
                                style="color: #9cdcfe;">$pdo</span><span style="color: #d4d4d4;">-&gt;</span><span
                                style="color: #dcdcaa;">query</span><span style="color: #d4d4d4;">(</span><span
                                style="color: #9cdcfe;">$qry</span><span style="color: #d4d4d4;">);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #9cdcfe;">$sql_logger</span><span style="color: #d4d4d4;">-&gt;</span><span
                                style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span
                                style="color: #ce9178;">"User:&nbsp;</span><span style="color: #9cdcfe;">${db_user}</span><span
                                style="color: #ce9178;">:&nbsp;Query:&nbsp;"</span><span
                                style="color: #d4d4d4;">&nbsp;&nbsp;=&gt;&nbsp;</span><span
                                style="color: #9cdcfe;">$qry</span><span style="color: #d4d4d4;">]);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #6a9955;">//&nbsp;Log&nbsp;failed&nbsp;query&nbsp;prepare&nbsp;statements&nbsp;to&nbsp;CloudWatch&nbsp;Logs.</span>
                </div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #c586c0;">catch</span><span style="color: #d4d4d4;">&nbsp;(</span><span
                                style="color: #4ec9b0;">PDOException</span><span style="color: #d4d4d4;">&nbsp;</span><span
                                style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">)&nbsp;{</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">-&gt;</span><span
                                style="color: #dcdcaa;">log</span><span style="color: #d4d4d4;">([</span><span
                                style="color: #ce9178;">'Problem&nbsp;with&nbsp;query&nbsp;'</span><span
                                style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #9cdcfe;">$e</span><span
                                style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getMessage</span><span
                                style="color: #d4d4d4;">()]);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
                                style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span
                                style="color: #ce9178;">"Unable&nbsp;to&nbsp;prepare&nbsp;query&nbsp;:&nbsp;</span><span
                                style="color: #9cdcfe;">${qry}</span><span style="color: #ce9178;">."</span><span
                                style="color: #d4d4d4;">);</span></div>
                <div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div>
                <div><br></div>
                <div></div>
            </div>

            <p>
                And this time the log goes to the php/database/sql/ logstream.
            </p>
                
            <img src="assets/phpcloudwatchlogs_query.png">

            <h1 class="blog-subheading">What can be learned from this article</h1>

            <p>
                Making use of AWS CloudWatch logs can simplify database error and query logging, especially
                if working collaboratively within a team which uses an AWS infrastructure. Instead of manually logging
                to files on a workstation's filesystem during development, the approach in this post emphasizes sending
                log data to CloudWatch logs where the logs can be analyzed further. AWS SDK's make logging simple
                when combined with a robust logging client such as the CloudWatchLogger from LegalThings. 
            </p>            

        </section>
        
    </div>

    </div>
    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    