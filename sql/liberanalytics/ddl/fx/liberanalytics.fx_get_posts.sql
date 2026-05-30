DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 px_featured INTEGER DEFAULT 1000
	,px_post_name TEXT[] DEFAULT NULL
	,px_post_category TEXT[] DEFAULT NULL
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
	,intro VARCHAR(512)
    ,post_img VARCHAR(64)
	,img_01_width INTEGER
	,img_02_width INTEGER
	,img_03_width INTEGER
	,img_04_width INTEGER
)

LANGUAGE plpgsql
STABLE
AS $$

DECLARE

txt_query_attr_list TEXT;
txt_query_base TEXT;
txt_query TEXT;
txt_posts_alias TEXT := 'la';
txt_post_img_01_alias TEXT := 'img_01';
txt_post_img_02_alias TEXT := 'img_02';
txt_post_img_03_alias TEXT := 'img_03';
txt_post_img_04_alias TEXT := 'img_04';
txt_post_tags_alias TEXT := 'post_tags';
txt_pred_featured TEXT;
txt_pred_post_name TEXT;
txt_pred_post_category TEXT;
txt_pred_post_tags TEXT;
txt_pred_post_keywords TEXT;
int_ordinal_post_name INTEGER := 1;
int_ordinal_post_category INTEGER := 2;
int_ordinal_post_tags INTEGER := 3;
txt_order_by TEXT;
txt_order_by_ascdesc TEXT;
txt_limit TEXT;

BEGIN

txt_pred_featured := meta.fx_get_dynamic_sql_clause('AND', txt_posts_alias, 'featured', '<=', NULL, px_featured, NULL);
txt_pred_post_name := meta.fx_get_dynamic_sql_clause('AND', txt_posts_alias, 'post_name', '=', QUOTE_LITERAL(px_post_name[1])::TEXT, NULL, int_ordinal_post_name);
txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_posts_alias, 'post_category', '=', QUOTE_LITERAL(px_post_category[1])::TEXT, NULL, int_ordinal_post_category);
txt_order_by := meta.fx_get_dynamic_sql_clause('ORDER BY', txt_posts_alias, px_order_by, px_order_by_ascdesc, NULL, NULL, NULL); 
txt_limit := meta.fx_get_dynamic_sql_clause('LIMIT', NULL, NULL, NULL, NULL, px_limit, NULL);

IF px_post_tags IS NOT NULL THEN
	txt_pred_post_tags := 
	format(
		E'\nAND ($%s IS NULL OR EXISTS
		(SELECT 1
		FROM liberanalytics.post_tags AS %I
		WHERE  %I.post_name = %I.post_name
		AND %I.post_tag = ANY($%s)
		))',
		int_ordinal_post_tags,
		txt_post_tags_alias,
		txt_post_tags_alias,
		txt_posts_alias,
		txt_post_tags_alias,
		int_ordinal_post_tags
		);
ELSE
	txt_pred_post_tags := '';
END IF;

txt_query_attr_list := 

	txt_posts_alias || '.post_name' || E'\n' ||
	',' || txt_posts_alias || '.post_category' || E'\n'
	',' || txt_posts_alias || '.post_created' || E'\n' ||
	',' || txt_posts_alias || '.short_desc' || E'\n' ||
	',' || txt_posts_alias || '.long_desc' || E'\n' ||
	',' || txt_posts_alias || '.intro' || E'\n' ||
	',' || txt_post_img_01_alias || '.post_img' || E'\n' ||
	',' || txt_post_img_01_alias || '.post_img_width AS img_width_01' || E'\n' ||
	',' || txt_post_img_02_alias || '.post_img_width AS img_width_02' || E'\n' ||
	',' || txt_post_img_03_alias || '.post_img_width AS img_width_03' || E'\n' ||
	',' || txt_post_img_04_alias || '.post_img_width AS img_width_04 '
	
	
	;


/*

*/
txt_query_base :=

'SELECT 

' || txt_query_attr_list ||

'FROM liberanalytics.posts AS ' || txt_posts_alias || '

INNER JOIN liberanalytics.post_img AS ' || txt_post_img_01_alias || '

ON (
' || txt_posts_alias || '.post_name = ' || txt_post_img_01_alias || '.post_name
AND ' || txt_post_img_01_alias || '.post_img_slot = 1
AND ' || txt_post_img_01_alias || '.post_img_number = 1
)

INNER JOIN liberanalytics.post_img AS ' || txt_post_img_02_alias || '

ON (
' || txt_posts_alias || '.post_name = ' || txt_post_img_02_alias || '.post_name
AND ' || txt_post_img_02_alias || '.post_img_slot = 1
AND ' || txt_post_img_02_alias || '.post_img_number = 2
)

INNER JOIN liberanalytics.post_img AS ' || txt_post_img_03_alias || '

ON (
' || txt_posts_alias || '.post_name = ' || txt_post_img_03_alias || '.post_name
AND ' || txt_post_img_03_alias || '.post_img_slot = 1
AND ' || txt_post_img_03_alias || '.post_img_number = 3
)

INNER JOIN liberanalytics.post_img AS ' || txt_post_img_04_alias || '

ON (
' || txt_posts_alias || '.post_name = ' || txt_post_img_04_alias || '.post_name
AND ' || txt_post_img_04_alias || '.post_img_slot = 1
AND ' || txt_post_img_04_alias || '.post_img_number = 4
)

WHERE 1=1
';

txt_query := 
	txt_query_base || 
	txt_pred_featured ||
	txt_pred_post_name ||
	txt_pred_post_category ||
	txt_pred_post_tags ||
	txt_order_by ||
	txt_limit ||

';';

RETURN QUERY EXECUTE txt_query
USING 
	 px_post_name 
	,px_post_category
	,px_post_tags;

END;
$$;
