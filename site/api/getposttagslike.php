<?php

    header('Content-Type: application/json');

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    /**
     * 
     */
    if ($_SERVER['REQUEST_METHOD'] !== 'GET')
    {
        http_response_code(405);
        echo json_encode(['error' => 'Only GET permitted']);
        exit;
    }

    $postTagTerm = $_GET['post_tag_term'] ?? null;

    if ($postTagTerm === null || trim($postTagTerm) === '')
    {
        echo json_encode([]);
        exit;
    }

    require_once(dirname(__DIR__, 2) . "/" . "src/api/getposttagslike.php");

    echo json_encode($postTags);

?>
