DROP FUNCTION IF EXISTS meta.fx_get_fx_pxs;

CREATE OR REPLACE FUNCTION meta.fx_get_fx_pxs
(
     pxFxSchema VARCHAR(32)
	,pxFxName VARCHAR(32)
)

RETURNS TABLE
(
	 px_order BIGINT
	,px_name TEXT
	,px_default_value TEXT
)

LANGUAGE plpgsql
STABLE
AS $$

BEGIN

RETURN QUERY

SELECT

	 pg_proc_arg_order.arg_ordinality AS px_order
	,pg_proc_arg_defaults.arg_name AS px_name
	,CAST(CASE 
	 	WHEN LOWER(pg_proc_arg_defaults.default_value) LIKE '%null%' THEN 'NULL'
		WHEN LOWER(pg_proc_arg_defaults.default_value) LIKE '%false%' THEN 'FALSE'
	 END AS TEXT) AS px_default_value


FROM

(SELECT

	 pg_ns_args_inner.nspname AS schema_name
	,pg_proc_args_inner.proname AS fx_name
	,unnest(pg_proc_args_inner.proargmodes) AS arg_mode
	,unnest(pg_proc_args_inner.proargnames) AS arg_name
	,unnest(regexp_split_to_array(pg_get_expr(pg_proc_args_inner.proargdefaults, 0), ',\s*')) AS default_value
	
FROM pg_proc AS pg_proc_args_inner

JOIN pg_namespace AS pg_ns_args_inner
ON (pg_proc_args_inner.pronamespace = pg_ns_args_inner.oid)

WHERE 1=1
AND pg_proc_args_inner.proargdefaults IS NOT NULL
AND pg_ns_args_inner.nspname = pxFxSchema
AND pg_proc_args_inner.proname = pxFxName

) AS pg_proc_arg_defaults

INNER JOIN

(SELECT

	 pg_ns_order_inner.nspname AS schema_name
	,pg_proc_order_inner.proname AS fx_name
	,pg_fx_argnames.arg_name
	,pg_fx_argnames.arg_ordinality

FROM pg_proc AS pg_proc_order_inner

JOIN pg_namespace AS pg_ns_order_inner
ON (pg_proc_order_inner.pronamespace = pg_ns_order_inner.oid)

LEFT JOIN unnest(pg_proc_order_inner.proargnames) WITH ORDINALITY AS pg_fx_argnames(arg_name, arg_ordinality) ON TRUE

WHERE 1=1
AND pg_proc_order_inner.proargdefaults IS NOT NULL
AND pg_ns_order_inner.nspname = pxFxSchema
AND pg_proc_order_inner.proname = pxFxName

) AS pg_proc_arg_order

ON (pg_proc_arg_defaults.schema_name = pg_proc_arg_order.schema_name
AND pg_proc_arg_defaults.fx_name = pg_proc_arg_order.fx_name
AND pg_proc_arg_defaults.arg_name = pg_proc_arg_order.arg_name)

WHERE 1=1
AND pg_proc_arg_defaults.arg_mode = 'i';

END;
$$;