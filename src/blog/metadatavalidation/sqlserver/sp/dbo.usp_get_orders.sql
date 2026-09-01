USE LiberAnalytics;
GO

SET QUOTED_IDENTIFIER ON;
SET ANSI_NULLS ON;
GO

DROP PROCEDURE IF EXISTS [dbo].[usp_get_orders];
GO

CREATE PROCEDURE [dbo].[usp_get_orders]

    @pxCustomerName NVARCHAR(100) = NULL, 
    @pxBusinessUnitCode CHAR(3) = NULL, 
    @pxOrderStatusCode VARCHAR(12) = NULL, 
    @pxSalesChannelCode CHAR(3) = NULL, 
    @pxOrderDate DATE = NULL, 
    @pxRequestedShipDate DATE = NULL, 
    @pxMinSubtotalAmount DECIMAL(12, 2) = NULL, 
    @pxMaxSubtotalAmount DECIMAL(12, 2) = NULL, 
    @pxExpedited BIT = NULL

AS 
BEGIN 
    -- Runtime performance and safety settings are fine here
    SET NOCOUNT ON; 
    SET ANSI_PADDING ON; 
    SET ANSI_WARNINGS ON; 
    SET ARITHABORT ON; 
    SET NUMERIC_ROUNDABORT OFF; 
    SET XACT_ABORT ON; 
    SET CONCAT_NULL_YIELDS_NULL ON; 

    BEGIN TRY 

        -- Begin a transaction if no others are being processed. 
        DECLARE @bitStartedTransaction BIT = 0;

        IF @@TRANCOUNT = 0
        BEGIN
            BEGIN TRANSACTION;
            SET @bitStartedTransaction = 1;
        END

        -- stored procedure logic/queries --

        SELECT

             orders.order_id
            ,cust.customer_name
            ,os.order_status_desc AS order_status
            ,bu.company_name
            ,bu.business_unit_name
            ,CAST(orders.order_date AS DATE) AS order_date
            ,REPLACE(
                REPLACE(
                    FORMAT(orders.order_date, 'h:mm tt'),
                    'AM', 'a.m.'
                ),
                'PM', 'p.m.'
            ) AS order_time
            ,COALESCE(CAST(orders.requested_ship_date AS VARCHAR(10)), 'None') AS requested_ship_date
            ,REPLACE(
                REPLACE(
                    CAST(orders.expedited AS VARCHAR(1)),
                    '0', 'No'
                ),
                    '1', 'Yes'
            ) AS expedited
            ,(orders.subtotal_amount + orders.tax_amount + orders.shipping_amount) AS order_total
            ,sc.sales_channel_desc AS sales_channel
            ,COALESCE(orders.customer_note, 'None') AS customer_note

        FROM

        dbo.orders AS orders

        INNER JOIN dbo.business_units AS bu
        ON (orders.business_unit_id = bu.business_unit_id)

        INNER JOIN dbo.customers AS cust
        ON (orders.customer_id = cust.customer_id)

        INNER JOIN dbo.order_statuses AS os
        ON (orders.order_status_code = os.order_status_code)

        INNER JOIN dbo.sales_channels AS sc
        ON (orders.sales_channel_code = sc.sales_channel_code)

        ORDER BY orders.order_date DESC;

        -- Cleanly close and commit the transaction if we opened it.
        IF @bitStartedTransaction = 1
           AND XACT_STATE() = 1
        BEGIN
            COMMIT TRANSACTION;
        END
    END TRY 

    BEGIN CATCH 
        -- Catch the exception and store the error data. 
        IF XACT_STATE() = -1 
        BEGIN
            ROLLBACK TRANSACTION; 
        END

        DECLARE @intErrorSeverity AS INT, 
                @intErrorState AS INT, 
                @strErrorProcedure AS VARCHAR(128), 
                @intErrorLine AS INT, 
                @strErrorMsg AS VARCHAR(512); 

        SET @intErrorSeverity = ERROR_SEVERITY(); 
        SET @intErrorState = ERROR_STATE(); 
        SET @strErrorProcedure = ERROR_PROCEDURE(); 
        SET @intErrorLine = ERROR_LINE(); 
        SET @strErrorMsg = ERROR_MESSAGE(); 

        THROW 50000, @strErrorMsg, @intErrorState;
    END CATCH 
END;
GO

