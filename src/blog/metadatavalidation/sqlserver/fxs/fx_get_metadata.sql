CREATE FUNCTION [dbo].[fx_get_procedure_parameters]
(
    @pxProcedureSchema SYSNAME,
    @pxProcedureName   SYSNAME
)
RETURNS TABLE
AS
RETURN
(
    SELECT
        SPECIFIC_SCHEMA,
        SPECIFIC_NAME,
        PARAMETER_NAME,
        ORDINAL_POSITION,
        DATA_TYPE,
        CHARACTER_MAXIMUM_LENGTH,
        NUMERIC_PRECISION,
        NUMERIC_SCALE
    FROM INFORMATION_SCHEMA.PARAMETERS
    WHERE SPECIFIC_SCHEMA = @pxProcedureSchema
      AND SPECIFIC_NAME   = @pxProcedureName
);