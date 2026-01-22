\set VERBOSITY verbose
\set ON_ERROR_STOP on

-- Make sure we're not in the target database.
\connect postgres

-- Kill any sessions still active on the database.
SELECT pg_terminate_backend(pid)
FROM pg_stat_activity
WHERE datname = 'liberanalytics'
  AND pid <> pg_backend_pid();

-- Create the liberanalytics database.
\ir ../ddl/db/liberanalytics_db.sql

-- Connect to database liberanalytics.
\connect liberanalytics

-- Make sure we're connected to the right database.
SELECT current_database() AS current_db, current_user AS current_user;

BEGIN;

    -- Create schemas.
    \echo Creating schemas...
    \ir ../ddl/schema/meta.sql
    \ir ../ddl/schema/refdata.sql
    \ir ../ddl/schema/liberanalytics.sql

    -- Create tables.
    \echo Creating tables...
    \ir ../ddl/tables/refdata.post_categories.sql
    \ir ../ddl/tables/liberanalytics.posts.sql
    \ir ../ddl/tables/refdata.post_tags.sql
    \ir ../ddl/tables/liberanalytics.post_tags.sql
    \ir ../ddl/tables/liberanalytics.contact.sql

    -- Create fxs.
    \echo Creating fxs...
    \ir ../ddl/fx/meta.fx_get_dynamic_sql_clause.sql
    \ir ../ddl/fx/meta.fx_get_fx_pxs.sql
    \ir ../ddl/fx/refdata.fx_get_post_categories.sql
    \ir ../ddl/fx/refdata.fx_get_post_tags.sql
    \ir ../ddl/fx/refdata.fx_get_post_tags_like.sql
    \ir ../ddl/fx/liberanalytics.fx_get_posts.sql
    \ir ../ddl/fx/liberanalytics.fx_create_contact.sql    

    -- Confirm final table & fx artifacts were created as quick pass/fail assertion.
    DO $$
    BEGIN
        -- Check last table was created.
        IF NOT EXISTS (
            SELECT 1 FROM pg_catalog.pg_tables 
            WHERE 1=1
            AND schemaname = 'liberanalytics'
            AND tablename = 'contact'
        )
        THEN
            RAISE EXCEPTION 'Schema creation failed: table liberanalytics.contact was not created.';
        END IF;
        
        -- Check last fx was created.
        IF NOT EXISTS (

            SELECT 1
            FROM pg_catalog.pg_proc AS pg

            INNER JOIN pg_catalog.pg_namespace AS ns
            ON pg.pronamespace = ns.oid

            WHERE 1=1
            AND ns.nspname = 'liberanalytics'
            AND pg.proname = 'fx_create_contact'
            -- Verify args are same as last fx created in case of overloading.
            AND pg_get_function_identity_arguments(pg.oid) LIKE '%text, %text, %text, %text, %text'
        )
        THEN
            RAISE EXCEPTION 'Fx creation failed: liberanalytics.fx fx_create_contact was not created.';
        END IF;

    END $$ LANGUAGE plpgsql;

    -- Seed data insertions.
    \echo Populating seed data...
    \ir ../dml/seed/refdata.post_categories.sql
    \ir ../dml/seed/refdata.post_tags.sql
    \ir ../dml/seed/liberanalytics.posts.sql
    \ir ../dml/seed/liberanalytics.post_tags.sql

    -- Confirm final table was seeded as quick pass/fail assertion.
    DO $$
    BEGIN
        IF NOT EXISTS (
            SELECT 1 FROM liberanalytics.post_tags
        ) THEN
            RAISE EXCEPTION 'Seed failed: liberanalytics.post_tags has no rows.';
        END IF;

    END $$ LANGUAGE plpgsql;

COMMIT;
