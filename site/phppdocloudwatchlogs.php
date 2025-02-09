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

            <h1 class="blog-heading site-font-fancyl">Logging database errors and queries to Amazon Web Services CloudWatch Logs</h1>
            <h3 class="blog-date">December22, 2024</h3>
            <h2 class="blog-desc site-font-emph">
            Exemplifies a PHP way to use Amazon Web Services CloudWatch to log database errors and queries run. (Yes, PHP in 2025!).
            </h2>

        </header>        

        <section>

            <img src="assets/aws_cw_820.png" class="blog-img">

            <p style="margin: 10px 0 10px 0px;">
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

            <img src="assets/phppdocloudwatchlogs_logstreams.png">

            <h1 class="blog-subheading">Create CloudWatch Logs / KMS / CloudWatchLogger instances in PHP.</h1>

            <p>
                Use PHP composer to install the AWS PHP SDK and logging packages - note the cloudwatch-logger package 
                from legalthings is used here. Then create the loudWatch Logs / KMS / CloudWatchLogger instances in the PHP program.
            </p>
                        
            <div class="blog-snippet" style="color: rgb(204, 204, 204); background-color: rgb(31, 31, 31); font-family: 'Droid Sans Mono', 'monospace', monospace; font-weight: normal; font-size: 16px; line-height: 19px; overflow: auto; white-space: nowrap;"><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">/**</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;phppdocloudwatchlogs.php</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;Creates&nbsp;a&nbsp;pdo&nbsp;connection&nbsp;to&nbsp;a&nbsp;postgresql&nbsp;database&nbsp;and&nbsp;runs&nbsp;a&nbsp;sample</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;query.&nbsp;Demonstrates&nbsp;how&nbsp;to&nbsp;use&nbsp;AWS&nbsp;Key&nbsp;Management&nbsp;Service&nbsp;keys&nbsp;to&nbsp;</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;decrypt&nbsp;database&nbsp;connection&nbsp;parameters&nbsp;from&nbsp;a&nbsp;configuration&nbsp;file&nbsp;on</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;filesystem.&nbsp;The&nbsp;configuration&nbsp;file&nbsp;must&nbsp;have&nbsp;been&nbsp;created&nbsp;first</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;with&nbsp;the&nbsp;aws_postgresql_config.py&nbsp;program.&nbsp;Shows&nbsp;how&nbsp;to&nbsp;loq&nbsp;errors</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;encountered&nbsp;as&nbsp;well&nbsp;as&nbsp;queries&nbsp;run&nbsp;to&nbsp;CloudWatch&nbsp;Logs.</span></div><div><span style="color: #6a9955;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*/</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">require</span><span style="color: #d4d4d4;">&nbsp;(</span><span style="color: #ce9178;">'../vendor/autoload.php'</span><span style="color: #d4d4d4;">);</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Create&nbsp;CloudWatch&nbsp;Logs&nbsp;/&nbsp;KMS&nbsp;/&nbsp;CloudWatchLogger&nbsp;instances.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">try</span><span style="color: #d4d4d4;">&nbsp;{</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$aws_cwlogs_client</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;Aws\CloudWatchLogs\</span><span style="color: #4ec9b0;">CloudWatchLogsClient</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$aws_kms_client</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;Aws\Kms\</span><span style="color: #4ec9b0;">KmsClient</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><br></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$error_logger</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;LegalThings\</span><span style="color: #4ec9b0;">CloudWatchLogger</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'aws'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;[</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'group_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'sdk'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'stream_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'php/database/errors/'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #9cdcfe;">$sql_logger</span><span style="color: #d4d4d4;">&nbsp;=&nbsp;</span><span style="color: #569cd6;">new</span><span style="color: #d4d4d4;">&nbsp;LegalThings\</span><span style="color: #4ec9b0;">CloudWatchLogger</span><span style="color: #d4d4d4;">([</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'aws'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;[</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'profile'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'default'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'region'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'us-east-2'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'version'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'latest'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'group_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'sdk'</span><span style="color: #d4d4d4;">,</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #ce9178;">'stream_name'</span><span style="color: #d4d4d4;">&nbsp;=&gt;&nbsp;</span><span style="color: #ce9178;">'php/database/sql/'</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]);&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #6a9955;">//&nbsp;Report&nbsp;on&nbsp;any&nbsp;exceptions&nbsp;-&nbsp;exit&nbsp;program&nbsp;since&nbsp;all&nbsp;instances&nbsp;are&nbsp;needed.</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">catch</span><span style="color: #d4d4d4;">&nbsp;(</span><span style="color: #4ec9b0;">Exxception</span><span style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">)&nbsp;{</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #dcdcaa;">echo</span><span style="color: #d4d4d4;">&nbsp;</span><span style="color: #9cdcfe;">$e</span><span style="color: #d4d4d4;">-&gt;</span><span style="color: #dcdcaa;">getTraceAsString</span><span style="color: #d4d4d4;">();</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #c586c0;">exit</span><span style="color: #d4d4d4;">(</span><span style="color: #ce9178;">'Couldn</span><span style="color: #d7ba7d;">\'</span><span style="color: #ce9178;">t&nbsp;instantiate&nbsp;a&nbsp;needed&nbsp;AWS&nbsp;or&nbsp;Logger&nbsp;class.&nbsp;Exiting.'</span><span style="color: #d4d4d4;">);</span></div><div><span style="color: #d4d4d4;">&nbsp;&nbsp;&nbsp;&nbsp;}</span></div><div><br></div></div>

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    