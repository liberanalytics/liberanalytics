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
    <link href="css/posts.css" rel="stylesheet" type="text/css" charset="utf-8">
    <script type="text/javascript" src="js/LAStyles.js"></script>
    <script type="text/javascript" src="js/LAHdrFtr.js"></script>
    <script type="text/javascript" src="js/la.js"></script>

</head>

<body>

    <?php include_once('../src/site_header.php'); ?>

    <main>
        <h1 class="page-section-heading">Search for posts on Liber<em>Analytics</em></h1>
        <div id="posts_w">
            <div id="posts_form">
                <form id="posts" method="post" action="posts_handler.php">
                    <ul>
                        <li>
                            <label class="label-first">Post Category</label>
                            <select class="select-control">
                                <option class="control">Select Category</option>
                                
                                <?php
                                    $laData->openConn();
                                    $categories = $laData->getPostCategories();
                                    while ($category = $categories->fetch(PDO::FETCH_ASSOC))
                                    {
                                        echo "<option>{$category['post_category']}</option>";
                                    }
                                    $laData->closeConn();

                                ?>
                            </select>
                        </li>
                        <li>
                            <label class="label-nonfirst">Tags</label>
                            <input type="text" class="input-control">
                        </li>
                        <li>
                            <label class="label-nonfirst">Keywords</label>
                            <input type="text" class="input-control">
                        </li>
                    </ul>

                </form>
            </div>
            


        </div> <!-- End posts_w -->

    </main>

    <?php include_once('../src/site_footer.php'); ?>

</body>

</html>    