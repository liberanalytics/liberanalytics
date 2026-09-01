USE LiberAnalytics;
GO

SET QUOTED_IDENTIFIER ON;
SET ANSI_NULLS ON;
GO

DROP PROCEDURE IF EXISTS [dbo].[usp_get_metadata];
GO

CREATE PROCEDURE [dbo].[usp_get_metadata]

    @pxCustomerName NVARCHAR(100) = NULL, 
    @pxBusinessUnitCode CHAR(3) = NULL, 
    @pxOrderStatusCode VARCHAR(12) = NULL

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

