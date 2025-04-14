-- Table: liberanalytics.posts_post_tags

-- DROP TABLE IF EXISTS liberanalytics.posts_post_tags;

CREATE TABLE IF NOT EXISTS liberanalytics.posts_post_tags
(
    post_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    tag character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_tag_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT posts_post_tags_pk PRIMARY KEY (post_name, tag),
    
    CONSTRAINT posts_post_tags_post_name_fkey FOREIGN KEY (post_name)
        REFERENCES liberanalytics.posts (post_name) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,

     CONSTRAINT posts_post_tags_post_tag_fkey FOREIGN KEY (tag)
        REFERENCES refdata.post_tags (tag) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS liberanalytics.posts_post_tags
    OWNER to liberanalytics;