<?php

    namespace metadatavalidation;

    require_once __DIR__ . '/metadatavalidationDb.php';

    /**
     * Parameters compared against nullable columns in the
     * database tables.
     *
     * @var array<int, string>
     */
    const NULLABLE_PXS = [
        'pxRequestedShipDate'
    ];

    /**
     * Domain membership validation parameters.
     *
     * @var array<string>
     */
    const REFDATA_PXS = [
        'pxCustomerName',
        'pxBusinessUnitCode',
        'pxOrderStatusCode',
        'pxSalesChannelCode'
    ];
    
    /**
     * Parameters validated against domain membership using
     * case-insensitive contains matching.
     *
     * @var array<int, string>
     */    
    const WILDCARD_PXS = [
        'pxCustomerName'
    ];

    /**
     * getOrders
     * 
     * Receives user-supplied CLI parameters, validates each parameter
     * according to its domain-membership or type-validation contract,
     * then returns the resulting orders data to the CLI.
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
            $dbOperation = getDBOperation(__FUNCTION__);
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
        $validatePx = normalizeNull($pxValue);

        // Make sure nullable column isn't filtering on value and IS NULL.
        if (in_array($pxParameter, NULLABLE_PXS, true))
        {
            $normalizedOperator = normalizeNull($pxParameterOperator);
            if ($normalizedOperator !== null)
            {
                validateNullableColumn(
                    $pxParameter,
                    $validatePx,
                    $normalizedOperator
                );
            }
        }

        $validPx = false;

        if ($validatePx !== null)
        {
            // Validate domain membership p(x)'s.
            if (in_array($pxParameter, REFDATA_PXS, true))
            {
                $validPx = validateDomainMembership(
                    $pxParameter,
                    $validatePx
                );
            }
            // Validate type p(x)'s.
            else
            {
                $validPx = validateType(
                    $pxParameter,
                    $validatePx
                );
            }
            // Throw new runtime exception if validation fails.
            if ($validPx === false)
            {
                throw new \RuntimeException(
                    "{$pxParameter} {$pxValue} is invalid."
                );
            }
        }

        return $validatePx;
    }

    /**
     * validateType
     *
     * @param string $pxParameter
     * @param string $pxValue
     *
     * @return bool
     */    
    function validateType(string $pxParameter, string $pxValue): bool
    {
        $dbOperation = getDBOperation(__FUNCTION__);
        $typeMetaData = executeQuery(
            $dbOperation,
            [
                'pxSchema'     => 'dbo',
                'pxObjectName' => 'usp_get_orders',
                'pxName'       => '@' . $pxParameter
            ]
        );
        $a = 1;
    }

    /* 
     * getDBOperation
     *
     * @param string $pxDBFx string
     *
     * @return string
     */
    function getDBOperation(string $pxAppFx): string
    {
        $dbOperation = substr(
            $pxAppFx,
            strrpos($pxAppFx, '\\') + 1
        );
        return $dbOperation;
    }

    /**
     * validateDomainMembership
     *
     * @param string $pxParameter
     * @param string $pxValue
     *
     * @return bool
     */    
    function validateDomainMembership(string $pxParameter, string $pxValue): bool
    {
        $dbOperation = getDBOperation(__FUNCTION__);
        $domainMembers = executeQuery(
            $dbOperation,
            [
                'membershipKey' => $pxParameter
            ]
        );
        $domainMembers = array_column(
            $domainMembers,
            'membership_value'
        );

        if (in_array($pxParameter, WILDCARD_PXS, true))
        {
            foreach ($domainMembers as $domainMember)
            {
                if (stripos($domainMember, $pxValue) !== false)
                {
                    return true;
                }
            }
        }
        else
        {
            $validateDomainMembership = in_array(
                $pxValue,
                $domainMembers,
                true
            );
        }

        return $validateDomainMembership;
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
     * validatePxType
     *
     *
     * @return void
     */   
    function validatePxType(): bool
    {

    }

    
?>