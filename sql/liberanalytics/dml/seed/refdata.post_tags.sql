INSERT INTO refdata.post_tags
(
	post_tag,
    post_tag_category,
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
    ('Data Analysis', 'Why Data', FALSE, DEFAULT)

    -- Pythonpostgresql post.
    -- ('PostgreSQL', 'RDBMS', TRUE, DEFAULT),
	('AWS', 'AWS', TRUE, DEFAULT),
    ('AWS-KMS', 'AWS', FALSE, DEFAULT),
    ('AWS-EC2', 'AWS', FALSE, DEFAULT),
    ('AWS-Python SDK', 'AWS', FALSE, DEFAULT),
    ('Encryption', 'DB Security', FALSE, DEFAULT),
    ('Python', 'Python', FALSE, DEFAULT),
    ('PostgreSQL in Python', 'Python', FALSE, DEFAULT),
  
    -- PHP CloudWatch Logs post.
    -- ('PostgreSQL', 'RDBMS', TRUE, DEFAULT),
	('AWS-CloudWatch', 'AWS', FALSE, DEFAULT),
    ('AWS-IAM', 'AWS', FALSE, DEFAULT),
    ('AWS-PHP SDK', 'AWS', FALSE, DEFAULT),
    ('Logging', 'Instrumentation', FALSE, DEFAULT),
    ('PHP', 'PHP', FALSE, DEFAULT),
    ('PostgreSQL in PHP', 'PHP', FALSE, DEFAULT);