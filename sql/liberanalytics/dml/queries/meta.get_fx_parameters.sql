SELECT

	 px_order
	,px_name
	,px_type
	,px_default_value

FROM meta.fx_get_fx_pxs
(
     :px_fx_schema
    ,:px_fx_name
)
