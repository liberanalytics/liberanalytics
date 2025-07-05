DROP FUNCTION IF EXISTS liberanalytics.fx_create_contact;

CREATE OR REPLACE FUNCTION liberanalytics.fx_create_contact
(
     px_first_name TEXT
    ,px_last_name TEXT
    ,px_email TEXT
    ,px_contact_message TEXT
)

RETURNS TABLE
(
     first_name TEXT
    ,last_name TEXT
    ,email TEXT
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
        ,contact_message 
    )

    VALUES
    (
         px_first_name
        ,px_last_name
        ,px_email
        ,px_contact_message
    );

    RETURN QUERY
    SELECT

         la_contact.first_name::TEXT
        ,la_contact.last_name::TEXT
        ,la_contact.email::TEXT
        ,la_contact.contact_message::TEXT
        ,la_contact.contact_sent

    FROM liberanalytics.contact AS la_contact
    WHERE la_contact.contact_id = currval(pg_get_serial_sequence(
        'liberanalytics.contact',
        'contact_id'
    ));

END;
$$
