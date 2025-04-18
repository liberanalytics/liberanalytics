DROP FUNCTION IF EXISTS meta.fx_get_fx_pxs;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_fx_pxs
(
     pxFxSchema VARCHAR(32)
	,pxFxName VARCHAR(32)
)

RETURNS TABLE
(
	
)

SELECT 

	 pg_ns.nspname AS schema_name
	,pg_fx.proname AS fx_name
	-- ,pg_catalog.pg_get_function_identity_arguments(pg_fx.oid) AS identity_arguments
	,pg_fx_argnames.args_ordinality AS arg_position
	,pg_fx_argnames.argname AS arg_name
	,pg_fx_argtypes.typname AS arg_type
	
	-- ,pg_fx_argnames.args_ordinality AS arg_position
	-- ,pg_fx_argnames.argname AS arg_name
	-- ,pg_fx_argtypes.typname AS arg_type

FROM pg_proc AS pg_fx

JOIN pg_namespace AS pg_ns
ON (pg_fx.pronamespace = pg_ns.oid)



LEFT JOIN LATERAL unnest(pg_fx.proargnames) WITH ORDINALITY AS pg_fx_argnames(argname, args_ordinality)
ON TRUE

LEFT JOIN LATERAL unnest(pg_fx.proargtypes) WITH ORDINALITY AS pg_fx_argtypes_oid(argtype_oid, args_ordinality)
ON (pg_fx_argnames.args_ordinality = pg_fx_argtypes_oid.args_ordinality)

LEFT JOIN pg_type AS pg_fx_argtypes
ON (pg_fx_argtypes.oid = pg_fx_argtypes_oid.argtype_oid)

LEFT JOIN LATERAL unnest(COALESCE(pg_fx.proargmodes, ARRAY['I'])) WITH ORDINALITY AS pg_fx_argmodes(argmode, args_ordinality)
ON (pg_fx_argnames.args_ordinality = pg_fx_argmodes.args_ordinality)

WHERE 1=1
AND pg_fx.proname = 'fx_get_posts'
AND pg_ns.nspname = 'liberanalytics'
AND pg_fx.prokind = 'f'
AND pg_fx_argmodes.argmode = 'i'
;


LANGUAGE plpgsql
STABLE
AS $$



END;
$$;