DROP FUNCTION IF EXISTS app.fx_execute_crud_operation;

CREATE OR REPLACE FUNCTION app.fx_execute_crud_operation
(
	,px_schema TEXT
	,px_fx_name TEXT
	,px_pxs TEXT []
)

LANGUAGE plpgsql
VOLATILE
AS $$

BEGIN

END;
$$