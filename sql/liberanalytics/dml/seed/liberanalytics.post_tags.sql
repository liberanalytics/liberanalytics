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
    ('pythonpostgreconnect', 'Database Security', DEFAULT),
    ('pythonpostgreconnect', 'Data Architecture', DEFAULT),
    ('pythonpostgreconnect', 'AWS', DEFAULT),
    ('pythonpostgreconnect', 'PostgreSQL', DEFAULT),
    ('pythonpostgreconnect', 'Python', DEFAULT),

    -- PHP CloudWatch logs post.
    ('phpcloudwatchlogs', 'AWS', DEFAULT),
    ('phpcloudwatchlogs', 'PostgreSQL', DEFAULT),
    ('phpcloudwatchlogs', 'PHP', DEFAULT),
    ('phpcloudwatchlogs', 'Instrumentation', DEFAULT),
    ('phpcloudwatchlogs', 'Telemetry', DEFAULT),
    
    -- Postgres deploy post.
    ('postgresdeploy', 'PostgreSQL', DEFAULT),
    ('postgresdeploy', 'Deployment', DEFAULT),
    ('postgresdeploy', 'Data Architecture', DEFAULT),
    ('postgresdeploy', 'psql', DEFAULT),

    -- Metadatavalidation post.
    ('metadatavalidation', 'Data Architecture', DEFAULT),
    ('metadatavalidation', 'Metadata', DEFAULT),
    ('metadatavalidation', 'Validation', DEFAULT),
    ('metadatavalidation', 'Database Security', DEFAULT),
    ('metadatavalidation', 'SQL Server', DEFAULT),
    
    -- Semanticsearch post.
    ('semanticsearch', 'AI', DEFAULT),
    ('semanticsearch', 'Semantic Search', DEFAULT),
    ('semanticsearch', 'Data Architecture', DEFAULT),
    
    -- Vectorsearch post.
    ('vectorsearch', 'AI', DEFAULT),
    ('vectorsearch', 'Vector Search', DEFAULT),
    ('vectorsearch', 'Data Architecture', DEFAULT);