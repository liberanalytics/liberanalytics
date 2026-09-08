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
        SPECIFIC_SCHEMA AS specific_schema,
        SPECIFIC_NAME AS specific_name,
        PARAMETER_NAME AS parameter_name,
        ORDINAL_POSITION AS ordinal_position,
        DATA_TYPE AS data_type,
        CHARACTER_MAXIMUM_LENGTH AS character_maximum_length,
        NUMERIC_PRECISION AS numeric_precision,
        NUMERIC_SCALE AS numeric_scale
    FROM INFORMATION_SCHEMA.PARAMETERS AS metadata_px
    
    WHERE 1=1
    AND metadata_px.SPECIFIC_SCHEMA = @pxSchema
    AND metadata_px.SPECIFIC_NAME   = @pxObjectName
    AND metadata_px.PARAMETER_NAME = @pxName
);
GO