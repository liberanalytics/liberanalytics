<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");

    $laData = new LaData();
    $laVx = new LaVx();    
    $laData->openConn();

    $validationErrorMsg = "";

    // $inputData = json_decode(file_get_contents('php://input'), true);

    $inputData = [
        'post_category' => 'Analytics',
        'tags' => 'sql rdbms theor%y',
        'keywords' => ''
    ];

    //
    if (!$laVx->vxValidateRefdata($laData, 'postsearch', 'post_category', $inputData['post_category']))
    {
        $httpResponseCode = http_response_code(400);
        $validationErrorMsg = (['error' => "Post Category is invalid."]);
    }

    //
    try
    {
        if (!empty($inputData['tags']))
        {
            $errorIdentifier = "Tags";
            $tagsList = $laVx->getSzVxArray($inputData['tags'], "/([\s,]){1,}/", ",", [".", "?", "/", "/", "!", "=", "+", "*"], "");
            $tagsList = $laVx->getPostGreList($tagsList);
        }
        if (!empty($inputData['keywords']))
        {
            $errorIdentifier = "Keywords";
            $keywordsList = $laVx->getSzVxArray($inputData['keywords'], "/[\s,]/", ",", [".", "?", "/", "/", "!", "=", "+", "*"], "");
            $keywordsList = $laVx->getPostGreList($keywordsList);
        }
    }
    catch(Error $error)
    {
        $httpResponseCode = http_response_code(400);
        $validationErrorMsg = (['error' => "{$errorIdentifier} " . $error->getMessage()]);
    }

    if(empty($validationErrorMsg))
    {
        $posts = $laData->getPosts(
            TRUE,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL
        );
    
        foreach($posts as $post)
        {
            echo $post['post_name'];
        }
    }
    else
    {
        echo $validationErrorMsg['error'];
    }

    

    $laData->closeConn();

?>