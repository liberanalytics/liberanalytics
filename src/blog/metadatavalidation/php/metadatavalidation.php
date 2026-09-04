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

            if ($argc !== 9) { echo 'Invalid number of arguments: must be 9.'; }
            else {
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
                    // Get the resultset headers and column widths of ea. header.
                    $rsHeaders = array_keys($resultset[0]);
                    $rsColumnWidths = [];
                    foreach ($rsHeaders as $rsHeader)
                    {
                        $rsColumnWidths[$rsHeader] = strlen($rsHeader);
                    }

                    // Inspect the resultset datums to determine the longest datum
                    //  per column in the resultset.
                    foreach ($resultset as $rsRecord)
                        foreach ($rsRecord as $rsColumn => $rsDatum)
                        {
                            $rsDatumLen = strlen($rsDatum);
                            if ($rsDatumLen > $rsColumnWidths[$rsColumn])
                            {
                                $rsColumnWidths[$rsColumn] = $rsDatumLen;
                            }
                        }

                    // Echo the resultset headers padding ea. header.
                    foreach ($rsHeaders as $rsHeader)
                    {
                        echo str_pad(
                            $rsHeader,
                            $rsColumnWidths[$rsHeader] +2
                        );
                    }
                    echo PHP_EOL;

                    // Iterate through resultset, echo datums per row.
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
            }
            break;
    }

?>
