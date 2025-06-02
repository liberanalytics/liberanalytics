DROP FUNCTION IF EXISTS refdata.fx_get_post_tags_like(TEXT);

CREATE OR REPLACE FUNCTION refdata.fx_get_post_tags_like
(
    px_post_tag_term TEXT DEFAULT NULL
)

RETURNS TABLE
(
    post_tag VARCHAR(32)
)

STABLE
LANGUAGE plpgsql
AS $$

SELECT

    pt.post_tag

FROM refdata.post_tags AS pt
WHERE 1=1
AND post_tag ILIKE px_post_tag_term || % 
ORDER BY post_tag
LIMIT 10;

--RAISE NOTICE 'Query String: %', txt_query;
$$
