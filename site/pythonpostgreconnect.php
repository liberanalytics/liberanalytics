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
                We will use a symmetric AWS KMS key to encrypt and decrypt database credentials.
            </p>

            <h1 class="blog-subheading">Requirements</h1>

            <ul>
                <li>Python 3 (preferably running on Ubuntu)</li>
                <li>Python libraries: psycopg2, boto3</li>
                <li>AWS: Key Management Service (KMS) Customer managed symmetric key</li>
                <li>Postgresql database cluster (needs to accept TCP/IP connections)</li>
            </ul>

            <h1 class="blog-subheading">Get database connection parameters</h1>

            <p>
                First we will need to know the database host and database name which will be used to create a config file
                with .ini extension in the user's home directory. Then we will prompt for the user name and password using
                Python's getpass to avoid echoing back the data input by the user. We'll use our AWS KMS key to perform
                encryption of the user name and password. Lastly the encrypted data will be written to the config file
                w/ permissions set to non-readable, non-writable, and non-executable. The following code shows how we can
                do the aforementioned in Python.
            </p>

            <div class="blog-python-snippet">

                <code>

                    <ol>
                        <li><span class="blog-python-comment"># --------------------------------------------------------------</span></li>
                        <li><span class="blog-python-comment"># aws_postgresql_connect_init.py</span></li>
                        <li><span class="blog-python-comment">#</span></li>
                        <li><span class="blog-python-comment"># Creates a .ini file containing encrypted credentials used</span></li>
                        <li><span class="blog-python-comment"># to connect to an AWS EC2 hosted postgresql database.</span></li>
                        <li><span class="blog-python-comment"># --------------------------------------------------------------</span></li>
                    </ol>

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
                        <li><span class="blog-python-comment"># Prompt for the connection params.</span></li>
                        <li>
                            <span>db_host = </span><span class="blog-python-function">getpass(</span><!--
                            --><span class="blog-python-string">'Please enter database host:'</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                        <li>
                            <span>db_name = </span><span class="blog-python-function">getpass(</span><!--
                            --><span class="blog-python-string">'Please enter database name:'</span><!--
                            --><span class="blog-python-function">)</span>                   
                        </li>
                        <li>
                            <span>db_user = </span><span class="blog-python-function">getpass(</span><!--
                            --><span class="blog-python-string">'Please enter database user name:'</span><!--
                            --><span class="blog-python-function">)</span>                   
                        </li>
                        <li>
                            <span>db_auth = </span><span class="blog-python-function">getpass(</span><!--
                            --><span class="blog-python-string">'Please enter user password:'</span><!--
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
                        <li><span class="blog-python-comment"># Concat + delimit params, convert to bytes, then base64 encode.</span></li>

                        <li>
                            <span>delim = </span><span class="blog-python-function">chr(</span>7</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                        <li><span>params = db_host + delim + db_name + delim + db_user + delim + db_auth</span></li>
                        <li>
                            <span>byte_params = params.<span class="blog-python-function">encode(</span></span><!--
                            --><span class="blog-python-string">'utf-8'</span><span class="blog-python-function">)</span>
                        </li>
                        <li>
                            <span>encode_params = </span><span class="blog-python-builtin">base64</span><!--
                            --><span class="blog-python-function">.b64encode</span><span>(byte_params)</span>
                        </li>                        
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Encrypt the params.</span></li>
                        <li><span>encryption_data = aws_kms.</span><span class="blog-python-function">encrypt(</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>KeyId = f'{key_id}',</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>Plaintext = encode_params,</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>EncryptionAlgorithm = </span><span class="blog-python-string">'SYMMETRIC_DEFAULT'</span></li>
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
                            &nbsp;&nbsp;&nbsp;&nbsp;<span>file.</span><span class="blog-python-function">write(</span><!--
                            --><span>encryption_data[</span><span class="blog-python-string">'CiphertextBlob'</span><!--
                            --><span>]</span><span class="blog-python-function">)</span>
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

            <h1 class="blog-subheading">Connecting to the postgresql database</h1>

            <p>
                Now that we have the connection config file created we are ready to connect to the postgresql
                database cluster and begin sql'ing away. The below aws_postgresql_connect.py program reads the
                encrypted data from the config file created by the aws_postgresql_connect_init.py program then
                decrypts the byte data with the same AWS KMS key. It is important to include the encryption
                algorithm (EncryptionAlgorithm='RSAES_OAEP_SHA_256') in the decrypt call if using an
                asymmetric key in AWS KMS. In this case we are using a symmetric key so the EncryptionAlgorithm='SYMMETRIC_DEFAULT'
                encryption algorithm property isn't needed but we still include it for posterity sake. 
                The data read is then split by delimiter to derive user name/password
                and the config file permissions are disabled. Finally we use the imported psycopg2 libarary to connect
                to our postgresql cluster in AWS EC2 and run a sample query.
            </p>
            
            <div class="blog-python-snippet">

                <code>

                    <ol>
                        <li><span class="blog-python-comment"># --------------------------------------------------------------</span></li>
                        <li><span class="blog-python-comment"># aws_postgresql_connect.py</span></li>
                        <li><span class="blog-python-comment">#</span></li>
                        <li><span class="blog-python-comment"># Connects to an AWS EC2 hosted postgresql database by reading</span></li>
                        <li><span class="blog-python-comment"># and decrypting credentials stored in a config file. Dependent</span></li>
                        <li><span class="blog-python-comment"># on the aws_postgresql_connect_init.py having been run first.</span></li>
                        <li><span class="blog-python-comment"># --------------------------------------------------------------</span></li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Import required modules.</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">pwd</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">sys</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">os</span></li>
                        <li><span class="blog-python-keyword">import </span><span class="blog-python-builtin">psycopg2</span></li>
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
                        <li><span class="blog-python-comment"># Make config file readable.</span></li>
                        <li>
                            <span class="blog-python-builtin">os</span><span>.</span><span class="blog-python-function">chmod(</span><!--
                            --><span>config_file, 440</span><span class="blog-python-function">)</span>
                        </li>
                    </ol>             

                    <ol>
                        <li><span class="blog-python-comment"># Get the connection credentials from config file.</span></li>
                        <li>
                            <span class="blog-python-keyword">with </span><span class="blog-python-function">open(</span><!--
                            --><span>config_file, </span><span class="blog-python-string">'rb'</span><!--
                            --><span class="blog-python-function">)</span><span class="blog-python-keyword"> as</span><!--
                            --><span> file:</span>
                        </li>
                        <li>
                            &nbsp;&nbsp;&nbsp;&nbsp;<!--
                            --><span>credentials_bytes = file.</span><span class="blog-python-function">read()</span>
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Reset config file perms.</span></li>
                        <li>
                            <span class="blog-python-builtin">os</span><span>.</span><span class="blog-python-function">chmod(</span><!--
                            --><span>config_file, 000</span><span class="blog-python-function">)</span>
                        </li>
                    </ol>                      

                    <ol>
                        <li><span class="blog-python-comment"># Decrypt the connection credentials.</span></li>
                        <li><span>credentials_decrypt = client.</span><span class="blog-python-function">decrypt(</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>CiphertextBlob = credentials_bytes,</span></li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>KeyId = f'{key_id}',</span></li>                        
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<span>EncryptionAlgorithm = </span><span class="blog-python-string">'SYMMETRIC_DEFAULT'</span></li>
                        <li><span class="blog-python-function">)</span></li>
                    </ol> 
                    
                    <ol>
                        <li><span class="blog-python-comment"># Decode the credentials and split by delimiter.</span></li>
                        <li><span>delimiter = </span><span class="blog-python-function">chr(</span>7</span><!--
                        --><span class="blog-python-function">)</span>
                        </li>
                        <li>
                            <span>credentials_decode = </span><span class="blog-python-builtin">base64</span><!--
                            --><span class="blog-python-function">.b64decode</span><span>(credentials_decrypt[</span><!--
                            --><span class="blog-python-string">'Plaintext'</span><span>])</span>
                        </li>
                        <li><span>credentials = </span><span class="blog-python-builtin">bytes</span><span>.</span><!--
                        --><span class="blog-python-function">decode(</span><span>credentials_decode, </span><!--
                        --><span class="blog-python-string">'utf-8'</span><span class="blog-python-function">)</span>
                        </li>
                        <li>
                            <span>db_data = credentials.</span><span class="blog-python-function">split(</span>delimiter</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                        <li><span>db_host = db_data[0]</span></li>
                        <li><span>db_name = db_data[1]</span></li>
                        <li><span>db_user = db_data[2]</span></li>
                        <li><span>db_auth = db_data[3]</span></li>
                    </ol>


                    
                    <ol>
                        <li><span class="blog-python-comment"># Form a connection string & connect.</span></li>
                        <li><span>connection_string = \</span></li>
                        <li><span class="blog-python-string">" host="</span><span> + db_host + \</span></li>
                        <li><span class="blog-python-string">" dbname"</span><span> + db_name + \</span></li>
                        <li><span class="blog-python-string">" user="</span><span> + db_user + \</span></li>
                        <li><span class="blog-python-string">" password="</span><span> + db_auth + \</span></li>
                        <li><span class="blog-python-string">" port=5432"</span>
                        <li>
                            <span>conn = </span><span class="blog-python-builtin">psycopg2</span><!--
                            --><span>.</span><span class="blog-python-function">connect(</span><span>connection_string</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Example query.</span></li>
                        <li><span>cur = conn.</span><span class="blog-python-function">cursor()</span></li>
                        <li>
                            <span>cur.</span><span class="blog-python-function">execute(</span><!--
                            --><span class="blog-python-string">'SELECT CURRENT_DATE;'</span><!--
                            --><span class="blog-python-function">)</span>
                        </li>
                        <li><span>rs = cur.</span><span class="blog-python-function">fetchall()</span></li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Iterate through resultset.</span></li>
                        <li>
                            <span class="blog-python-keyword">for</span><span> row </span><!--
                            --><span class="blog-python-keyword">in</span><span> rs: </span>
                        </li>
                        <li>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="blog-python-function">print(</span><span>row</span><!--
                        --><span class="blog-python-function">)</span>
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Close cursor and connection.</span></li>
                        <li><span>cur.</span><span class="blog-python-function">close()</span></li>
                        <li><span>conn.</span><span class="blog-python-function">close()</span></li>
                    </ol>
                    

                </code>

            </div>

            <h1 class="blog-subheading">Key Takeaways</h1>

            <p>
                In this article we have connected to a postgresql database cluster hosted in AWS EC2
                without storing database credentials or encryption keys anywhere on the filesystem. Although
                the code presented could be refactored to eliminate redundancies or abstracted into a class, 
                the key points listed below could help in securely connecting to databases.
            </p>

            <ul>
                <li>Minimize visibility and access to database credentials</li>
                <li>If hosting in a cloud service provider, make use of symmetric keys to protect
                    sensitive data such as user names or passwords
                </li>
                <li>Use encryption when storing database credentials on filesystem</li>
                <li>Try to avoid storing encryption keys or plain text user names/passwords</li>
            </ul>

        </section>
        
    </div>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    