<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/index.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/LAHdrFtr.js"></script>
    <script type="text/javascript" src="js/la.js"></script>

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

    <main>

        <div id="posts_w">

            <section id="posts_l">

                <h1 class="page-section-heading">Featured</h1>

                <article class="featured-post clear">
                    
                    <div class="featured-post-content clear">

                        <div class="featured-post-img">
                            <img src="assets/whydata_195.png">
                        </div>             
                    
                        <div class="featured-post-desc">      
                            
                            <a class="featured-post-hdrtxt" href="/blog?entry=id">Why do we need Data?</a>
                            <!-- PHP Pull header from RS. -->                              
                    
                            <p class="featured-post-desctxt"> <!-- PHP Pull short desc from RS. -->
                                Explores the importance of data and information in decision making.
                            </p>                                            
                    
                        </div>

                    </div>

                    <div class="featured-post-attrs clear">
                        <span class="featured-post-date">November 15, 2024</span>
                        <a href="/blog?cat=rdbms" class="featured-post-cat">Analytics</a> <!-- PHP Pull href & category frome RS. -->  
                    </div>
                
                </article> 

                <article class="featured-post clear">
                    
                    <div class="featured-post-content clear">

                        <div class="featured-post-img">
                            <img src="assets/pythonpostgreconnect195.png">
                        </div>             
                    
                        <div class="featured-post-desc">      
                            
                            <a class="featured-post-hdrtxt" href="/blog?entry=id">Connecting Python to a Postgresql database</a>
                            <!-- PHP Pull header from RS. -->                              
                    
                            <p class="featured-post-desctxt"> <!-- PHP Pull short desc from RS. -->
                                Discover how to connect Python to a Postgresql database without storing credentials or keys on filesystem.
                            </p>                                            
                    
                        </div>

                    </div>

                    <div class="featured-post-attrs clear">
                        <span class="featured-post-date">December 15, 2024</span>
                        <a href="/blog?cat=rdbms" class="featured-post-cat">RDBMS</a> <!-- PHP Pull href & category frome RS. -->  
                    </div>
                
                </article>

                <article class="featured-post clear">
                    
                    <div class="featured-post-content clear">

                        <div class="featured-post-img">
                            <img>
                        </div>             
                    
                        <div class="featured-post-desc">      
                            
                            <a class="featured-post-hdrtxt" href="/blog?entry=id">Store database logs in Amazon Web Services CloudWatch Logs</a>
                            <!-- PHP Pull header from RS. -->                              
                    
                            <p class="featured-post-desctxt"> <!-- PHP Pull short desc from RS. -->
                                Exemplifies a PHP way to use Amazon Web Services CloudWatch Logs to organize database queries and errors. (Yes, PHP in 2025!) .
                            </p>                                           
                    
                        </div>

                    </div>

                    <div class="featured-post-attrs clear">
                        <span class="featured-post-date">December 22, 2024</span>
                        <a href="/blog?cat=rdbms" class="featured-post-cat">Cloud Computing</a> <!-- PHP Pull href & category frome RS. -->  
                    </div>
                
                </article>                 

            </section> <!-- End posts_l -->

            <section id="posts_r">

                <h1 class="page-section-heading">Latest Posts</h1>

                <nav id="latest_posts_nav">

                    <a href="/blog?id=27" class="site-href">When sorting on database side is a good idea</a>
                    <a href="/blog?id=27" class="site-href">Source control for databases?</a>
                    <a href="/blog?id=28" class="site-href">Should primary keys be used in data warehouses?</a>
                    <a href="/blog?id=29" class="site-href">MS Excel VBA Ado to read resultsets into .xlsx</a>
                    <a href="/blog?id=30" class="site-href">Examples of SQL Unit Tests</a>
                    <a href="/blog?id=31" class="site-href">A simple data pipeline</a>
                    <a href="/blog?id=32" class="site-href">Using relational databases in defining program logic</a>
                    <a href="/blog?id=33" class="site-href">Importance of explain / exucution plans</a>
                    <a href="/blog?id=34" class="site-href">What is a Master Data Management plan?</a>
                    <a href="/blog?id=35" class="site-href">Use the FWGHSO mnemonic to create efficient SQL select queries</a>
                    <a href="/blog?id=36" class="site-href">Delete then Insert - Don't Update</a>
                    <a href="/blog?id=37" class="site-href">When a data warehouse is overkill</a>
                    <a href="/blog?id=38" class="site-href">Why use the exists predicate?</a>
                    <a href="/blog?id=39" class="site-href">Leveraging Linux-based bash commands to massage flat file data</a>
                    <a href="/blog?id=40" class="site-href">Structuring your SQL code so it is readable and intuitive</a>

                </nav>

            </section> <!-- End posts_r -->                   

        </div> <!-- End posts_w -->

        <section id="mission_w">

            <h1 class="page-section-heading">Our Mission</h1>

            <div id="mission" class="site-block-std">

                <p class="site-font-emph">
                    <em class="site-font-fancyl">LiberAnalytics</em>' goal is to show that having a creative mindset while working with data can be fascinating and enhance an
                    individual's ability to solve problems. Most solutions to challenges in data are by their nature non-deterministic and
                    are resolvable in many different ways. Hence, we aspire to consider multiple possible data-driven techniques when approaching problems in the domain of data analytics.
                    We hope that the content published will illuminate how to exercise a <em class="site-font-fancyl">liber</em> (free)
                    mind while working with data as well as inspire individuals to keep improving!
                </p>

                <div id="mission_graphic">
                    <img src="assets/mission.png">
                </div>                    

            </div> <!-- End mission -->

        </section>

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>