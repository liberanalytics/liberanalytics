<?php

    namespace metadatavalidation;

    require_once __DIR__ . '/metadatavalidationOrchestrator.php';    

    $operation = $argv[1] ?? null;

    switch ($operation) {

        case 'getOrders':

            getOrders(
                $argv[2] ?? null,
                $argv[3] ?? null,
                $argv[4] ?? null,
                $argv[5] ?? null,
                $argv[6] ?? null,
                $argv[7] ?? null,
                $argv[8] ?? null,
                $argv[9] ?? null,
                $argv[10] ?? null
            );

            break;

        default:
            throw new \InvalidArgumentException('Invalid operation.');
    }

?>