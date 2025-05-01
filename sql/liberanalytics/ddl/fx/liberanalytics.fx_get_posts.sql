DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 px_featured INTEGER DEFAULT 1000
	,px_post_name[] TEXT DEFAULT NULL
	,px_post_category[] TEXT DEFAULT NULL
	,px_post_tags TEXT[] DEFAULT NULL
	,px_post_keywords TEXT[] DEFAULT NULL
	,px_order_by TEXT DEFAULT NULL
	,px_order_by_ascdesc TEXT DEFAULT NULL
	,px_limit INTEGER DEFAULT NULL	
)

RETURNS TABLE
(
     post_name VARCHAR(32)
    ,post_category VARCHAR(32)
    ,post_created TIMESTAMP
    ,short_desc VARCHAR(256)
    ,long_desc VARCHAR(256)
    ,post_img VARCHAR(64)
    ,featured_img VARCHAR(64)
    ,search_img VARCHAR(64)
)

LANGUAGE plpgsql
STABLE
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_group_by TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_posts_alias TEXT := 'la';
	txt_post_tags_alias TEXT := 'post_tags';
	txt_pred_featured TEXT;
	txt_pred_post_name TEXT;
	txt_pred_post_category TEXT;
	txt_pred_post_tags TEXT;
	txt_pred_post_keywords TEXT;
	txt_order_by TEXT;
	txt_order_by_ascdesc TEXT;
	txt_limit TEXT;

BEGIN

txt_pred_featured := meta.fx_get_dynamic_sql_clause('AND', txt_posts_alias, 'featured', '<=', NULL, px_featured, NULL);
txt_pred_post_name := meta.fx_get_dynamic_sql_clause('AND', txt_posts_alias, 'post_name', '=', px_post_name, NULL, 1);
txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_posts_alias, 'post_category', '=', px_post_category, NULL, 2);
txt_order_by := meta.fx_get_dynamic_sql_clause('ORDER BY', txt_posts_alias, px_order_by, px_order_by_ascdesc, NULL, NULL, NULL); 
txt_limit := meta.fx_get_dynamic_sql_clause('LIMIT', NULL, NULL, NULL, NULL, px_limit, NULL); 


/*
IF pxPostTags IS NOT NULL THEN
	txtPredPostTags := ' AND ' || txtPostTagsAlias || '.tag = ANY($1)';
ELSE
	txtPredPostTags := '';
END IF;
*/


txt_query_attr_list := 

	txt_posts_alias || '.post_name' || E'\n' ||
	',' || txt_posts_alias || '.post_category' || E'\n'
	',' || txt_posts_alias || '.post_created' || E'\n' ||
	',' || txt_posts_alias || '.short_desc' || E'\n' ||
	',' || txt_posts_alias || '.long_desc' || E'\n' ||
	',' || txt_posts_alias || '.post_img' || E'\n' ||
	',' || txt_posts_alias || '.featured_img' || E'\n' ||
	',' || txt_posts_alias || '.search_img' || E'\n\n';

txt_query_group_by :=

	E'\n\n' || 'GROUP BY' || E'\n\n' || txt_query_attr_list;

txt_query_base :=

'SELECT 

' || txt_query_attr_list ||

'FROM liberanalytics.posts AS ' || txt_posts_alias || '

LEFT JOIN liberanalytics.post_tags AS ' || txt_post_tags_alias || '
ON (la.post_name = post_tags.post_name)

WHERE 1=1
';

txt_query := 
	txt_query_base || 
	txt_pred_featured ||
	txt_pred_post_name ||
	txt_pred_post_category ||
	txt_query_group_by ||
	txt_order_by ||
	txt_limit ||

';';

RETURN QUERY EXECUTE txt_query
USING 
	 px_post_name 
	,px_post_category;

--RAISE NOTICE 'Query txting: %', txt_query;
--USING pxPostTags;

END;
$$;