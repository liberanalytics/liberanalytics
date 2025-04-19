<?php

require_once('LaDataPxs.php');
require_once('LaConfig.php');

class LaData extends LaDataPxs {

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
        //
        $laConfig = new LaConfig();
        $dbFxSpecs = $laConfig->getConfigAttr('data', 'db_to_client_fx', 'client_fx', $pxFunctionName);
        $dbSchema = $dbFxSpecs[self::SCHEMA_KEY];
        $dbFx = $dbFxSpecs[self::FX_KEY];
        $parameterData = $this->convertPxs($dbSchema, $dbFx, $pxParameterData);

        //
        $crudData = call_user_func([$this, $pxFunctionName], $parameterData);
        return $crudData;

    }

    public function getPosts
    (
        array $pxParameters,
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchStyle = self::FETCHSTYLE_ASSOC
    )  
    {


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

        return $this->fetchData($qry, $pxParameters, $pxResultsetType, $pxFetchStyle);
    }

    /**
    *
    * @return PDO Statement
    */
    public function getPostCategories
    (
        array $pxParameters,
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchStyle = self::FETCHSTYLE_ASSOC
    )
    {
        $qry =
        "SELECT

        post_category

        FROM refdata.fx_get_post_categories";

        return $this->fetchData($qry, $pxParameters, $pxResultsetType, $pxFetchStyle);

    }    


}

?>