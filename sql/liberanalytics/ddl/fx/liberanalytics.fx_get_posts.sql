DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 pxFeatured SMALLINT DEFAULT 0
	,pxPostName VARCHAR(32) DEFAULT NULL
	,pxPostCategory VARCHAR(32) DEFAULT NULL
	,pxPostTags TEXT[] DEFAULT NULL
	,pxKeywords TEXT[] DEFAULT NULL
	,pxOrderBy VARCHAR(32) DEFAULT NULL
	,pxOrderByAscDesc VARCHAR(8) DEFAULT NULL
	,pxLimitBy INTEGER DEFAULT NULL
)
RETURNS TABLE(
     post_name VARCHAR(32)
    ,post_category VARCHAR(32)
    ,post_created TIMESTAMP
    ,short_desc VARCHAR(256)
    ,long_desc VARCHAR(256)
    ,featured SMALLINT
    ,post_img VARCHAR(64)
    ,featured_img VARCHAR(64)
    ,search_img VARCHAR(64)
)

LANGUAGE plpgsql
STABLE
AS $$

DECLARE
	strQueryBase VARCHAR(1024);
	strQueryAttrList VARCHAR(1024);
	strQuery VARCHAR(1024);
	strPostsAlias VARCHAR(8) := 'la';
	strPostTagsAlias VARCHAR(16) := 'post_tags';
	strPredFeatured VARCHAR(64);
	strPredPostName VARCHAR(64);
	strPredPostCategory VARCHAR(64);
	strPredPostTags VARCHAR(64);
	strPredPostKeywords VARCHAR(64);
	strOrderBy VARCHAR(64);
	strOrderByAscDesc VARCHAR(64);
	strLimitBy VARCHAR(64);

BEGIN

/*

*/

strPredFeatured := ' AND ' || strPostsAlias || '.featured = <= ' || pxFeatured


IF pxPostName IS NOT NULL THEN
	strPredPostName := ' AND ' || strPostsAlias || '.post_name = ' || QUOTE_LITERAL(pxPostName);
ELSE
	strPredPostName := '';
END IF;

IF pxPostCategory IS NOT NULL THEN
	strPredPostCategory := ' AND ' || strPostsAlias || '.post_category = ' || QUOTE_LITERAL(pxPostCategory);
ELSE
	strPredPostCategory := '';
END IF;

IF pxPostTags IS NOT NULL THEN
	strPredPostTags := ' AND ' || strPostTagsAlias || '.tag = ANY($1)';
ELSE
	strPredPostTags := '';
END IF;

IF pxOrderBy IS NOT NULL THEN
	strOrderBy := ' ORDER BY ' || pxOrderBy;
ELSE
	strOrderBy := '';
END IF;

IF pxOrderByAscDesc IS NOT NULL THEN
	strOrderByAscDesc := ' ' || pxOrderByAscDesc;
ELSE
	strOrderByAscDesc := '';
END IF;

IF pxLimitBy IS NOT NULL THEN
	strLimitBy := ' LIMIT ' || to_char(pxLimitBy, '999');
ELSE
	strLimitBy := '';
END IF;


/*

*/

strQueryAttrList := '

     la.post_name
    ,la.post_category
    ,la.post_created
    ,la.short_desc
    ,la.long_desc
    ,la.featured
    ,la.post_img
    ,la.featured_img
    ,la.search_img

';

strQueryBase :=

'SELECT ' || strQueryAttrList ||

'FROM liberanalytics.posts AS ' || strPostsAlias || '

LEFT JOIN liberanalytics.posts_tags AS ' || strPostTagsAlias || '
ON (la.post_name = post_tags.post_name)

WHERE 1=1
';

strQuery := 
	strQueryBase || 
	strPredFeatured || 
	strPredPostName || 
	strPredPostCategory ||
	strPredPostTags ||
	' GROUP BY ' || strQueryAttrList ||
	strOrderBy || 
	strOrderByAscDesc ||
	strLimitBy || ';';

RETURN QUERY EXECUTE strQuery USING pxPostTags;

--RAISE NOTICE 'Query String: %', strQuery;

END;
$$;