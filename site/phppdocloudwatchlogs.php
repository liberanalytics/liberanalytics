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
                to create and encrypt database connection parameters with AWS Key Management Service (KMS)
                in Python. We will then use PHP to read and decrypt the connection parameters in connecting to the Postgresql
                instance.
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
            
            <div class="blog-snippet">

                <code>

                    <ol>
                        <li><span class="blog-php-comment">/**</span></li>
                        <li><span class="blog-php-comment">&nbsp; * phppdocloudwatchlogs.php</span></li>
                        <li><span class="blog-php-comment">&nbsp; * </span></li>
                        <li><span class="blog-php-comment">&nbsp; * Creates a pdo connection to a postgresql database and runs a sample</span></li>
                        <li><span class="blog-php-comment">&nbsp; * query. Demonstrates how to use AWS Key Management Service keys to </span></li>
                        <li><span class="blog-php-comment">&nbsp; * decrypt database connection parameters from a configuration file on </span></li>
                        <li><span class="blog-php-comment">&nbsp; * filesystem. The configuration file must have been created first </span></li>
                        <li><span class="blog-php-comment">&nbsp; * with the aws_postgresql_config.py program. Shows how to loq errors </span></li>
                        <li><span class="blog-php-comment">&nbsp; * encountered while attempting to connect with PDO to CloudWatch Logs. </span></li>
                        <li><span class="blog-php-comment">&nbsp; * Also logs sql queries to CloudWatch Logs. </span></li>
                        <li><span class="blog-php-comment">&nbsp; */</span></li>
                    </ol>

                    <ol>
                        <li>
                            <span class="blog-php-keyword">require</span><span>(</span><!--
                             --><span class="blog-php-string">'../vendor/autoload.php'</span><span>);</span>
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-php-comment">// Create new CloudWatch Logs client / KMS client / CloudWatchLogger</span></li>
                        <li><span class="blog-php-comment">// instances.</span></li>
                        <li><span class="blog-php-keyword">try</span><span> {</span></li>

                        <li class="space">
                            <span class="blog-php-var">&nbsp;&nbsp;&nbsp;$aws_cwlogs_client</span><span> = </span><!--
                            --><span class="blog-php-keyword"> new </span><span class="blog-php-fx">Aws\CloudWatchLogs\CloudWatchLogsClient</span><!--
                            --><span>([</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'profile'</span><span> => </span><!--
                            --><span class="blog-php-string">'default'</span><span>,</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'region'</span><span> => </span><!--
                            --><span class="blog-php-string">'us-west-1'</span><span>,</span>
                        </li>                         
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'version'</span><span> => </span><!--
                            --><span class="blog-php-string">'latest'</span>
                        </li>
                        <li>
                            <span>&nbsp;&nbsp;&nbsp;]);</span>
                        </li>
                        
                        <li class="space">
                            <span class="blog-php-var">&nbsp;&nbsp;&nbsp;$aws_kms_client</span><span> = </span><!--
                            --><span class="blog-php-keyword"> new </span><span class="blog-php-fx">Aws\Kms\KmsClient</span><!--
                            --><span>([</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'profile'</span><span> => </span><!--
                            --><span class="blog-php-string">'default'</span><span>,</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'region'</span><span> => </span><!--
                            --><span class="blog-php-string">'us-west-1'</span><span>,</span>
                        </li>                         
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'version'</span><span> => </span><!--
                            --><span class="blog-php-string">'latest'</span>
                        </li>
                        <li>
                            <span>&nbsp;&nbsp;&nbsp;]);</span>
                        </li>
                        
                        <li class="space">
                            <span class="blog-php-var">&nbsp;&nbsp;&nbsp;$error_logger</span><span> = </span><!--
                            --><span class="blog-php-keyword"> new </span><span class="blog-php-fx">LegalThings\CloudWatchLogger</span><!--
                            --><span>([</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'aws'</span><span> => [</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'profile'</span><span> => </span><!--
                            --><span class="blog-php-string">'default'</span><span>,</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'region'</span><span> => </span><!--
                            --><span class="blog-php-string">'us-west-1'</span><span>,</span>
                        </li>                         
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'version'</span><span> => </span><!--
                            --><span class="blog-php-string">'latest'</span>
                        </li>
                        <li>
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'group_name'</span><span> => </span><!--
                            --><span class="blog-php-string">'sdk'</span><span>,</span>                            
                        </li>                        
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'stream_name'</span><span> => </span><!--
                            --><span class="blog-php-string">'php/database/errors/'</span>                            
                        </li>
                        <li><span>&nbsp;&nbsp;&nbsp;]);</span></li>     
                        
                        <li class="space">
                            <span class="blog-php-var">&nbsp;&nbsp;&nbsp;$sql_logger</span><span> = </span><!--
                            --><span class="blog-php-keyword"> new </span><span class="blog-php-fx">LegalThings\CloudWatchLogger</span><!--
                            --><span>([</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'aws'</span><span> => [</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'profile'</span><span> => </span><!--
                            --><span class="blog-php-string">'default'</span><span>,</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'region'</span><span> => </span><!--
                            --><span class="blog-php-string">'us-west-1'</span><span>,</span>
                        </li>                         
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'version'</span><span> => </span><!--
                            --><span class="blog-php-string">'latest'</span>
                        </li>
                        <li>
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span>
                        </li>
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'group_name'</span><span> => </span><!--
                            --><span class="blog-php-string">'sdk'</span><span>,</span>                            
                        </li>                        
                        <li>
                            <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'stream_name'</span><span> => </span><!--
                            --><span class="blog-php-string">'php/database/sql/'</span>                            
                        </li>
                        <li><span>&nbsp;&nbsp;&nbsp;]);</span></li>
                        <li><span>}</span></li>  
                        
                        <li class="space">
                            <span class="blog-php-keyword">catch</span><span> (Exception</span><span class="blog-php-var">$e</span><!--
                            --><span>) {</span>
                        </li>
                        <li>
                            <span class="blog-php-keyword">&nbsp;&nbsp;&nbsp;echo</span><span class="blog-php-var"> $e</span><span>-></span><!--
                            --><span class="blog-php-fx">getTraceAsString</span><span>();</span>
                        </li>
                        <li><span>}</span></li>
                                               
                    </ol>

                    <ol>
                        <li><span class="blog-php-comment">// Open and read the database config file.</span></li>
                        <li>
                            <span class="blog-php-var">$db_config_file</span><span> = </span><!--
                            --><span class="blog-php-string">'la_demo_db.ini'</span><span>;</span>
                        </li>
                        <li>
                            <span class="blog-php-fx">chmod</span><span>(</span><span class="php-blog-var">$db_config_file</span><!--
                            --><span>, </span><span class="blog-php-var">0440</span><span>);</span>
                        </li>
                        <li>
                            <span class="blog-php-var">$handle</span><span> = </span><span class="blog-php-fx">fopen</span><!--
                            --><span>(</span><span class="blog-php-var">$db_config_file</span><span>, </span><!--
                            --><span class="blog-php-string">'rb'</span><span>);</span>
                        </li>
                        <li>
                            <span class="blog-php-fx">chmod</span><span>(</span><span class="php-blog-var">$db_config_file</span><!--
                            --><span>, </span><span class="blog-php-var">0000</span><span>);</span>
                        </li>
                        <li>
                            <span class="blog-php-var">$binary_data</span><span> = </span><span class="blog-php-fx">fread</span><!--
                            --><span>(</span><span class="blog-php-var">$handle</span><span>, </span><!--
                            --><span class="blog-php-fx">filesize</span><span>(</span><span class="blog-php-var">$db_config_file</span><!--
                            --><span>));</span>
                        </li>  
                    </ol>
                    
                    <ol>
                        <li><span class="blog-php-comment">// Open and read the database config file.</span></li>
                        <li><span class="blog-php-keyword">try</span><span> {</span></li>

                            <li>
                                <span class="blog-php-var">&nbsp;&nbsp;&nbsp;$db_config_data</span><span> = </span><!--
                                --><span class="blog-php-var">$aws_kms_client</span><span>-></span><!--
                                --><span class="blog-php-fx">decrypt</span><span>([</span>
                            </li>
                            <li>
                                <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'CiphertextBlob' </span><span> => </span><!--
                                --><span class="blog-php-var">$binary_data</span><span>,</span>
                            </li>
                            <li>
                                <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'EncryptionAlgorithm'</span><span> => </span><!--
                                --><span class="blog-php-string">'SYMMETRIC_DEFAULT'</span><span>,</span>
                            </li>                         
                            <li>
                                <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'KeyId'</span><span> => </span><!--
                                --><span class="blog-php-string">'keyid'</span>
                            </li>
                            <li>
                                <span>&nbsp;&nbsp;&nbsp;]);</span>
                            </li>
                            <li><span>}</span></li>  
                            <li>
                                <span class="blog-php-keyword">catch</span><span> (Exception </span><span class="blog-php-var">$e</span><!--
                                --><span>) {</span>
                            </li>
                            <li>
                                <span class="blog-php-var">&nbsp;&nbsp;&nbsp;$error_logger</span><span>-></span><!--
                                --><span class="blog-php-fx">log</span><span>([</span>
                            </li>
                            <li>
                                <span class="blog-php-string">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Problem decrypting: '</span><span> => </span><!--
                                --><span class="blog-php-var">$e</span><span>-></span><!--
                                --><span class="blog-php-fx">getAwsErrorMessage</span><span>()</span>
                            </li>
                            <li><span>]);</span></li>                                                                       
                    </ol>

                </code>

            </div>

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    