<?php

    /**
     * getOrders
     *
     * @param string $pxCustomerName
     * @param string $pxBusinessUnitCode
     * @param string $pxOrderStatusCode
     * @param string $pxSalesChannelCode
     * @param string $pxOrderDate
     * @param string $pxRequestedShipDate
     * @param string $pxMinSubtotalAmount
     * @param string $pxMaxSubtotalAmount
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
        ?string $pxMinSubtotalAmount,
        ?string $pxMaxSubtotalAmount,
        ?string $pxExpedited
        
    ):array
    {
        $getOrders = [];
        validatePxCount();
        return $getOrders;
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