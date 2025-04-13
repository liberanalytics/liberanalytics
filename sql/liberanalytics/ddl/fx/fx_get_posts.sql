DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 pxFeatured BOOLEAN DEFAULT FALSE
	,pxPostName VARCHAR(32) DEFAULT NULL
	,pxPostCategory VARCHAR(32) DEFAULT NULL
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
AS $$

DECLARE
	strQueryBase VARCHAR(1024);
	strQuery VARCHAR(1024);
	strAlias VARCHAR(8) := 'la';
	strPredFeatured VARCHAR(64);
	strPredPostName VARCHAR(64);
	strPredPostCategory VARCHAR(64);
	strOrderBy VARCHAR(64);
	strOrderByAscDesc VARCHAR(64);
	strLimitBy VARCHAR(64);

BEGIN

IF pxFeatured IS NOT FALSE THEN
	strPredFeatured := ' AND ' || strAlias || '.featured > 0';
ELSE
	strPredFeatured := '';
END IF;

IF pxPostName IS NOT NULL THEN
	strPredPostName := ' AND ' || strAlias || '.post_name = ' || QUOTE_LITERAL(pxPostName);
ELSE
	strPredPostName := '';
END IF;

IF pxPostCategory IS NOT NULL THEN
	strPredPostCategory := ' AND ' || strAlias || '.post_category = ' || QUOTE_LITERAL(pxPostCategory);
ELSE
	strPredPostCategory := '';
END IF;

IF pxOrderBy IS NOT NULL THEN
	strOrderBy := ' ORDER BY ' || pxOrderBy;
ELSE
	strOrderBy := '';
END IF;

IF pxOrderByAscDesc IS NOT NULL THEN
	strOrderByAscDesc := pxOrderByAscDesc
ELSE
	strOrderByAscDesc := '';
END IF;

IF pxLimitBy IS NOT NULL THEN
	strLimitBy := ' LIMIT ' || to_char(pxLimitBy, '999');
ELSE
	strLimitBy := '';
END IF;




strQueryBase :=

'SELECT 

     la.post_name
    ,la.post_category
    ,la.post_created
    ,la.short_desc
    ,la.long_desc
    ,la.featured
    ,la.post_img
    ,la.featured_img
    ,la.search_img

FROM liberanalytics.posts AS la

WHERE 1=1
';

strQuery := strQueryBase || strPredFeatured || strPredPostName || strPredPostCategory || strOrderBy || strLimitBy || ';';

RETURN QUERY EXECUTE strQuery;

--RAISE NOTICE 'Query String: %', strQuery;

END;
$$;