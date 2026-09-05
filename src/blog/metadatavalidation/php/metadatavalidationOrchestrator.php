<?php

    namespace metadatavalidation;

    require_once __DIR__ . '/metadatavalidationDb.php';

    /**
     * Params compared against nullable columns in the
     * database tables.
     *
     * @var array<int, string>
     */
    const NULLABLE_PXS = [
        'pxRequestedShipDate'
    ];    

    /**
     * getOrders
     *
     * @param string|null $pxCustomerName
     * @param string|null $pxBusinessUnitCode
     * @param string|null $pxOrderStatusCode
     * @param string|null $pxSalesChannelCode
     * @param string|null $pxOrderDate
     * @param string|null $pxRequestedShipDate
     * @param string|null $pxRequestedShipDateOperator
     * @param string|null $pxMinTotalAmount
     * @param string|null $pxMaxTotalAmount
     * @param string|null $pxExpedited
     *
     * @return array
     */
    function getOrders
    (
        string $pxCustomerName,
        string $pxBusinessUnitCode,
        string $pxOrderStatusCode,
        string $pxSalesChannelCode,
        string $pxOrderDate,
        string $pxRequestedShipDate,
        string $pxRequestedShipDateOperator,
        string $pxMinTotalAmount,
        string $pxMaxTotalAmount,
        string $pxExpedited        
    ): array
    {
        // $getOrders = [];
        // validatePxCount();
        // return $getOrders;
        $pxs = [
            'pxCustomerName'                => validatePx('pxCustomerName', $pxCustomerName),
            'pxBusinessUnitCode'            => validatePx('pxBusinessUnitCode', $pxBusinessUnitCode),
            'pxOrderStatusCode'             => validatePx('pxOrderStatusCode', $pxOrderStatusCode),
            'pxSalesChannelCode'            => validatePx('pxSalesChannelCode', $pxSalesChannelCode),
            'pxOrderDate'                   => validatePx('pxOrderDate', $pxOrderDate),
            'pxRequestedShipDate'           => validatePx(
                                                'pxRequestedShipDate',
                                                $pxRequestedShipDate,
                                                $pxRequestedShipDateOperator
                                                ),
            'pxRequestedShipDateOperator'   => validatePx('pxRequestedShipDateOperator', $pxRequestedShipDateOperator),
            'pxMinTotalAmount'              => validatePx('pxMinTotalAmount', $pxMinTotalAmount),
            'pxMaxTotalAmount'              => validatePx('pxMaxTotalAmount', $pxMaxTotalAmount),
            'pxExpedited'                   => validatePx('pxExpedited', $pxExpedited)
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

    /* 
     * validatePx
     *
     * @param string $pxParameter string
     * @param string $pxValue string
     * @param string $pxParameterOperator string|null
     *
     * @return string|null
     */
    function validatePx(    
        string $pxParameter,
        string $pxValue,
        string|null $pxParameterOperator = null
    ): string|null
    {
        $normalizedPxValue = normalizeNull($pxValue);

        // Make sure nullable column isn't filtering on value and IS NULL.
        if (in_array($pxParameter, NULLABLE_PXS, true))
        {
            $normalizedOperator = normalizeNull($pxParameterOperator);

            if ($normalizedOperator !== null)
            {
                validateNullableColumn(
                    $pxParameter,
                    $normalizedPxValue,
                    $normalizedOperator
                );
            }
        }

        return $validatePx;
    }

    /**
     * validateNullableColumn
     *
     * @param string $pxParameter
     * @param string|null $pxValue
     * @param string|null $pxParameterOperator
     *
     * @return void
     */    
    function validateNullableColumn(
        string $pxParameter, 
        string|null $pxValue, 
        string|null $pxParameterOperator
    ): void
    {
        if ($pxValue !== null && $pxParameterOperator !== null)
        {
            throw new \RuntimeException(
                "{$pxParameter} filters for {$pxValue}; cannot also filter for NULLS."
            );  
        }
    }

    /**
     * normalizePx
     *
     * @param string $pxValue
     *
     * @return string|null
     */
    function normalizePx(string $pxValue): string|null
    {
        $normalizePx = normalizeNull($pxValue);
        return $normalizePx;
    }

    /**
     * normalizeNull
     *
     * @param string $pxValue
     *
     * @return string|null
     */    
    function normalizeNull(string $pxValue): string|null
    {      
        $normalizeNull = (strcasecmp($pxValue, 'NULL') === 0) ? null : $pxValue;
        return $normalizeNull;
    }
    
    /**
     * validatePxValues
     *
     *
     * @return void
     */   
    function validatePxValue()
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