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
    \ir ../ddl/refdata.post_categories.sql
    \ir ../ddl/liberanalytics.posts.sql
    \ir ../ddl/refdata.post_tags.sql
    \ir ../ddl/liberanalytics.post_tags.sql
    \ir ../ddl/liberanalytics.contact.sql

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
