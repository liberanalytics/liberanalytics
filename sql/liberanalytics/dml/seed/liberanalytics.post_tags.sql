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
