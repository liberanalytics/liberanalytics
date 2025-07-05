SELECT

     first_name
    ,last_name
    ,email
    ,contact_message
    ,contact_sent
        
FROM liberanalytics.fx_create_contact
(
     :px_first_name
    ,:px_last_name
    ,:px_email
    ,:px_contact_message
);
