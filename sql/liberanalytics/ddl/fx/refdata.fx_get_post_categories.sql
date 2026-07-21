-- Fx: refdata.fx_get_post_categories

DROP FUNCTION IF EXISTS refdata.fx_get_post_categories(text[]);

CREATE OR REPLACE FUNCTION refdata.fx_get_post_categories
(
	px_post_category text[] DEFAULT NULL::text[]
)

RETURNS TABLE
(
    post_category character varying
) 

LANGUAGE 'plpgsql'
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_post_categories_alias TEXT := 'pc';
    int_ordinal_post_category INTEGER := CASE WHEN px_post_category IS NULL THEN NULL ELSE 1 END;
    txt_pred_post_category TEXT;

BEGIN

txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_post_categories_alias, 'post_category', '=', NULL, NULL, int_ordinal_post_category);

txt_query_attr_list := '

' || txt_post_categories_alias || '.post_category

';

txt_query_base :=

'SELECT ' || txt_query_attr_list ||

'FROM refdata.post_categories AS ' || txt_post_categories_alias ||

'
WHERE 1=1
';

txt_query :=
    txt_query_base ||
    txt_pred_post_category ||

';';

RETURN QUERY EXECUTE txt_query
USING px_post_category;

END;
$$;

ALTER FUNCTION refdata.fx_get_post_categories(text[])
    OWNER TO liberanalytics;
