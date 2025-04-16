<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");

    $laData = new LaData();
    $laVx = new LaVx();
    
    $laData->openConn();

    // $inputData = json_decode(file_get_contents('php://input'), true);

    $inputData = [
        'post_category' => 'Analytics',
        'tags' => 'sql rdbms theory',
        'keywords' => ''
    ];

    //
    if (!empty($inputData['post_category']))
    {
        $postCategories = $laData->getPostCategories(NULL);

        while ($postCategory = $postCategories->fetch(PDO::FETCH_ASSOC))
        {
            if ($inputData['post_category'] == $postCategory['post_category'])
            {
                return true;
            }
            else
            {
                http_response_code(400);
                echo json_encode(['error' => 'Post Category is invalid.']);
                return;
            }
        }
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
        http_response_code(400);
        echo json_encode(['[error' => "{$errorIdentifier} " . $error->getMessage()]);
        return;
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