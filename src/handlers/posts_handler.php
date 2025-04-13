<?php

    include(dirname(__DIR__) . "/" . "classes/LaData.php");
    $laData = new LaData();

    $handlerData = json_decode(file_get_contents('php://input'), true);

    

    echo json_encode($handlerData);

?>