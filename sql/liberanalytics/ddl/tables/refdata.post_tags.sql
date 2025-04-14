-- Table: refdata.post_tags

-- DROP TABLE IF EXISTS refdata.post_tags;

CREATE TABLE refdata.post_tags (

    tag character varying(32) COLLATE pg_catalog."default" NOT NULL,
    tag_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    tag_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT tags_pk PRIMARY KEY(tag),

    CONSTRAINT tags_tag_category_fkey FOREIGN KEY (tag_category)
    REFERENCES refdata.post_category (post_category) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION

)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS refdata.post_tags
OWNER to liberanalytics;