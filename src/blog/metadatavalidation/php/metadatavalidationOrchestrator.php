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
        
    ): array
    {
        // $getOrders = [];
        // validatePxCount();
        // return $getOrders;
        $pxs = [
            'pxCustomerName'      => normalizePx($pxCustomerName),
            'pxBusinessUnitCode'  => normalizePx($pxBusinessUnitCode),
            'pxOrderStatusCode'   => normalizePx($pxOrderStatusCode),
            'pxSalesChannelCode'  => normalizePx($pxSalesChannelCode),
            'pxOrderDate'         => normalizePx($pxOrderDate),
            'pxRequestedShipDate' => normalizePx($pxRequestedShipDate),
            'pxMinTotalAmount'    => normalizePx($pxMinTotalAmount),
            'pxMaxTotalAmount'    => normalizePx($pxMaxTotalAmount),
            'pxExpedited'         => normalizePx($pxExpedited)
        ];

        try
        {
            $dbOperation = substr(
                __FUNCTION__,
                strrpos(__FUNCTION__, '\\') + 1
            );

            $getOrders = executeQuery($dbOperation, $pxs);
            return $getOrders;
        }
        catch (\PDOException | \RuntimeException | \JsonException $e)
        {
            echo $e->getMessage();
            return [];
        }

    }

    /**
     * normalizePx
     *
     * @param string|null $pxValue
     *
     * @return mixed
     */
    function normalizePx(string|null $pxValue): mixed
    {
        $normalizePx = normalizeNull($pxValue);
        return $normalizePx;
    }

    /**
     * normalizeNull
     *
     * @param string|null $pxValue
     *
     * @return mixed
     */    
    function normalizeNull(string|null $pxValue): string|null
    {
        if ($pxValue === null)
        {
            return null;
        }        
        $normalizeNull = (strcasecmp($pxValue, 'NULL') === 0) ? null : $pxValue;
        return $normalizeNull;
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