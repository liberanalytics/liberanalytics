DROP FUNCTION IF EXISTS meta.fx_get_dynamic_sql_clause;

CREATE OR REPLACE FUNCTION meta.fx_get_dynamic_sql_clause
(
     pxClauseType TEXT
	,pxAlias TEXT
	,pxColumn TEXT
	,pxOperator TEXT
	,pxValueString TEXT[]
	,pxValueInt INTEGER[]
)

RETURNS TEXT

LANGUAGE plpgsql
AS $$

DECLARE
	txtDynamicSQLClause TEXT;

BEGIN

IF pxClauseType = 'AND' THEN
	IF (pxValueString IS NOT NULL OR pxValueInt IS NOT NULL) THEN
		txtDynamicSQLClause :=

			E'\n' ||
			pxClauseType || ' ' || 
			COALESCE(pxAlias || '.', '') || 
			pxColumn || ' ' ||
			pxOperator || ' ' ||
			'ANY(ARRAY[' ||

			COALESCE( CAST(pxValueInt AS TEXT), CAST(pxValueString AS TEXT) ) ||

			'])';

	ELSE
		txtDynamicSQLClause := '';
	END IF;
	
ELSIF pxClauseType = 'ORDER BY' THEN
	IF pxColumn IS NOT NULL THEN
		txtDynamicSQLClause :=
			E'\n' ||
			pxClauseType || ' ' || 
			pxAlias || '.' || 
			pxColumn || ' ' ||
			pxOperator;
	ELSE
		txtDynamicSQLClause := '';
	END IF;

ELSIF pxClauseType = 'LIMIT' THEN	
	IF pxValueInt IS NOT NULL THEN
		txtDynamicSQLClause :=
			E'\n' ||
			pxClauseType || ' ' ||
			CAST(pxValueInt AS TEXT);
	ELSE
		txtDynamicSQLClause := '';
	END IF;
	
END IF;

RETURN txtDynamicSQLClause;

END;
$$
