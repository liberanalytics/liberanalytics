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
BEGIN

RETURN QUERY
SELECT

    pt.post_tag

FROM refdata.post_tags AS pt
WHERE px_post_tag_term IS NULL
OR pt.post_tag ILIKE px_post_tag_term || '%' 
ORDER BY pt.post_tag
LIMIT 10;

END;
$$;
