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