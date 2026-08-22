-- Table: liberanalytics.posts

DROP TABLE IF EXISTS liberanalytics.posts;

CREATE TABLE liberanalytics.posts
(
    post_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    short_desc character varying(256) COLLATE pg_catalog."default" NOT NULL,
    long_desc character varying(256) COLLATE pg_catalog."default" NOT NULL,
    intro character varying(512) COLLATE pg_catalog."default" NOT NULL,
    featured smallint NOT NULL,
    CONSTRAINT posts_pk PRIMARY KEY (post_name),
    
    CONSTRAINT posts_post_category_fkey FOREIGN KEY (post_category)
        REFERENCES refdata.post_categories (post_category)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE  liberanalytics.posts
    OWNER to liberanalytics;
