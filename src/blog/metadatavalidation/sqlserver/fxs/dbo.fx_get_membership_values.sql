USE LiberAnalytics;
GO

DROP FUNCTION IF EXISTS [dbo].[fx_get_membership_values];
GO

CREATE FUNCTION [dbo].[fx_get_membership_values]
(
    @membershipKey varchar(50)
)
RETURNS @membershipValues TABLE
(
    membership_value varchar(256)
)
AS
BEGIN

    IF @membershipKey = 'pxCustomerName'
    BEGIN
        INSERT INTO @membershipValues
        SELECT CAST(customer_name AS varchar(256))
        FROM dbo.customers;
    END

    ELSE IF @membershipKey = 'pxBusinessUnitCode'
    BEGIN
        INSERT INTO @membershipValues
        SELECT CAST(business_unit_code AS varchar(256))
        FROM dbo.business_units;
    END

    ELSE IF @membershipKey = 'pxOrderStatusCode'
    BEGIN
        INSERT INTO @membershipValues
        SELECT CAST(order_status_code AS varchar(256))
        FROM dbo.order_statuses;
    END

    ELSE IF @membershipKey = 'pxSalesChannelCode'
    BEGIN
        INSERT INTO @membershipValues
        SELECT CAST(sales_channel_code AS varchar(256))
        FROM dbo.sales_channels;
    END

    RETURN;
END;