-- Seed data: refdata.post_tags

INSERT INTO refdata.post_tags
(
    post_tag,
    post_category,
    post_tag_foundational,
    post_tag_created
)
VALUES
    -- Why Data? tags.
    ('Data Analysis', 'Why Data', FALSE, DEFAULT),
    ('Data Quality', 'Why Data', FALSE, DEFAULT),

    -- AWS tags.
    ('AWS', 'AWS', TRUE, DEFAULT),

    -- PostgreSQL tags.
    ('PostgreSQL', 'PostgreSQL', TRUE, DEFAULT),

    -- Python tags.
    ('Python', 'Python', TRUE, DEFAULT),

    -- PHP tags.
    ('PHP', 'PHP', TRUE, DEFAULT),

    -- Instrumentation tags.
    ('Logging', 'Instrumentation', FALSE, DEFAULT);