CREATE TABLE liberanalytics.post_chunks
(
    post_chunk_id BIGINT GENERATED ALWAYS AS IDENTITY,
    post_name VARCHAR(32) NOT NULL,
    chunk_index INTEGER NOT NULL,
    chunk_text TEXT NOT NULL,
    embedding VECTOR(1536) NOT NULL,

    CONSTRAINT post_chunks_pk
        PRIMARY KEY (post_chunk_id),

    CONSTRAINT post_chunks_post_name_fkey
        FOREIGN KEY (post_name)
        REFERENCES liberanalytics.posts (post_name)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT post_chunks_post_chunk_uk
        UNIQUE (post_name, chunk_index),

    CONSTRAINT post_chunks_chunk_index_ck
        CHECK (chunk_index >= 0),

    CONSTRAINT post_chunks_chunk_text_ck
        CHECK (length(btrim(chunk_text)) > 0)
);