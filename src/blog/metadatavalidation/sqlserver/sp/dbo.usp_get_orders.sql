USE LiberAnalytics;
GO

SET QUOTED_IDENTIFIER ON;
SET ANSI_NULLS ON;
GO

CREATE PROCEDURE [dbo].[usp_get_orders] 
    @pxCustomerName NVARCHAR(100) = NULL, 
    @pxBusinessUnitCode CHAR(3) = NULL, 
    @pxOrderStatusCode VARCHAR(12) = NULL, 
    @pxSalesChannelCode CHAR(3) = NULL, 
    @pxOrderDate DATE = NULL, 
    @pxRequestedShipDate DATE, 
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
        IF @@TRANCOUNT = 0 
        BEGIN
            BEGIN TRANSACTION;
        END

        -- stored procedure logic/queries --

        -- Cleanly close and commit the transaction if we opened it.
        IF @@TRANCOUNT = 1 AND XACT_STATE() = 1 
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