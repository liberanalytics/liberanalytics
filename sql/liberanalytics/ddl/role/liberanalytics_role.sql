\set VERBOSITY verbose
\set ON_ERROR_STOP on

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
