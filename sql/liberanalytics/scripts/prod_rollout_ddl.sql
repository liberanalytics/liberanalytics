-- =========================================================
-- STEP 01: Create role(s)
-- =========================================================

-- Role: liberanalytics

DO
$$
BEGIN

IF NOT EXISTS (
    SELECT 1 FROM pg_catalog.pg_roles WHERE rolname = 'liberanalytics'
)
THEN
    CREATE ROLE liberanalytics WITH LOGIN;
ELSE
    RAISE NOTICE 'Role liberanalytics already exists, skipping.';
END IF;

END;

$$ LANGUAGE plpgsql;

-- =========================================================
-- STEP 02: Create database
-- =========================================================

CREATE DATABASE liberanalytics
    TEMPLATE template0
    ENCODING 'UTF-8'
    OWNER liberanalytics;

-- =========================================================
-- STEP 03: Create schemas
-- =========================================================

CREATE SCHEMA IF NOT EXISTS liberanalytics AUTHORIZATION liberanalytics;
CREATE SCHEMA IF NOT EXISTS refdata AUTHORIZATION liberanalytics;
CREATE SCHEMA IF NOT EXISTS meta AUTHORIZATION liberanalytics;

-- =========================================================
-- STEP 04: Create tables
-- ========================================================= 

-- Table: liberanalytics.posts

DROP TABLE IF EXISTS liberanalytics.posts;

CREATE TABLE IF NOT EXISTS liberanalytics.posts
(
    post_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    short_desc character varying(256) COLLATE pg_catalog."default" NOT NULL,
    long_desc character varying(256) COLLATE pg_catalog."default" NOT NULL,
    intro character varying(512) COLLATE pg_catalog."default" NOT NULL,
    featured smallint NOT NULL,
    CONSTRAINT posts_pk PRIMARY KEY (post_name),
    
    CONSTRAINT posts_post_category_fkey FOREIGN KEY (post_category)
        REFERENCES refdata.post_categories (post_category) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS liberanalytics.posts
    OWNER to liberanalytics;

-- NOTICE:  table "posts" does not exist, skipping
-- ERROR:  relation "refdata.post_categories" does not exist 
-- SQL state: 42P01

-- Table: refdata.post_categories

DROP TABLE IF EXISTS refdata.post_categories;

CREATE TABLE IF NOT EXISTS refdata.post_categories
(
    post_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_category_desc character varying(32) COLLATE pg_catalog."default",
    post_category_created timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT post_categories_pk PRIMARY KEY (post_category)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS refdata.post_categories
    OWNER to liberanalytics;

-- Table: liberanalytics.post_tags

DROP TABLE IF EXISTS liberanalytics.post_tags;

CREATE TABLE IF NOT EXISTS liberanalytics.post_tags
(
    post_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_tag character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_tag_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT liberanalytics_post_tags_pk PRIMARY KEY (post_name, post_tag),
    
    CONSTRAINT post_tags_post_name_fkey FOREIGN KEY (post_name)
        REFERENCES liberanalytics.posts (post_name) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,

     CONSTRAINT post_tags_post_tag_fkey FOREIGN KEY (post_tag)
        REFERENCES refdata.post_tags (post_tag) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS liberanalytics.post_tags
    OWNER to liberanalytics;

-- ERROR:  relation "refdata.post_tags" does not exist 
-- SQL state: 42P01

-- Table: refdata.post_tags

DROP TABLE IF EXISTS refdata.post_tags;

CREATE TABLE refdata.post_tags (

    post_tag character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_tag_foundational boolean DEFAULT FALSE,
    post_tag_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT refdata_post_tags_pk PRIMARY KEY(post_tag),

    CONSTRAINT post_tags_category_fkey FOREIGN KEY (post_category)
    REFERENCES refdata.post_categories (post_category) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION

)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS refdata.post_tags
OWNER to liberanalytics;

-- Table: liberanalytics.post_img

DROP TABLE IF EXISTS liberanalytics.post_img;

CREATE TABLE liberanalytics.post_img (

  post_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
  post_img character varying(32) COLLATE pg_catalog."default" NOT NULL,
  post_img_slot   smallint NOT NULL,   -- 1=hero, 2=thumb, 3=inline1, etc.
  post_img_number smallint NOT NULL,   -- 1..N (your size index)
  post_img_width  integer  NOT NULL,
  post_img_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT post_img_pk
    PRIMARY KEY (post_name, post_img_slot, post_img_number),

    CONSTRAINT post_img_pos_ck CHECK (
        post_img_slot > 0 AND 
        post_img_number > 0 AND 
        post_img_width > 0
    ),

    CONSTRAINT post_img_post_name_fkey FOREIGN KEY (post_name)
        REFERENCES liberanalytics.posts (post_name) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE

);

CREATE INDEX post_img_ix
  ON liberanalytics.post_img (post_name, post_img_slot, post_img_number);

-- Table: liberanalytics.contact

DROP TABLE IF EXISTS liberanalytics.contact;

CREATE TABLE IF NOT EXISTS liberanalytics.contact
(
    contact_id INTEGER GENERATED ALWAYS AS IDENTITY,
    first_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    last_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    email character varying(32) COLLATE pg_catalog."default" NOT NULL,
    contact_subject character varying(32) COLLATE pg_catalog."default" NOT NULL,
    contact_message character varying(512) COLLATE pg_catalog."default" NOT NULL,
    contact_sent timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT contact_pk PRIMARY KEY (contact_id)   
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS liberanalytics.contact
    OWNER to liberanalytics;

-- =========================================================
-- STEP 05: Create functions
-- ========================================================= 

-- Fx: meta.fx_get_dynamic_sql_clause

DROP FUNCTION IF EXISTS meta.fx_get_dynamic_sql_clause;

CREATE OR REPLACE FUNCTION meta.fx_get_dynamic_sql_clause
(
     pxClauseType TEXT
	,pxAlias TEXT
	,pxColumn TEXT
	,pxOperator TEXT
	,pxValueString TEXT
	,pxValueInt INTEGER
	,pxAnyOrdinalPosition INTEGER
)

RETURNS TEXT

LANGUAGE plpgsql
AS $$

DECLARE
	txtDynamicSQLClause TEXT;

BEGIN

IF pxClauseType = 'AND' THEN
	IF (pxValueString IS NOT NULL OR pxValueInt IS NOT NULL) THEN
		txtDynamicSQLClause :=

			E'\n' ||
			pxClauseType || ' ' || 
			COALESCE(pxAlias || '.', '') || 
			pxColumn || ' ' ||
			pxOperator || ' ' ||

			CASE
				WHEN pxAnyOrdinalPosition IS NOT NULL THEN
					'ANY($' || CAST(pxAnyOrdinalPosition AS TEXT) || ')'
				ELSE
					COALESCE( CAST(pxValueInt AS TEXT), CAST('''' || pxValueString || '''' AS TEXT) )
			END;

		ELSE
			txtDynamicSQLClause := '';
		END IF;
	
ELSIF pxClauseType = 'ORDER BY' THEN
	IF pxColumn IS NOT NULL THEN
		txtDynamicSQLClause :=
			E'\n' ||
			pxClauseType || ' ' || 
			pxAlias || '.' || 
			pxColumn || ' ' ||
			pxOperator;
	ELSE
		txtDynamicSQLClause := '';
	END IF;

ELSIF pxClauseType = 'LIMIT' THEN	
	IF pxValueInt IS NOT NULL THEN
		txtDynamicSQLClause :=
			E'\n' ||
			pxClauseType || ' ' ||
			CAST(pxValueInt AS TEXT);
	ELSE
		txtDynamicSQLClause := '';
	END IF;
	
END IF;

RETURN txtDynamicSQLClause;

END;
$$;

-- Fx: refdata.fx_get_post_categories

DROP FUNCTION IF EXISTS refdata.fx_get_post_categories(text[]);

CREATE OR REPLACE FUNCTION refdata.fx_get_post_categories
(
	px_post_category text[] DEFAULT NULL::text[]
)

RETURNS TABLE
(
    post_category character varying
) 

LANGUAGE 'plpgsql'
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_post_categories_alias TEXT := 'pc';
    int_ordinal_post_category INTEGER := CASE WHEN px_post_category IS NULL THEN NULL ELSE 1 END;
    txt_pred_post_category TEXT;

BEGIN

txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_post_categories_alias, 'post_category', '=', NULL, NULL, int_ordinal_post_category);

txt_query_attr_list := '

' || txt_post_categories_alias || '.post_category

';

txt_query_base :=

'SELECT ' || txt_query_attr_list ||

'FROM refdata.post_categories AS ' || txt_post_categories_alias ||

'
WHERE 1=1
';

txt_query :=
    txt_query_base ||
    txt_pred_post_category ||

';';

RETURN QUERY EXECUTE txt_query
USING px_post_category;

END;
$$;

ALTER FUNCTION refdata.fx_get_post_categories(text[])
    OWNER TO liberanalytics;

-- Fx: refdata.fx_get_post_categories

DROP FUNCTION IF EXISTS refdata.fx_get_post_categories(text[]);

CREATE OR REPLACE FUNCTION refdata.fx_get_post_categories
(
	px_post_category text[] DEFAULT NULL::text[]
)

RETURNS TABLE
(
    post_category character varying
) 

LANGUAGE 'plpgsql'
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_post_categories_alias TEXT := 'pc';
    int_ordinal_post_category INTEGER := CASE WHEN px_post_category IS NULL THEN NULL ELSE 1 END;
    txt_pred_post_category TEXT;

BEGIN

txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_post_categories_alias, 'post_category', '=', NULL, NULL, int_ordinal_post_category);

txt_query_attr_list := '

' || txt_post_categories_alias || '.post_category

';

txt_query_base :=

'SELECT ' || txt_query_attr_list ||

'FROM refdata.post_categories AS ' || txt_post_categories_alias ||

'
WHERE 1=1
';

txt_query :=
    txt_query_base ||
    txt_pred_post_category ||

';';

RETURN QUERY EXECUTE txt_query
USING px_post_category;

END;
$$;

ALTER FUNCTION refdata.fx_get_post_categories(text[])
    OWNER TO liberanalytics;

-- Fx: meta.fx_get_fx_pxs

DROP FUNCTION IF EXISTS meta.fx_get_fx_pxs;

CREATE OR REPLACE FUNCTION meta.fx_get_fx_pxs
(
     px_fx_schema TEXT
	,px_fx_name TEXT
)

RETURNS TABLE
(
	 px_schema_name NAME
	,px_function_name NAME
	,px_order BIGINT
	,px_name TEXT
	,px_type NAME
	,px_default_value TEXT
)

LANGUAGE plpgsql
STABLE
AS $$

BEGIN

RETURN QUERY

SELECT

	 pg_proc_arg_defaults.schema_name
	,pg_proc_arg_defaults.fx_name
	,pg_proc_arg_order.arg_ordinality 
	,pg_proc_arg_defaults.arg_name
	,pg_proc_arg_defaults.typname
	,pg_proc_arg_defaults.default_value

FROM

(SELECT

	 pg_proc_arg_defaults_inner.schema_name
	,pg_proc_arg_defaults_inner.fx_name
	,pg_proc_arg_defaults_inner.arg_name
	,pg_proc_arg_defaults_inner.default_value
	,pg_types.typname

FROM

(SELECT

	 pg_ns_args_inner.nspname AS schema_name
	,pg_proc_args_inner.proname AS fx_name	
	,unnest(pg_proc_args_inner.proargmodes) AS arg_mode
	,unnest(pg_proc_args_inner.proargnames) AS arg_name
	,unnest(regexp_split_to_array(pg_get_expr(pg_proc_args_inner.proargdefaults, 0), ',\s*')) AS default_value
	,unnest(pg_proc_args_inner.proargtypes) AS arg_oid

FROM pg_proc AS pg_proc_args_inner

JOIN pg_namespace AS pg_ns_args_inner
ON (pg_proc_args_inner.pronamespace = pg_ns_args_inner.oid
AND pg_ns_args_inner.nspname NOT IN ('pg_catalog', 'information_schema'))

WHERE 1=1
AND pg_proc_args_inner.proargdefaults IS NOT NULL
AND pg_ns_args_inner.nspname = px_fx_schema
AND pg_proc_args_inner.proname = px_fx_name

) AS pg_proc_arg_defaults_inner

INNER JOIN pg_type AS pg_types ON
(pg_proc_arg_defaults_inner.arg_oid = pg_types.oid)

WHERE 1=1
AND pg_proc_arg_defaults_inner.arg_mode = 'i'

) AS pg_proc_arg_defaults

INNER JOIN

(SELECT

	 pg_ns_order_inner.nspname AS schema_name
	,pg_proc_order_inner.proname AS fx_name
	,pg_fx_argnames.arg_name
	,pg_fx_argnames.arg_ordinality

FROM pg_proc AS pg_proc_order_inner

JOIN pg_namespace AS pg_ns_order_inner
ON (pg_proc_order_inner.pronamespace = pg_ns_order_inner.oid)

LEFT JOIN LATERAL unnest(pg_proc_order_inner.proargnames) WITH ORDINALITY AS pg_fx_argnames(arg_name, arg_ordinality) ON TRUE

WHERE 1=1
AND pg_proc_order_inner.proargdefaults IS NOT NULL
AND pg_ns_order_inner.nspname = px_fx_schema
AND pg_proc_order_inner.proname = px_fx_name

) AS pg_proc_arg_order

ON (pg_proc_arg_defaults.schema_name = pg_proc_arg_order.schema_name
AND pg_proc_arg_defaults.fx_name = pg_proc_arg_order.fx_name
AND pg_proc_arg_defaults.arg_name = pg_proc_arg_order.arg_name);

END;
$$;


-- Fx: refdata.fx_get_post_tags_like

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

-- Fx: refdata.fx_get_post_tags

DROP FUNCTION IF EXISTS refdata.fx_get_post_tags;

CREATE OR REPLACE FUNCTION refdata.fx_get_post_tags
(
     px_post_tags TEXT[] DEFAULT NULL
    ,px_post_category TEXT[] DEFAULT NULL
)

RETURNS TABLE
(
    post_tag VARCHAR(32)
)

LANGUAGE plpgsql
AS $$

DECLARE
	txt_query_attr_list TEXT;
	txt_query_base TEXT;
	txt_query TEXT;
	txt_post_tags_alias TEXT := 'pt';
    int_ordinal_post_tags INTEGER := CASE WHEN px_post_tags IS NULL THEN NULL ELSE 1 END;
    int_ordinal_post_category INTEGER := CASE WHEN px_post_category IS NULL THEN NULL ELSE 2 END;
    txt_pred_post_tags TEXT;
    txt_pred_post_category TEXT;

BEGIN

txt_pred_post_tags := meta.fx_get_dynamic_sql_clause('AND', txt_post_tags_alias, 'post_tag', '=', NULL, NULL, int_ordinal_post_tags);
txt_pred_post_category := meta.fx_get_dynamic_sql_clause('AND', txt_post_tags_alias, 'post_category', '=', NULL, NULL, int_ordinal_post_category);

txt_query_attr_list := '

' || txt_post_tags_alias || '.post_tag

';

txt_query_base :=

'SELECT ' || txt_query_attr_list ||

'FROM refdata.post_tags AS ' || txt_post_tags_alias ||

'
WHERE 1=1
';

txt_query :=
    txt_query_base ||
    txt_pred_post_tags ||
    txt_pred_post_category ||

';';

RETURN QUERY EXECUTE txt_query
USING 
     px_post_tags
    ,px_post_category;

END;
$$;

-- Fx: liberanalytics.fx_get_posts

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
	,thumb_01_width INTEGER
	,thumb_02_width INTEGER
	,thumb_03_width INTEGER
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
txt_post_thumb_01_alias TEXT := 'thumb_01';
txt_post_thumb_02_alias TEXT := 'thumb_02';
txt_post_thumb_03_alias TEXT := 'thumb_03';
txt_post_tags_alias TEXT := 'post_tags';
txt_pred_featured TEXT;
txt_pred_post_name TEXT;
txt_pred_post_category TEXT;
txt_pred_post_tags TEXT;
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
	',' || txt_posts_alias || '.post_category' || E'\n' ||
	',' || txt_posts_alias || '.post_created' || E'\n' ||
	',' || txt_posts_alias || '.short_desc' || E'\n' ||
	',' || txt_posts_alias || '.long_desc' || E'\n' ||
	',' || txt_posts_alias || '.intro' || E'\n' ||
	',' || txt_post_img_01_alias || '.post_img' || E'\n' ||
	',' || txt_post_img_01_alias || '.post_img_width AS img_01_width' || E'\n' ||
	',' || txt_post_img_02_alias || '.post_img_width AS img_02_width' || E'\n' ||
	',' || txt_post_img_03_alias || '.post_img_width AS img_03_width' || E'\n' ||
	',' || txt_post_img_04_alias || '.post_img_width AS img_04_width ' || E'\n' ||
	',' || txt_post_thumb_01_alias || '.post_img_width AS thumb_01_width ' || E'\n' ||
	',' || txt_post_thumb_02_alias || '.post_img_width AS thumb_02_width ' || E'\n' ||
	',' || txt_post_thumb_03_alias || '.post_img_width AS thumb_03_width ';

/*
 * Set the query to retrieve post data & post image specs by slot #
 * where slot 1 = main images, slot 2 = thumbnails.
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

INNER JOIN liberanalytics.post_img AS ' || txt_post_thumb_01_alias || '
ON (
' || txt_posts_alias || '.post_name = ' || txt_post_thumb_01_alias || '.post_name
AND ' || txt_post_thumb_01_alias || '.post_img_slot = 2
AND ' || txt_post_thumb_01_alias || '.post_img_number = 1
)

INNER JOIN liberanalytics.post_img AS ' || txt_post_thumb_02_alias || '
ON (
' || txt_posts_alias || '.post_name = ' || txt_post_thumb_02_alias || '.post_name
AND ' || txt_post_thumb_02_alias || '.post_img_slot = 2
AND ' || txt_post_thumb_02_alias || '.post_img_number = 2
)

INNER JOIN liberanalytics.post_img AS ' || txt_post_thumb_03_alias || '
ON (
' || txt_posts_alias || '.post_name = ' || txt_post_thumb_03_alias || '.post_name
AND ' || txt_post_thumb_03_alias || '.post_img_slot = 2
AND ' || txt_post_thumb_03_alias || '.post_img_number = 3
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

-- Fx: liberanalytics.fx_create_contact

DROP FUNCTION IF EXISTS liberanalytics.fx_create_contact;

CREATE OR REPLACE FUNCTION liberanalytics.fx_create_contact
(
     px_first_name TEXT DEFAULT NULL
    ,px_last_name TEXT DEFAULT NULL
    ,px_email TEXT DEFAULT NULL
    ,px_contact_subject TEXT DEFAULT NULL
    ,px_contact_message TEXT DEFAULT NULL
)

RETURNS TABLE
(
     first_name TEXT
    ,last_name TEXT
    ,email TEXT
    ,contact_subject TEXT
    ,contact_message TEXT
    ,contact_sent TIMESTAMP
)

LANGUAGE plpgsql
AS $$
BEGIN

    INSERT INTO liberanalytics.contact
    (
         first_name 
        ,last_name
        ,email
        ,contact_subject
        ,contact_message 
    )

    VALUES
    (
         px_first_name
        ,px_last_name
        ,px_email
        ,px_contact_subject
        ,px_contact_message
    );

    RETURN QUERY
    SELECT

         la_contact.first_name::TEXT
        ,la_contact.last_name::TEXT
        ,la_contact.email::TEXT
        ,la_contact.contact_subject::TEXT
        ,la_contact.contact_message::TEXT
        ,la_contact.contact_sent

    FROM liberanalytics.contact AS la_contact
    WHERE la_contact.contact_id = currval(pg_get_serial_sequence(
        'liberanalytics.contact',
        'contact_id'
    ));

END;
$$;


