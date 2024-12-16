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
                First we will need to know the database host and database name which will be used to create a config file
                with .ini extension in the user's home directory. Then we will prompt for the user name and password using
                Python's getpass to avoid echoing back the data input by the user. We'll use our AWS KMS key to perform
                encryption of the user name and password. Finally the encrypted data will be written to the config file
                w/ permissions set to non-readable, non-writable, non-executable on the config file.
            </p>

            <div class="blog-python-snippet">

                <code>

                    <ol>
                        <li><span class="blog-python-comment"># Import required modules.</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">pwd</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">sys</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">os</span></li>
                        <li>
                            <span class="blog-python-keyword">from </span><span class="blog-python-builtin">getpass</span>
                            <span class="blog-python-keyword">import </span><span class="blog-python-builtin">getpass</span>
                        </li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">boto3</span></li>
                    </ol>

                    <ol>    
                        <li><span class="blog-python-comment"># Instantiate an AWS KMS client instance.</span></li>   
                        <li>
                            <span>client = </span><span class="blog-python-builtin">boto3</span><!--
                            --><span class="blog-python-function">.client(</span><span class="blog-python-string">'kms'</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                    </ol>
                    
                    <ol>    
                        <li><span class="blog-python-comment"># KMS Key Id.</span></li>
                        <li><span>key_id = </span><span class="blog-python-string">'keyid'</span></li>
                    </ol>
                    
                    <ol>    
                        <li><span class="blog-python-comment"># Get the database host & database name.</span></li>
                        <li>
                            <span>db_host = </span><span class="blog-python-function">input(</span><!--
                            --><span class="blog-python-string">'Please enter database host:'</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                        <li>
                            <span>db_name = </span><span class="blog-python-function">input(</span><!--
                            --><span class="blog-python-string">'Please enter database name:'</span><!--
                            --><span class="blog-python-function">)</span>                   
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Config file.</span></li>
                        <li>
                            <span>config_dir = [</span><span class="blog-python-string">'HOME'</span><span>] +</span>
                            <span class="blog-python-string">'/'</span><span> + </span><span class="blog-python-string">'conn'</span>
                            <span>+</span><span class="blog-python-string">'/'</span><span> + db_host</span>
                        </li>
                        <li>
                            <span>config_file = config_dir + </span><span class="blog-python-string">'/'</span><span> + db_name + </span>
                            <span class="blog-python-string">.ini</span>
                        </li>                        
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Store connection credentials delimited by a BEL utf8 char.</span></li>

                        <li>
                            <span>delimiter = </span><span class="blog-python-builtin">bytes</span><!--
                            --><span>(</span><span class="blog-python-function">chr(</span>7</span><!--
                            --><span class="blog-python-function">)</span><span>, </span><!--
                            --><span class="blog-python-string">'utf-8'</span><span class="blog-python-function">)</span>
                        </li>

                        <li><span>credentials_bytes = \</span></li>
                        <li>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="blog-python-builtin">bytes</span><!--
                            --><span class="blog-python-function">(getpass(</span><!--
                            --><span class="blog-python-string">'Please enter database user name:'</span><!--
                            --><span class="blog-python-function">)</span><span>, </span><!--
                            --><span class="blog-python-string">'utf-8'</span><span class="blog-python-function">)</span><!--
                            --><span> + \</span>
                        </li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>delimiter + \</span></li>
                        <li>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="blog-python-builtin">bytes</span><!--
                            --><span class="blog-python-function">(getpass(</span><!--
                            --><span class="blog-python-string">'Please enter database user password:'</span><!--
                            --><span class="blog-python-function">)</span><span>, </span><!--
                            --><span class="blog-python-string">'utf-8'</span><span class="blog-python-function">)</span><!--
                            --><span> + \</span>                            
                        </li>
                        
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Encrypt credentials.</span></li>
                        <li><span>credentials_encrypt = client.</span><span class="blog-python-function">encrypt(</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>KeyId = f'{key_id}',</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>Plaintext = credentials_bytes,</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>EncryptionAlgorithm = </span><span class="blog-python-string">'RSAES_OAEP_SHA_256'</span></li>
                        <li><span class="blog-python-function">)</span></li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Write connection credentials.</span></li>
                        <li>
                            <span class="blog-python-keyword">with </span><span class="blog-python-function">open(</span><!--
                            --><span>config_file, </span><span class="blog-python-string">'wb'</span><!--
                            --><span class="blog-python-function">)</span><span class="blog-python-keyword"> as</span><!--
                            --><span> file:</span>
                        </li>
                        <li>
                            <span>file.</span><span class="blog-python-function">write(</span><span>credentials_encrypt[</span><!--
                            --><span class="blog-python-string">'CiphertextBlob'</span><span>]</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># chmod on config file to make unreadable/unwritable.</span></li>
                        <li>
                            <span class="blog-python-builtin">os</span><span>.</span><span class="blog-python-function">chmod(</span><!--
                            --><span>config_file, 000</span><span class="blog-python-function">)</span>
                        </li>

                    </ol>
                    
                </code>
            </div>

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    