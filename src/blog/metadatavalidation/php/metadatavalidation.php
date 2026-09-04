<?php

    namespace metadatavalidation;

    require_once __DIR__ . '/metadatavalidationOrchestrator.php';

    const VALID_OPERATIONS = ['getOrders'];

    $operation = $argv[1] ?? null;

    if (!in_array($operation, VALID_OPERATIONS, true)) {
        throw new \InvalidArgumentException("Invalid operation: {$operation}.");
    }

    switch ($operation) {

        case 'getOrders':

            $resultset =                 
                getOrders(
                    $argv[2] ?? null,	// @pxCustomerName		-> :pxCustomerName
                    $argv[3] ?? null,	// @pxBusinessUnitCode  -> :pxBusinessUnitCode
                    $argv[4] ?? null,	// @pxOrderStatusCode   -> :pxOrderStatusCode
                    $argv[5] ?? null,	// @pxSalesChannelCode  -> :pxSalesChannelCode
                    $argv[6] ?? null,	// @pxOrderDate         -> :pxOrderDate
                    $argv[7] ?? null,	// @pxRequestedShipDate -> :pxRequestedShipDate
                    $argv[8] ?? null,	// @pxMinTotalAmount    -> :pxMinTotalAmount
                    $argv[9] ?? null,	// @pxMaxTotalAmount    -> :pxMaxTotalAmount
                    $argv[10] ?? null	// @pxExpedited         -> :pxExpedited
                );
            if ( $resultset ) 
            {
                $rsHeaders = array_keys($resultset[0]);
                $rsColumnWidths = [];
                foreach ($rsHeaders as $rsHeader)
                {
                    $rsColumnWidths[$rsHeader] = strlen($rsHeader);
                }

                foreach ($resultset as $rsRecord)
                {
                    foreach ($rsRecord as $rsColumn => $rsDatum)
                    {
                        $rsDatumLen = strlen((string) $rsDatum);
                        if ($rsDatumLen > $rsColumnWidths[$rsColumn])
                        {
                            $rsColumnWidths[$rsColumn] = $rsDatumLen;
                        }
                    }

                }
                
                foreach ($rsHeaders as $rsHeader)
                {
                    echo str_pad(
                        $rsHeader,
                        $rsColumnWidths[$rsHeader] + 2
                    );
                }
                echo PHP_EOL;

                foreach ($resultset as $rsRecord)
                {
                    foreach ($rsRecord as $rsColumn => $rsDatum)
                    {
                        echo str_pad(
                            (string) $rsDatum,
                            $rsColumnWidths[$rsColumn] + 2
                        );
                    }

                    echo PHP_EOL;
                }
            }
            else {
                echo 'No records to return.' . PHP_EOL;
            }

            break;
    }

?>
