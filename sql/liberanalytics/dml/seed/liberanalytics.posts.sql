-- Seed data: liberanalytics.posts

INSERT INTO liberanalytics.posts 
(

     post_name
    ,post_category
    ,post_created
    ,short_desc
    ,long_desc
    ,intro
    ,featured
)
 VALUES

    (
         'whydata'
        ,'Why Data'
        , NOW()
        ,'Why do we need Data?'
        ,'Explores the importance of data and information in decision making.'
        ,'Why do we go through extensive efforts to collect, transform, load, store, and aalyze volumes of data? Startups and corporations employ teams of professionals who often specialize in niche functions within data analytics such as data architect, data analyst, or data scientist in order to provide maximum value of their respective function to the enterprise.'
        ,1
    ),

    (
         'pythonpostgreconnect'
        ,'DB Security'
        , NOW()
        ,'Connecting Python to a Postgresql database'
        ,'Discover how to connect Python to a Postgresql database without storing credentials or keys on filesystem.'
        ,'Connecting Python to Postgresql is made seamless with the psycopg2 library. This article demonstrates how to connect to a Postgresql database using Python without storing credentials in plain text or in the .pgpass file.'
        ,2
    ),

    (
         'phpcloudwatchlogs'
        ,'Instrumentation'
        , NOW()
        ,'Logging database errors and queries to Amazon Web Services CloudWatch Logs'
        ,'Exemplifies a PHP way to use Amazon Web Services CloudWatch to log database errors and queries run. (Yes, PHP in 2026!).'
        ,'Ever grow tired of storing logs on a server then having to manage access to the logs for individual users working in a collaborative development team? This post explores an alternative method to manage database logging by making use of the Amazon Web Services (AWS) CloudWatch Logs service.'
        ,3
    ),
    
    (
         'postgresdeploy'
        ,'PostgreSQL'
        , NOW()
        ,'Lessons learned from deploying a PostgreSQL database to production'
        ,'A case study on the do''s and don''ts learned during deployment of a PostgreSQL database to a production environment.'
        ,''
        ,4
    );         
