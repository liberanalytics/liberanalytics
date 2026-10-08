\set VERBOSITY verbose
\set ON_ERROR_STOP on

-- Make sure we're not in the target database.
\connect liberanalytics

-- Wrap the rollout in a transaction to provide all-or-nothing atomicity.
BEGIN;

    -- Create schemas.
    -- \echo Creating schemas...

    -- Create tables.
    -- \echo Creating tables...

    -- Create fxs.
    \echo Creating fxs...
    \ir ../ddl/fx/refdata.fx_get_post_tags.sql
   

    -- Confirm final table & fx artifacts were created as quick pass/fail assertion.
    -- DO $$
    -- BEGIN
        -- Check last table was created.
        -- IF NOT EXISTS (
        --     SELECT 1 FROM pg_catalog.pg_tables 
        --     WHERE 1=1
        --     AND schemaname = 'liberanalytics'
        --     AND tablename = 'contact'
        -- )
        -- THEN
        --     RAISE EXCEPTION 'Schema creation failed: table liberanalytics.contact was not created.';
        -- END IF;
        
        -- Check last fx was created.
        -- IF NOT EXISTS (

        --     SELECT 1
        --     FROM pg_catalog.pg_proc AS pg

        --     INNER JOIN pg_catalog.pg_namespace AS ns
        --     ON pg.pronamespace = ns.oid

        --     WHERE 1=1
        --     AND ns.nspname = 'liberanalytics'
        --     AND pg.proname = 'fx_create_contact'

        --     -- Verify args are same as last fx created in case of overloading.
        --     AND pg_get_function_identity_arguments(pg.oid) LIKE '%text, %text, %text, %text, %text'
        -- )
        -- THEN
        --     RAISE EXCEPTION 'Fx creation failed: liberanalytics.fx fx_create_contact was not created.';
        -- END IF;

    -- END $$ LANGUAGE plpgsql;

COMMIT;
