\set VERBOSITY verbose
\set ON_ERROR_STOP on

-- Make sure we're not in the target database.
\connect postgres

-- Kill any sessions still active on the database.
SELECT pg_terminate_backend(pid)
FROM pg_stat_activity
WHERE datname = 'liberanalytics'
  AND pid <> pg_backend_pid();

DROP DATABASE IF EXISTS liberanalytics;

CREATE DATABASE liberanalytics
    TEMPLATE template0
    ENCODING 'UTF-8'
    OWNER liberanalytics;

\connect liberanalytics

BEGIN;

CREATE SCHEMA IF NOT EXISTS meta AUTHORIZATION liberanalytics;
CREATE SCHEMA IF NOT EXISTS refdata AUTHORIZATION liberanalytics;
CREATE SCHEMA IF NOT EXISTS liberanalytics AUTHORIZATION liberanalytics;

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
