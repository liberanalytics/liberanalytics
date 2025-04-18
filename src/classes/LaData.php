<?php

require_once('LaDataParams.php');
require_once('LaConfig.php');

class LaData extends LaDataParams {

    private const SCHEMA_KEY = 'schema';
    private const FX_KEY = 'fx';    

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    public function crudData(string $pxFunctionName, array $pxParameterData, string $pxResultsetType, string $pxFetchType)
    {
        $laConfig = new LaConfig();
        $dbFxSpecs = $laConfig->getConfigAttr('data', 'db_to_client_fx', 'client_fx', $pxFunctionName);
        $dbSchema = $dbFxSpecs[self::SCHEMA_KEY];
        $dbFx = $dbFxSpecs[self::FX_KEY];
        $parameters = $this->convertParams($dbSchema, $dbFx, $pxParameterData);

    }

    public function getPosts
    (
        bool $pxFeatured,
        string $pxPostName = NULL,        
        string $pxPostCategory = NULL,
        string $pxPostTags = NULL,
        string $pxPostKeywords = NULL,
        string $pxOrderBy = NULL,
        string $pxOrderByAscDesc = NULL,
        int $pxLimit = NULL,
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchStyle = self::FETCHSTYLE_ASSOC
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

        return $this->fetchData($qry, func_get_args(), $pxResultsetType, $pxFetchStyle);
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

        return $this->fetchData($qry, func_get_args(), self::RESULTSET_ROWS, self::FETCHSTYLE_ASSOC);

    }    


}

?>