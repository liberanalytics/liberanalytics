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
     * Type validation parameters.
     *
     * @var array<string>
     */    
    const TYPE_FXS = [
        'date'      => __NAMESPACE__ . '\filterDate'
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
            'pxCustomerName'                => filterPx('pxCustomerName', $pxCustomerName),
            'pxBusinessUnitCode'            => filterPx('pxBusinessUnitCode', $pxBusinessUnitCode),
            'pxOrderStatusCode'             => filterPx('pxOrderStatusCode', $pxOrderStatusCode),
            'pxSalesChannelCode'            => filterPx('pxSalesChannelCode', $pxSalesChannelCode),
            'pxOrderDate'                   => filterPx('pxOrderDate', $pxOrderDate),
            'pxRequestedShipDate'           => filterPx(
                                                'pxRequestedShipDate',
                                                $pxRequestedShipDate,
                                                $pxRequestedShipDateOperator
                                                ),
            'pxRequestedShipDateOperator'   => filterPx('pxRequestedShipDateOperator', $pxRequestedShipDateOperator),
            'pxMinTotalAmount'              => filterPx('pxMinTotalAmount', $pxMinTotalAmount),
            'pxMaxTotalAmount'              => filterPx('pxMaxTotalAmount', $pxMaxTotalAmount),
            'pxExpedited'                   => filterPx('pxExpedited', $pxExpedited)
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
     * filterPx
     *
     * @param string $pxParameter string
     * @param string $pxValue string
     * @param string $pxParameterOperator string|null
     *
     * @return string|null
     */
    function filterPx(    
        string $pxParameter,
        string $pxValue,
        string|null $pxParameterOperator = null
    ): string|null
    {
        $filterPx = normalizeNull($pxValue);

        // Make sure nullable column isn't filtering on value and IS NULL.
        if (in_array($pxParameter, NULLABLE_PXS, true))
        {
            $normalizedOperator = normalizeNull($pxParameterOperator);
            if ($normalizedOperator !== null)
            {
                filterNullableColumn(
                    $pxParameter,
                    $filterPx,
                    $normalizedOperator
                );
            }
        }

        // If the parameter is not null, normalize and filter the parameter value.
        if ($filterPx !== null)
        {
            // filter domain membership p(x)'s.
            if (in_array($pxParameter, REFDATA_PXS, true))
            {
                $filterPx = filterDomainMembership(
                    $pxParameter,
                    $filterPx
                );
            }
            // filter type p(x)'s.
            else
            {
                $filterPx = filterType(
                    $pxParameter,
                    $filterPx
                );
            }
            // Throw new runtime exception if validation fails.
            if ($filterPx === false)
            {
                throw new \RuntimeException(
                    "{$pxParameter} {$pxValue} is invalid."
                );
            }
        }

        return $filterPx;
    }   

    /**
     * filterType
     *
     * @param string $pxParameter
     * @param string $pxValue
     *
     * @return bool|false
     */    
    function filterType(string $pxParameter, string $pxValue): string|false
    {
        $dbOperation = getDBOperation(__FUNCTION__);
        $typeMetaData = executeQuery(
            $dbOperation,
            [
                'pxSchema'     => 'dbo',
                'pxObjectName' => 'usp_get_orders',
                'pxName'       => '@' . $pxParameter
            ]
        )[0];

        $validateDataType = $typeMetaData['data_type'];
        $validateFx = TYPE_FXS[$validateDataType];
        $filterType = $validateFx($pxValue, $typeMetaData);

        return $filterType;
        
    }

    function filterDate(string $pxValue, array $pxTypeMetaData): string|false
    {
        $formats = [
            'Y-m-d',
            'm-d-Y',
            'm/d/Y'
        ];

        foreach ($formats as $format)
        {
            $date = \DateTimeImmutable::createFromFormat(
                '!' . $format,
                $pxValue
            );

            $errors = \DateTimeImmutable::getLastErrors();

            if (
                $date !== false
                &&
                (
                    $errors === false
                    ||
                    (
                        $errors['warning_count'] === 0
                        &&
                        $errors['error_count'] === 0
                    )
                )
            )
            {
                return $date->format('Y-m-d');
            }
        }

        return false;

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
     * filterDomainMembership
     *
     * @param string $pxParameter
     * @param string $pxValue
     *
     * @return string|false
     */
    function filterDomainMembership(
        string $pxParameter,
        string $pxValue
    ): string|false
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

        // Wildcard parameter matching.
        if (in_array($pxParameter, WILDCARD_PXS, true))
        {
            foreach ($domainMembers as $domainMember)
            {
                if (stripos($domainMember, $pxValue) !== false)
                {
                    return $pxValue;
                }
            }

            return false;
        }

        $filterDomainMembership = in_array(
            $pxValue,
            $domainMembers,
            true
        );

        return ($filterDomainMembership === true)
            ? $pxValue
            : false;
    }

    /**
     * filterNullableColumn
     *
     * @param string $pxParameter
     * @param string|null $pxValue
     * @param string|null $pxParameterOperator
     *
     * @return void
     */    
    function filterNullableColumn(
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

    
?>