<?php

namespace metadatavalidation;

    require_once __DIR__ . '/metadatavalidationDb.php';

    /**
     * getOrders
     *
     * @param string $pxCustomerName
     * @param string $pxBusinessUnitCode
     * @param string $pxOrderStatusCode
     * @param string $pxSalesChannelCode
     * @param string $pxOrderDate
     * @param string $pxRequestedShipDate
     * @param string $pxMinTotalAmount
     * @param string $pxMaxTotalAmount
     * @param string $pxExpedited
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
        
    ):array
    {
        // $getOrders = [];
        // validatePxCount();
        // return $getOrders;

        echo "1";
        return [1];
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