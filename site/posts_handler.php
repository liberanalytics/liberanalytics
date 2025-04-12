<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
//include('../src/handlers/posts_handlers.php');

if (isset($_POST))
{

    $data = file_get_contents('php://input');
    $json = json_decode($data, true);
    echo json_encode($json);
}

?>