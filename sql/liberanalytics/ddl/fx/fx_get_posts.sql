DROP FUNCTION IF EXISTS liberanalytics.fx_get_posts;

CREATE OR REPLACE FUNCTION liberanalytics.fx_get_posts
(
	 pxFeatured BOOLEAN
	,pxPostName VARCHAR(32) DEFAULT NULL
	,pxPostCategory VARCHAR(32) DEFAULT NULL
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
BEGIN

RETURN QUERY

SELECT 

     la.post_name
    ,la.post_category
    ,la.post_created
    ,la.short_desc
    ,la.long_desc
    ,la.featured
    ,la.post_img
    ,la.featured_img
    ,la.search_img

FROM liberanalytics.posts AS la;

END;
$$;