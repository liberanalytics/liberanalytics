-- Fx: refdata.fx_get_post_tags

DROP FUNCTION IF EXISTS refdata.fx_get_post_tags;

CREATE OR REPLACE FUNCTION refdata.fx_get_post_tags
(
     px_post_tags TEXT[] DEFAULT NULL
)

RETURNS TABLE
(
    post_tag VARCHAR(32)
)

LANGUAGE plpgsql
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_post_tags_alias TEXT := 'pt';
    int_ordinal_post_tags INTEGER := CASE WHEN px_post_tags IS NULL THEN NULL ELSE 1 END;
    txt_pred_post_tags TEXT;

BEGIN

txt_pred_post_tags := meta.fx_get_dynamic_sql_clause('AND', txt_post_tags_alias, 'post_tag', '=', NULL, NULL, NULL, int_ordinal_post_tags);

txt_query_attr_list := '

' || txt_post_tags_alias || '.post_tag

';

txt_query_base :=

'SELECT ' || txt_query_attr_list ||

'FROM refdata.post_tags AS ' || txt_post_tags_alias ||

'
WHERE 1=1
AND ' || txt_post_tags_alias || '.post_tag = ANY($1)
';

txt_query :=
    txt_query_base ||
    txt_pred_post_tags ||

';';

RETURN QUERY EXECUTE txt_query
USING 
     px_post_tags;

END;
$$;