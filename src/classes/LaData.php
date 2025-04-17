<?php

require_once('LaDb.php');

class LaData extends LaDB {

    function __construct()
    {
        parent::__construct();
    }

/**
    * 
    * @param bool
    * @param string
    * @param string
    * @param array
    * @param int
    *
    * @return PDO Statement
    */
    public function getPosts
    (
        bool $pxFeatured,
        string $pxPostName = NULL,        
        string $pxPostCategory = NULL,
        string $pxPostTags = NULL,
        string $pxPostKeywords = NULL,
        string $pxOrderBy = NULL,
        string $pxOrderByAscDesc = NULL,
        int $pxLimit = NULL
    )  
    {
        //
        $qry = 
        "SELECT

        la_posts.post_name,
        la_posts.post_category,
        la_posts.post_created,
        la_posts.short_desc,
        la_posts.long_desc,
        la_posts.featured,
        la_posts.post_img,
        la_posts.featured_img,
        la_posts.search_img
        
        FROM liberanalytics.fx_get_posts";

        return $this->fetchData($qry, func_get_args(), $this->getResultsetAsRows());
    }

    /**
    *
    * @return PDO Statement
    */
    public function getPostCategories($pxPostCategory = NULL)
    {
        $qry =
        "SELECT

        post_category

        FROM refdata.fx_get_post_categories()
        
        ORDER BY post_category;

        ";

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    }    


}

?>