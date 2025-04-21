DROP FUNCTION IF EXISTS meta.fx_get_dynamic_sql_clause;

CREATE OR REPLACE FUNCTION meta.fx_get_dynamic_sql_clause
(
     pxClauseType VARCHAR(32)
	,pxAlias VARCHAR(32)
	,pxColumn VARCHAR(32)
	,pxOperator VARCHAR(32)
	,pxValueString VARCHAR(32)
	,pxValueInt INTEGER
	,pxLastClause BOOLEAN
)

RETURNS VARCHAR(1024)

LANGUAGE plpgsql

AS $$

DECLARE
	textDynamicSqlClause VARCHAR(1024);

BEGIN

textDynamicSqlClause := 
	pxClauseType || ' ' || 
	COALESCE(pxAlias, '') ||
	COALESCE('.' || pxColumn, '') || ' ' ||
	COALESCE(pxOperator, '') || ' ' ||
	COALESCE(pxValueString, '') ||
	COALESCE(CAST(pxValueInt AS VARCHAR(32)), '');

textDynamicSqlClause := TRIM(textDynamicSqlClause);

IF pxLastClause = TRUE
THEN
	textDynamicSqlClause := textDynamicSqlClause || ';';
ELSE
	textDynamicSqlClause := textDynamicSqlClause || E'\n';
END IF;

RETURN textDynamicSqlClause;

END;
$$
