<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");

    $laData = new LaData();
    $laVx = new LaVx();

    // $inputData = json_decode(file_get_contents('php://input'), true);

    $inputData = [
        'post_category' => 'Analytics',
        'tags' => 'sql rdbms theory',
        'keywords' => ''
    ];

    try
    {
        if (!empty($inputData['tags']))
        {
            $errorIdentifier = "Tags";
            $tagsList = $laVx->getSzVxList($inputData['tags'], "/([\s,]){1,}/", ",", [".", "?", "/", "/", "!", "=", "+", "*"], "");
            $tagsList = $laVx->getPostGreArray($tagsList);
        }
        if (!empty($inputData['keywords']))
        {
            $errorIdentifier = "Keywords";
            $keywordsList = $laVx->getSzVxList($inputData['keywords'], "/[\s,]/", ",", [".", "?", "/", "/", "!", "=", "+", "*"], "");
            $keywordsList = $laVx->getPostGreArray($keywordsList);
        }
    }
    catch(Error $error)
    {
        echo "Error: {$errorIdentifier} " . $error->getMessage();
    }

    $laData->openConn();

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

    while ($post = $posts->fetch(PDO::FETCH_ASSOC))
    {
        echo $post['post_name'];
    }

    $laData->closeConn();

?>