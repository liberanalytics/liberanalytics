<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //
    header('Content-Type: application/json');
    $posts = include(dirname(__DIR__, 2) . "/" . "src/api/postsearch.php");
    $postsJSON = json_encode($posts);
    echo($postsJSON);
}

?>
