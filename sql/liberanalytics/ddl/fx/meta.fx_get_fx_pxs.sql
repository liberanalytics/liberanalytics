DROP FUNCTION IF EXISTS meta.fx_get_fx_pxs;

CREATE OR REPLACE FUNCTION meta.fx_get_fx_pxs
(
     px_fx_schema TEXT
	,px_fx_name TEXT
)

RETURNS TABLE
(
	 px_schema_name NAME
	,px_function_name NAME
	,px_order BIGINT
	,px_name TEXT
	,px_type NAME
	,px_default_value TEXT
)

LANGUAGE plpgsql
STABLE
AS $$

BEGIN

RETURN QUERY

SELECT

	 pg_proc_arg_defaults.schema_name
	,pg_proc_arg_defaults.fx_name
	,pg_proc_arg_order.arg_ordinality 
	,pg_proc_arg_defaults.arg_name
	,pg_proc_arg_defaults.typname
	,pg_proc_arg_defaults.default_value

FROM

(SELECT

	 pg_proc_arg_defaults_inner.schema_name
	,pg_proc_arg_defaults_inner.fx_name
	,pg_proc_arg_defaults_inner.arg_name
	,pg_proc_arg_defaults_inner.default_value
	,pg_types.typname

FROM

(SELECT

	 pg_ns_args_inner.nspname AS schema_name
	,pg_proc_args_inner.proname AS fx_name	
	,unnest(pg_proc_args_inner.proargmodes) AS arg_mode
	,unnest(pg_proc_args_inner.proargnames) AS arg_name
	,unnest(regexp_split_to_array(pg_get_expr(pg_proc_args_inner.proargdefaults, 0), ',\s*')) AS default_value
	,unnest(pg_proc_args_inner.proargtypes) AS arg_oid

FROM pg_proc AS pg_proc_args_inner

JOIN pg_namespace AS pg_ns_args_inner
ON (pg_proc_args_inner.pronamespace = pg_ns_args_inner.oid
AND pg_ns_args_inner.nspname NOT IN ('pg_catalog', 'information_schema'))

WHERE 1=1
AND pg_proc_args_inner.proargdefaults IS NOT NULL
AND pg_ns_args_inner.nspname = px_fx_schema
AND pg_proc_args_inner.proname = px_fx_name

) AS pg_proc_arg_defaults_inner

INNER JOIN pg_type AS pg_types ON
(pg_proc_arg_defaults_inner.arg_oid = pg_types.oid)

WHERE 1=1
AND pg_proc_arg_defaults_inner.arg_mode = 'i'

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

LEFT JOIN LATERAL unnest(pg_proc_order_inner.proargnames) WITH ORDINALITY AS pg_fx_argnames(arg_name, arg_ordinality) ON TRUE

WHERE 1=1
AND pg_proc_order_inner.proargdefaults IS NOT NULL
AND pg_ns_order_inner.nspname = px_fx_schema
AND pg_proc_order_inner.proname = px_fx_name

) AS pg_proc_arg_order

ON (pg_proc_arg_defaults.schema_name = pg_proc_arg_order.schema_name
AND pg_proc_arg_defaults.fx_name = pg_proc_arg_order.fx_name
AND pg_proc_arg_defaults.arg_name = pg_proc_arg_order.arg_name);

END;
$$;