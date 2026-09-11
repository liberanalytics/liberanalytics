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
        ,'Data Concepts'
        , NOW() - INTERVAL '4 WEEK'
        ,'Why do we need Data?'
        ,'Explores the importance of data and information in decision making.'
        ,'Why do we go through extensive efforts to collect, transform, load, store, and aalyze volumes of data? Startups and corporations employ teams of professionals who often specialize in niche functions within data analytics such as data architect, data analyst, or data scientist in order to provide maximum value of their respective function to the enterprise.'
        ,FALSE
    ),

    (
         'pythonpostgreconnect'
        ,'Database Engineering'
        , NOW() - INTERVAL '3 WEEK'
        ,'Connecting Python to a Postgresql database'
        ,'Discover how to connect Python to a Postgresql database without storing credentials or keys on filesystem.'
        ,'Connecting Python to Postgresql is made seamless with the psycopg2 library. This article demonstrates how to connect to a Postgresql database using Python without storing credentials in plain text or in the .pgpass file.'
        ,FALSE
    ),

    (
         'phpcloudwatchlogs'
        ,'Cloud'
        , NOW() - INTERVAL '2 WEEK'
        ,'Logging database errors and queries to Amazon Web Services CloudWatch Logs'
        ,'Exemplifies a PHP way to use Amazon Web Services CloudWatch to log database errors and queries run. (Yes, PHP in 2026!).'
        ,'Ever grow tired of storing logs on a server then having to manage access to the logs for individual users working in a collaborative development team? This post explores an alternative method to manage database logging by making use of the Amazon Web Services (AWS) CloudWatch Logs service.'
        ,FALSE
    ),
    
    (
         'postgresdeploy'
        ,'Database Engineering'
        , NOW() - INTERVAL '1 week'
        ,'Building an atomic PostgreSQL database rollout with Bash and psql'
        ,'A lightweight approach to orchestrating an atomic PostgreSQL database rollout with Bash and psql.'
        ,'Deploying a database can seem like a daunting task that turns into a trial-and-error ritual: run SQL script(s) to create database schema objects — tables, foreign keys, indexes, functions, and stored procedures.'
        ,TRUE
    ),
    
    (
         'metadatavalidation'
        ,'Database Engineering'
        , NOW()
        ,'Using database metadata and domain membership for server-side validation of user-supplied inputs'
        ,'Demonstrates a validation method that leverages database metadata for type checking, regular expression matching, and nullability checks, as well as authoritative domain-membership validation in an application''s backend.'
        ,'Validating user-supplied inputs in the backend of an application strengthens data integrity even if the frontend also performs validation. The challenge in performing server-side validation often lies in selecting validation'
        ,TRUE
    ),
    
    (
         'semanticsearch'
        ,'AI'
        , NOW()
        ,''
        ,''
        ,''
        ,TRUE
    );         
