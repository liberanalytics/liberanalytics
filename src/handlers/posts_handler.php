<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");

    $laData = new LaData();
    $laVx = new LaVx();

    // $inputData = json_decode(file_get_contents('php://input'), true);

    $inputData = [
        'post_category' => 'Analytics',
        'tags' => 'theory',
        'keywords' => 'ec2 php python'
    ];

    try
    {
        $errorIdentifier = "Tags";
        $tagsList = $laVx->getSzVxList($inputData['tags'], "/([\s,]){1,}/", ",", [".", "?", "/", "/", "!", "=", "+", "*"], "");
        $errorIdentifier = "Keywords";
        $keywordsList = $laVx->getSzVxList($inputData['keywords'], "/[\s,]/", ",", [".", "?", "/", "/", "!", "=", "+", "*"], "");
    }
    catch(Error $error)
    {
        echo "Error: {$errorIdentifier} " . $error->getMessage();
    }

?>