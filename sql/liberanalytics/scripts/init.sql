\set VERBOSITY verbose
\set ON_ERROR_STOP on

-- Make sure we're not in the target database.
\connect postgres

-- Kill any sessions still active on the database.
SELECT pg_terminate_backend(pid)
FROM pg_stat_activity
WHERE datname = 'liberanalytics'
  AND pid <> pg_backend_pid();

\ir ../ddl/db/liberanalytics_db.sql

\connect liberanalytics

BEGIN;

    -- Create schemas.
    \echo 'Creating schemas...'
    \ir ../ddl/schema/meta.sql
    \ir ../ddl/schema/refdata.sql
    \ir ../ddl/schema/liberanalytics.sql

    -- Create tables.
    \echo 'Creating tables...'
    \ir ../ddl/tables/refdata.post_categories.sql
    \ir ../ddl/tables/liberanalytics.posts.sql
    \ir ../ddl/tables/refdata.post_tags.sql
    \ir ../ddl/tables/liberanalytics.post_tags.sql
    \ir ../ddl/tables/liberanalytics.contact.sql

    -- Create fxs.
    \echo 'Creating fxs...'
    \ir ../ddl/fx/meta.fx_get_dynamic_sql_clause.sql
    \ir ../ddl/fx/meta.fx_get_fx_pxs.sql
    \ir ../ddl/fx/refdata.fx_get_post_categories.sql
    \ir ../ddl/fx/refdata.fx_get_post_tags.sql
    \ir ../ddl/fx/refdata.fx_get_post_tags_like.sql
    \ir ../ddl/fx/liberanalytics.fx_get_posts.sql
    \ir ../ddl/fx/liberanalytics.fx_create_contact.sql

    -- Confirm final table was created as quick pass/fail assertion.
    DO
    $$

    BEGIN

    IF NOT EXISTS (
        SELECT 1 FROM pg_catalog.pg_tables 
        WHERE 1=1
        AND schemaname = 'liberanalytics'
        AND tablename = 'post_tags'
    )
    THEN
        RAISE EXCEPTION 'Schema creation failed: table liberanalytics.post_tags was not created.';
    END IF;

    END;

    $$ LANGUAGE plpgsql;

COMMIT;
