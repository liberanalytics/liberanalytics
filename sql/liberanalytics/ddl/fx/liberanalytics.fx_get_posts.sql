DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 pxFeatured INTEGER DEFAULT 0
	,pxPostName TEXT DEFAULT NULL
	,pxPostCategory TEXT DEFAULT NULL
	,pxPostTags TEXT[] DEFAULT NULL
	,pxKeywords TEXT[] DEFAULT NULL
	,pxOrderBy TEXT DEFAULT NULL
	,pxOrderByAscDesc TEXT DEFAULT NULL
	,pxLimit INTEGER DEFAULT NULL	
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
	txtQueryBase TEXT;
	txtQueryAttrList TEXT;
	txtQuery TEXT;
	txtPostsAlias TEXT := 'la';
	txtPostTagsAlias TEXT := 'post_tags';
	txtPredFeatured TEXT;
	txtPredPostName TEXT;
	txtPredPostCategory TEXT;
	txtPredPostTags TEXT;
	txtPredPostKeywords TEXT;
	txtOrderBy TEXT;
	txtOrderByAscDesc TEXT;
	txtLimit TEXT;

BEGIN

txtPredFeatured := meta.fx_get_dynamic_sql_clause('AND', txtPostsAlias, 'featured', '<=', NULL, pxFeatured);
txtPredPostName := meta.fx_get_dynamic_sql_clause('AND', txtPostsAlias, 'post_name', '=', QUOTE_LITERAL(pxPostName), NULL);
txtPredPostCategory := meta.fx_get_dynamic_sql_clause('AND', txtPostsAlias, 'post_category', '=', QUOTE_LITERAL(pxPostCategory), NULL);
txtOrderBy := meta.fx_get_dynamic_sql_clause('ORDER BY', txtPostsAlias, pxOrderBy, pxOrderByAscDesc, NULL, NULL); 
txtLimit := meta.fx_get_dynamic_sql_clause('LIMIT', NULL, NULL, NULL, NULL, pxLimit); 


/*
IF pxPostTags IS NOT NULL THEN
	txtPredPostTags := ' AND ' || txtPostTagsAlias || '.tag = ANY($1)';
ELSE
	txtPredPostTags := '';
END IF;
*/


txtQueryAttrList := 

	txtPostsAlias || '.post_name' || E'\n' ||
	',' || txtPostsAlias || '.post_category' || E'\n' ||
	',' || txtPostsAlias || '.post_created' || E'\n' ||
	',' || txtPostsAlias || '.short_desc' || E'\n' ||
	',' || txtPostsAlias || '.long_desc' || E'\n' ||
	',' || txtPostsAlias || '.post_img' || E'\n' ||
	',' || txtPostsAlias || '.featured_img' || E'\n' ||
	',' || txtPostsAlias || '.search_img' || E'\n\n';

txtQueryBase :=

'SELECT 

' || txtQueryAttrList ||

'FROM liberanalytics.posts AS ' || txtPostsAlias || '

LEFT JOIN liberanalytics.posts_tags AS ' || txtPostTagsAlias || '
ON (la.post_name = post_tags.post_name)

WHERE 1=1
';

txtQuery := 
	txtQueryBase || 
	txtPredFeatured ||
	txtPredPostName ||
	txtPredPostCategory ||
	txtOrderBy ||
	txtLimit ||

';';

RETURN QUERY EXECUTE txtQuery;
--USING pxPostTags;

--RAISE NOTICE 'Query txting: %', txtQuery;

END;
$$;