-- Seed data: refdata.post_categories

INSERT INTO refdata.post_categories (

     post_category
    ,post_category_desc
    ,post_category_created

)

VALUES

     ('Why Data', 'Why Data', DEFAULT),
     ('DB Security', 'DB Security', DEFAULT),
     ('AWS', 'AWS', DEFAULT),
     ('Python', 'Python', DEFAULT),
     ('PHP', 'PHP', DEFAULT),
     ('Instrumentation', 'Instrumentation', DEFAULT);

-- Seed data: refdata.post_tags

INSERT INTO refdata.post_tags
(
	post_tag,
    post_category,
    post_tag_foundational,
    post_tag_created
)
VALUES
    -- Why Data? post.
    ('Why Data', 'Why Data', TRUE, DEFAULT),
    ('Value of Data', 'Why Data', FALSE, DEFAULT),
    ('Data Integrity', 'Why Data', FALSE, DEFAULT),
    ('Datasets', 'Why Data', FALSE, DEFAULT),
    ('Data Accuracy', 'Why Data', FALSE, DEFAULT),
    ('Data Analysis', 'Why Data', FALSE, DEFAULT),

    -- Pythonpostgresql post.
	('AWS', 'AWS', TRUE, DEFAULT),
    ('AWS-KMS', 'AWS', FALSE, DEFAULT),
    ('AWS-EC2', 'AWS', FALSE, DEFAULT),
    ('AWS-Python SDK', 'AWS', FALSE, DEFAULT),
    ('Encryption', 'DB Security', FALSE, DEFAULT),
    ('Python', 'Python', FALSE, DEFAULT),
    ('PostgreSQL in Python', 'Python', FALSE, DEFAULT),
  
    -- PHP CloudWatch Logs post.
	('AWS-CloudWatch', 'AWS', FALSE, DEFAULT),
    ('AWS-IAM', 'AWS', FALSE, DEFAULT),
    ('AWS-PHP SDK', 'AWS', FALSE, DEFAULT),
    ('Logging', 'Instrumentation', FALSE, DEFAULT),
    ('PHP', 'PHP', FALSE, DEFAULT),
    ('PostgreSQL in PHP', 'PHP', FALSE, DEFAULT);

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
        ,'Exemplifies a PHP way to use Amazon Web Services CloudWatch to log database errors and queries run. (Yes, PHP in 2025!).'
        ,'Ever grow tired of storing logs on a server then having to manage access to the logs for individual users working in a collaborative development team? This post explores an alternative method to manage database logging by making use of the Amazon Web Services (AWS) CloudWatch Logs service.'
        ,3
    );         


-- Seed data: liberanalytics.post_tags

INSERT INTO liberanalytics.post_tags
(
	post_name, 
    post_tag,
    post_tag_created
)
VALUES
    -- Why Data? post.
    ('whydata', 'Why Data', DEFAULT),
    ('whydata', 'Value of Data', DEFAULT),
    ('whydata', 'Data Integrity', DEFAULT),
    ('whydata', 'Datasets', DEFAULT),
    ('whydata', 'Data Accuracy', DEFAULT),
    ('whydata', 'Data Analysis', DEFAULT),

    -- Pythonpostgresql post.
	('pythonpostgreconnect', 'AWS', DEFAULT),
    ('pythonpostgreconnect', 'AWS-KMS', DEFAULT),
    ('pythonpostgreconnect', 'AWS-EC2', DEFAULT),
    ('pythonpostgreconnect', 'AWS-Python SDK', DEFAULT),
    ('pythonpostgreconnect', 'Encryption', DEFAULT),
    ('pythonpostgreconnect', 'Python', DEFAULT),
    ('pythonpostgreconnect', 'PostgreSQL in Python', DEFAULT),
  
    -- PHP CloudWatch logs post.
	('phpcloudwatchlogs', 'AWS', DEFAULT),
    ('phpcloudwatchlogs', 'AWS-KMS', DEFAULT), 
    ('phpcloudwatchlogs', 'AWS-IAM', DEFAULT),
    ('phpcloudwatchlogs', 'AWS-PHP SDK', DEFAULT),
    ('phpcloudwatchlogs', 'Logging', DEFAULT),   
    ('phpcloudwatchlogs', 'PHP', DEFAULT),
    ('phpcloudwatchlogs', 'PostgreSQL in PHP', DEFAULT);

-- Seed data: liberanalytics.post_img

INSERT INTO liberanalytics.post_img 
(
     post_name
	,post_img
    ,post_img_slot
    ,post_img_number
    ,post_img_width
    ,post_img_created
)

VALUES

(
    'whydata',
	'whydata',
    1,
    1,
    288,
    NOW()
),

(
    'whydata',
	'whydata',
    1,
    2,
    820,
    NOW()
),

(
    'whydata',
	'whydata',
    1,
    3,
    1080,
    NOW()
),

(
    'whydata',
	'whydata',
    1,
    4,
    1960,
    NOW()
),

(
    'whydata',
	'whydata',
    2,
    1,
    195,
    NOW()
),

(
    'whydata',
	'whydata',
    2,
    2,
    390,
    NOW()
),

(
    'whydata',
	'whydata',
    2,
    3,
    585,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    1,
    1,
    288,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    1,
    2,
    820,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    1,
    3,
    1080,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    1,
    4,
    1960,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    2,
    1,
    195,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    2,
    2,
    390,
    NOW()
),

(
    'pythonpostgreconnect',
	'pythonpostgreconnect',
    2,
    3,
    585,
    NOW()
),

(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    1,
    1,
    288,
    NOW()
),

(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    1,
    2,
    820,
    NOW()
),

(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    1,
    3,
    1080,
    NOW()
),

(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    1,
    4,
    1960,
    NOW()
),


(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    2,
    1,
    195,
    NOW()
),

(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    2,
    2,
    390,
    NOW()
),

(
    'phpcloudwatchlogs',
	'phpcloudwatchlogs',
    2,
    3,
    585,
    NOW()
);
