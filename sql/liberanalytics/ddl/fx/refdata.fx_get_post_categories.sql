DROP FUNCTION IF EXISTS refdata.fx_get_post_categories;

CREATE OR REPLACE FUNCTION refdata.fx_get_post_categories
(
    px_post_category TEXT DEFAULT NULL
)

RETURNS TABLE
(
    post_category VARCHAR(32)
)

LANGUAGE plpgsql
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_post_categories_alias TEXT := 'pc';
    txt_pred_post_category TEXT;

BEGIN

txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_post_categories_alias, 'post_category', '=', QUOTE_LITERAL(px_post_category), NULL, NULL);
-- strPredPostCategory = COALESCE('AND ' ||  strPostCategoriesAlias || '.post_category = ' || QUOTE_LITERAL(pxPostCategory), '');

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

RETURN QUERY EXECUTE txt_query;

--RAISE NOTICE 'Query String: %', txt_query;

END;
$$