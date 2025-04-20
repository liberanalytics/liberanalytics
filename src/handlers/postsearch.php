<?php

if
( 
    ((isset($_SERVER['HTTP_ORIGIN'])) && 
    ($_SERVER['HTTP_ORIGIN'] === 'http://localhost')) &&
    ($_SERVER['REQUEST_METHOD'] === 'POST') &&
    (strpos($_SERVER['CONTENT_TYPE'],'application/json') !== false)
)
{
    echo json_encode(
        [
            "orign" => $_SERVER['HTTP_ORIGIN'],
            "content_type" => $_SERVER['CONTENT_TYPE']
        ]
    );
}


?>