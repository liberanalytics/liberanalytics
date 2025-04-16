DROP FUNCTION IF EXISTS refdata.fx_get_post_categories;

CREATE OR REPLACE FUNCTION refdata.fx_get_post_categories
(
    pxPostCategory VARCHAR(32) DEFAULT NULL
)

RETURNS TABLE
(
    post_category VARCHAR(32)
)

LANGUAGE plpgsql
AS $$

DECLARE
	strQueryBase VARCHAR(1024);
	strQueryAttrList VARCHAR(1024);
	strQuery VARCHAR(1024);
    strPostCategoriesAlias VARCHAR(8) := 'pc';
    strPredPostCategory VARCHAR(64);

BEGIN

strPredPostCategory = COALESCE('AND ' ||  strPostCategoriesAlias || '.post_category = ' || QUOTE_LITERAL(pxPostCategory), '');

strQueryAttrList := '

' || strPostCategoriesAlias || '.post_category

';

strQueryBase :=

'SELECT ' || strQueryAttrList ||

'FROM refdata.post_category AS ' || strPostCategoriesAlias ||

'
WHERE 1=1
';


strQuery :=
    strQueryBase ||
    strPredPostCategory;

RETURN QUERY EXECUTE strQuery;

--RAISE NOTICE 'Query String: %', strQuery;

END;
$$