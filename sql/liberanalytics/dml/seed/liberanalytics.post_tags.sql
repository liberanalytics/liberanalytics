-- Seed data: liberanalytics.post_tags

INSERT INTO liberanalytics.post_tags
(
    post_name,
    post_tag,
    post_tag_created
)
VALUES
    -- Why Data? post.
    ('whydata', 'Data Analysis', DEFAULT),
    ('whydata', 'Data Quality', DEFAULT),

    -- Python/PostgreSQL connection post.
    ('pythonpostgreconnect', 'AWS', DEFAULT),
    ('pythonpostgreconnect', 'PostgreSQL', DEFAULT),
    ('pythonpostgreconnect', 'Python', DEFAULT),

    -- PHP CloudWatch logs post.
    ('phpcloudwatchlogs', 'AWS', DEFAULT),
    ('phpcloudwatchlogs', 'PostgreSQL', DEFAULT),
    ('phpcloudwatchlogs', 'PHP', DEFAULT),
    ('phpcloudwatchlogs', 'Logging', DEFAULT)
    
    -- Postgres deploy post.
    ('postgresdeploy', 'PostgreSQL', DEFAULT);