-- Table: refdata.post_categories

DROP TABLE IF EXISTS refdata.post_categories;

CREATE TABLE IF NOT EXISTS refdata.post_categories
(
    post_category character varying(32) COLLATE pg_catalog."default" NOT NULL,
    post_category_desc character varying(32) COLLATE pg_catalog."default",
    post_category_created timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT post_categories_pk PRIMARY KEY (post_category)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS refdata.post_categories
    OWNER to liberanalytics;
