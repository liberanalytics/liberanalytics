-- Seed data: refdata.post_tags

INSERT INTO refdata.post_tags
(
    post_tag,
    post_tag_foundational,
    post_tag_created
)
VALUES
  
    ('Data Analysis', FALSE, DEFAULT),
    ('Data Quality', FALSE, DEFAULT),
    ('AWS', TRUE, DEFAULT),
    ('PostgreSQL', TRUE, DEFAULT),
    ('Python', TRUE, DEFAULT),
    ('PHP', TRUE, DEFAULT),
    ('Metadata', FALSE, DEFAULT),
    ('Validation', FALSE, DEFAULT),
    ('Deployment', FALSE, DEFAULT),
    ('Database Security', FALSE, DEFAULT),
    ('SQL Server', TRUE, DEFAULT),
    ('AI', TRUE, DEFAULT),
    ('Semantic Search', FALSE, DEFAULT),
    ('Data Architecture', FALSE, DEFAULT),
    ('Instrumentation', FALSE, DEFAULT),
    ('Telemetry', FALSE, DEFAULT),
    ('psql', FALSE, DEFAULT),
    ('AI', TRUE, DEFAULT);
