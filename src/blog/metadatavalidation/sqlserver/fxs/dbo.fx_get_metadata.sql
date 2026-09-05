USE LiberAnalytics;
GO

DROP FUNCTION IF EXISTS [dbo].[fx_get_metadata];
GO

CREATE FUNCTION [dbo].[fx_get_metadata]
(
    @pxSchema SYSNAME,
    @pxObjectName SYSNAME,
    @pxName VARCHAR(32)
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
    FROM INFORMATION_SCHEMA.PARAMETERS AS metadata_px
    
    WHERE 1=1
    AND metadata_px.SPECIFIC_SCHEMA = @pxSchema
    AND metadata_px.SPECIFIC_NAME   = @pxObjectName
    AND metadata_px.PARAMETER_NAME = @pxName
);
GO