<?php

    namespace metadatavalidation;

    require_once __DIR__ . '/metadatavalidationDb.php';

    /**
     * getOrders
     *
     * @param string|null $pxCustomerName
     * @param string|null $pxBusinessUnitCode
     * @param string|null $pxOrderStatusCode
     * @param string|null $pxSalesChannelCode
     * @param string|null $pxOrderDate
     * @param string|null $pxRequestedShipDate
     * @param string|null $pxMinTotalAmount
     * @param string|null $pxMaxTotalAmount
     * @param string|null $pxExpedited
     *
     * @return array
     */
    function getOrders
    (
        ?string $pxCustomerName,
        ?string $pxBusinessUnitCode,
        ?string $pxOrderStatusCode,
        ?string $pxSalesChannelCode,
        ?string $pxOrderDate,
        ?string $pxRequestedShipDate,
        ?string $pxMinTotalAmount,
        ?string $pxMaxTotalAmount,
        ?string $pxExpedited
        
    )  //:array
    {
        // $getOrders = [];
        // validatePxCount();
        // return $getOrders;
        $pxs = [
            'pxCustomerName'      => $pxCustomerName,
            'pxBusinessUnitCode'  => $pxBusinessUnitCode,
            'pxOrderStatusCode'   => $pxOrderStatusCode,
            'pxSalesChannelCode'  => $pxSalesChannelCode,
            'pxOrderDate'         => $pxOrderDate,
            'pxRequestedShipDate' => $pxRequestedShipDate,
            'pxMinTotalAmount'    => $pxMinTotalAmount,
            'pxMaxTotalAmount'    => $pxMaxTotalAmount,
            'pxExpedited'         => $pxExpedited
        ];

        try
        {
            $dbOperation = substr(
                __FUNCTION__,
                strrpos(__FUNCTION__, '\\') + 1
            );

            executeQuery($dbOperation, $pxs);
        }
        catch (\PDOException | \RuntimeException | \JsonException $e)
        {

        }

    }

    /**
     * validateCount
     *
     *
     * @return void
     */   
    function validatePxCount(): void
    {

    }
    
    /**
     * validatePxValues
     *
     *
     * @return void
     */   
    function validatePxValues()
    {

    }

    /**
     * validatePxMembership
     *
     *
     * @return void
     */   
    function validatePxMembership(): bool
    {

    }
    
    /**
     * validatePxType
     *
     *
     * @return void
     */   
    function validatePxType(): bool
    {

    }

    
?>