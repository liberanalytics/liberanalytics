INSERT INTO liberanalytics.posts 
(

     post_name
    ,post_category
    ,post_created
    ,short_desc
    ,long_desc
    ,featured
    ,post_img
    ,featured_img
    ,search_img
)
 VALUES

    (
         'whydata'
        ,'Why Data'
        , NOW()
        ,'Why do we need Data?'
        ,'Explores the importance of data and information in decision making.'
        ,1
        ,'whydata_820.png'
        ,'whydata_195.png'
        ,NULL
    ),

    (
         'pythonpostgreconnect'
        ,'DB Security'
        , NOW()
        ,'Connecting Python to a Postgresql database'
        ,'Discover how to connect Python to a Postgresql database without storing credentials or keys on filesystem.'
        ,2
        ,'pythonpostgreconnect_820.png'
        ,'pythonpostgreconnect_195.png'
        ,NULL
    ),

    (
         'phpcloudwatchlogs'
        ,'Instrumentation'
        , NOW()
        ,'Logging database errors and queries to Amazon Web Services CloudWatch Logs'
        ,'Exemplifies a PHP way to use Amazon Web Services CloudWatch to log database errors and queries run. (Yes, PHP in 2025!).'
        ,3
        ,'aws_cw_820.png'
        ,'aws_cw_195.png'
        ,NULL
    );         
