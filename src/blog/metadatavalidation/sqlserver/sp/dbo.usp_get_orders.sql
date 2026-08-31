CREATE PROCEDURE [dbo].[usp_get_orders]

@pxCustomerName,
@pxBusinessUnitCode,
@pxOrderStatusCode,
@pxSalesChannelCode,
@pxOrderDate,
@pxRequestedShipDate,
@pxMinSubtotalAmount,
@pxMaxSubtotalAmount,
@pxExpedited

AS

BEGIN

	SET NOCOUNT ON;						-- Don't count the # of records affected.
	SET QUOTED_IDENTIFIER ON;			-- Treated double quotes as field delimiters.
	SET ANSI_NULLS ON;					-- Treat NULLS as unknown in compares.
	SET ANSI_PADDING ON;				-- Pad chars, don't trim trailing spaces.
	SET ANSI_WARNINGS ON;				-- Warn on null aggregates, arith. overflows.
	SET ARITHABORT ON;					-- Abort transaction for overflows & div/0.
	SET NUMERIC_ROUNDABORT OFF;			-- Allow numeric truncations.
	SET XACT_ABORT ON;					-- Abort whole transactions at a time.
	SET CONCAT_NULL_YIELDS_NULL ON;		-- NULL causes expressions to be unknown.

	BEGIN TRY

	-- Begin a transaction if no others are being processed.
	IF @@TRANCOUNT = 0 BEGIN TRANSACTION

		IF @@TRANCOUNT = 1
		BEGIN	

		IF @@TRANCOUNT = 1 AND XACT_STATE() = 1		
				COMMIT TRANSACTION
		END

	END TRY

	-- Exception handler if any errors raised.
	BEGIN CATCH

	-- Catch the exception and store the error data.
	IF XACT_STATE() = -1
	BEGIN;

	ROLLBACK TRANSACTION;

	DECLARE 
		@intErrorSeverity AS INT,
		@intErrorState AS INT,
		@strErrorProcedure AS VARCHAR(128),
		@intErrorLine AS INT,
		@strErrorMsg AS VARCHAR(512);

	SET @intErrorSeverity = ERROR_SEVERITY();
	SET	@intErrorState = ERROR_STATE();
	SET @strErrorProcedure = ERROR_PROCEDURE();
	SET @intErrorLine = ERROR_LINE();
	SET @strErrorMsg = ERROR_MESSAGE();


	EXEC [error].[usp_write_db_error]

		@intErrorSeverity,
		@intErrorState,
		@strErrorProcedure,
		@intErrorLine,
		@strErrorMsg;

	END;

	END CATCH

END;