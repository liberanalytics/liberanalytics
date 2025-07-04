DROP FUNCTION IF EXISTS liberanalytics.fx_create_contact;

CREATE OR REPLACE FUNCTION liberanalytics.fx_create_contact
(
     px_first_name TEXT
    ,px_last_name TEXT
    ,px_email TEXT
    ,px_message TEXT
)

RETURNS VOID
LANGUAGE plpgsql
AS $$
BEGIN

    INSERT INTO liberanalytics.contact
    (
         first_name 
        ,last_name
        ,email
        ,message 
    )

    VALUES
    (
         px_first_name
        ,px_last_name
        ,px_email
        ,px_message
    );

END;
$$
