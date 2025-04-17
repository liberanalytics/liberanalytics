<?php

require_once('LaDataDb.php');

class LaData extends LaDataDB {

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

        $args = func_get_args();
        //
        $qry = 
        "SELECT

        post_name,
        post_category,
        post_created,
        short_desc,
        long_desc,
        featured,
        post_img,
        featured_img,
        search_img
        
        FROM liberanalytics.fx_get_posts";

        return $this->fetchData($qry, func_get_args(), $this->getResultsetAsRows(), $this->getFetchStyleAssoc());
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

        FROM refdata.fx_get_post_categories";

        return $this->fetchData($qry, func_get_args(), $this->getResultsetAsRows(), $this->getFetchStyleAssoc());

    }    


}

?>