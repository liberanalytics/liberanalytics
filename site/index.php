<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>LiberAnalytics</title>
    <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8">
    <link href="css/index.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/la.js"></script>
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/hdrftr.js"></script>

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
                            <img src="assets/la_logo_135.png">
                        </div>             
                    
                        <div class="featured-post-desc">      
                            
                            <a class="featured-post-hdrtxt" href="/blog?entry=id">PostgreSQL Database setup best practices on Ubuntu EC2 Instance</a>
                            <!-- PHP Pull header from RS. -->                              
                    
                            <p class="featured-post-desctxt"> <!-- PHP Pull short desc from RS. -->
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </p>                                            
                    
                        </div>

                    </div>

                    <div class="featured-post-attrs clear">
                        <span class="featured-post-date">October 12, 1991</span>
                        <a href="/blog?cat=rdbms" class="featured-post-cat">RDBMS</a> <!-- PHP Pull href & category frome RS. -->  
                    </div>
                
                </article> 

                <article class="featured-post clear">
                    
                    <div class="featured-post-content clear">

                        <div class="featured-post-img">
                            <img src="assets/la_logo_135.png">
                        </div>             
                    
                        <div class="featured-post-desc">      
                            
                            <a class="featured-post-hdrtxt" href="/blog?entry=id">Using relational theory to plot your sql queries</a>
                            <!-- PHP Pull header from RS. -->                              
                    
                            <p class="featured-post-desctxt"> <!-- PHP Pull short desc from RS. -->
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </p>                                            
                    
                        </div>

                    </div>

                    <div class="featured-post-attrs clear">
                        <span class="featured-post-date">September 25, 2024</span>
                        <a href="/blog?cat=rdbms" class="featured-post-cat">RDBMS</a> <!-- PHP Pull href & category frome RS. -->  
                    </div>
                
                </article> 

            </section> <!-- End posts_l -->

            <section id="posts_r">

                <h1 class="page-section-heading">Latest Posts</h1>

                <nav id="latest_posts_nav">

                    <a href="/blog?id=27" class="site-href">When sorting on database side is a good idea</a>
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

                </nav>

            </section> <!-- End posts_r -->                   

        </div> <!-- End posts_w -->

        <div id="mission_w">

            <section>       

                <h1 class="page-section-heading">Our Mission</h1>

                <div id="mission">
                    <p class="site-font-emphasis">
                        LiberAnalytics emphasizes the use of a creative mindset when approaching problems in the domain of data analytics.
                        Our goal is to show through multiple perspectives that working with data can be fascinating and enhanve an
                        individual's ability to solve problems. Most solutions to challenges in data are by their nature non-deterministic and
                        hence resolvable in many different ways. We hope that the content published will illuminate how to exercise a liber (free)
                        mind while working with data as well as inspire individuals to keep improving!
                    </p>
                </div>

            </section>

        </div> <!-- End mission_w -->

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>