-- Table: liberanalytics.posts

DROP TABLE IF EXISTS liberanalytics.contact;

CREATE TABLE IF NOT EXISTS liberanalytics.contact
(
    contact_id INTEGER GENERATED ALWAYS AS IDENTITY,
    first_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    last_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
    email character varying(32) COLLATE pg_catalog."default" NOT NULL,
    message character varying(512) COLLATE pg_catalog."default" NOT NULL,
    contact_sent timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT contact_pk PRIMARY KEY (contact_id)   
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS liberanalytics.contact
    OWNER to liberanalytics;
