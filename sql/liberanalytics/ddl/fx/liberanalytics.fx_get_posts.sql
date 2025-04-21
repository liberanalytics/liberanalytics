DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 pxFeatured SMALLINT DEFAULT 0
)

RETURNS TEXT

LANGUAGE plpgsql
STABLE
AS $$

DECLARE
	txtQueryBase TEXT;
	txtQueryAttrList TEXT;
	txtQuery TEXT
	txtPostsAlias TEXT := 'la';
	txtPostTagsAlias TEXT := 'post_tags';
	txtPredFeatured TEXT;
	txtPredPostName TEXT;
	txtPredPostCategory TEXT;
	txtPredPostTags TEXT;
	txtPredPostKeywords TEXT;
	txtOrderBy TEXT;
	txtOrderByAscDesc TEXT;
	txtLimitBy TEXT;

BEGIN

txtPredFeatured := meta.fx_get_dynamic_sql_clause('AND', txtPostsAlias, 'featured', '<=', NULL, pxFeatured);



/*
IF pxPostTags IS NOT NULL THEN
	txtPredPostTags := ' AND ' || txtPostTagsAlias || '.tag = ANY($1)';
ELSE
	txtPredPostTags := '';
END IF;
*/

txtQueryAttrList := '

	' || txtPostsAlias || '.post_name' || E'\n' ||
	',' || txtPostsAlias || '.post_category' || E'\n' ||
	',' || txtPostsAlias || '.post_created' || E'\n' ||
	',' || txtPostsAlias || '.short_desc' || E'\n' ||
	',' || txtPostsAlias || '.long_desc' || E'\n' ||
	',' || txtPostsAlias || '.post_img' || E'\n' ||
	',' || txtPostsAlias || '.featured_img' || E'\n' ||
	',' || txtPostsAlias || '.search_img' || E'\n';

txtQueryBase :=

'SELECT ' || txtQueryAttrList ||

'FROM liberanalytics.posts AS ' || txtPostsAlias || '

LEFT JOIN liberanalytics.posts_tags AS ' || txtPostTagsAlias || '
ON (la.post_name = post_tags.post_name)

WHERE 1=1
';

txtQuery := 
	txtQueryBase || 
	txtPredFeatured ||

';';

--RETURN QUERY EXECUTE txtQuery USING pxPostTags;

RAISE NOTICE 'Query txting: %', txtQuery;

END;
$$;