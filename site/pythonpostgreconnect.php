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
                First we will need to know the database host and database name which will be used to create
                a config file with .ini extension in the user's home directory. 
            </p>

            <div class="blog-python-snippet">

                <code>

                    <ol>
                        <li><span class="blog-python-comment"># Import required modules.</span></li>
                        <li><span class="blog-python-import">import </span><span>pwd</span></li>
                        <li><span class="blog-python-import">import </span><span>sys</span></li>
                        <li><span class="blog-python-import">import </span><span>os</span></li>
                        <li>
                            <span class="blog-python-import">from </span><span>getpass</span>
                            <span class="blog-python-import">import </span><span>getpass</span>
                        </li>
                        <li><span class="blog-python-import">import </span><span>boto3</span></li>
                    </ol>
                    
                    <ol>    
                        <li><span class="blog-python-comment"># Get the database host & database name.</span></li>
                        <li>
                            <span>db_host = </span><span class="blog-python-function">input(</span>
                            <span class="blog-python-string">'Please enter database host:'</span>
                            <span class="blog-python-function">)</span>
                        </li>
                        <li>
                            <span>db_name = </span><span class="blog-python-function">input(</span>
                            <span class="blog-python-string">'Please enter database name:'</span>
                            <span class="blog-python-function">)</span>                   
                        </li>
                        </li>
                    </ol>

                    <ol>
                        <li><span class="blog-python-comment"># Config file.</li>
                        <li>
                            <span>config_dir = [</span><span class="blog-python-string">'HOME'</span><span>] +</span>
                            <span class="blog-python-string">'/'</span><span> + </span><span class="blog-python-string">'conn'</span>
                            <span>+</span><span class="blog-python-string">'/'</span><span> + db_host</span>
                            
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