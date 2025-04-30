-- Table: refdata.post_tags

DROP TABLE IF EXISTS refdata.post_tags;

CREATE TABLE refdata.post_tags (

    post_tag character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_tag_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_tag_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT refdata_post_tags_pk PRIMARY KEY(post_tag),

    CONSTRAINT post_tags_category_fkey FOREIGN KEY (post_tag_category)
    REFERENCES refdata.post_category (post_category) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION

)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS refdata.post_tags
OWNER to liberanalytics;