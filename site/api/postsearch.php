<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    include(dirname(dirname(__DIR__)) . "/" . "src/handlers/postsearch.php");
}

?>
