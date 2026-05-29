DROP TABLE IF EXISTS liberanalytics.post_img;

CREATE TABLE liberanalytics.post_img (

  post_name character varying(32) COLLATE pg_catalog."default" NOT NULL,
  post_img_slot   smallint NOT NULL,   -- 1=hero, 2=thumb, 3=inline1, etc.
  post_img_number smallint NOT NULL,   -- 1..N (your size index)
  post_img_width  integer  NOT NULL,
  post_img_created timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT post_img_pk
    PRIMARY KEY (post_name, post_img_slot, post_img_number),

    CONSTRAINT post_img_pos_ck CHECK (
        post_img_slot > 0 AND 
        post_img_number > 0 AND 
        post_img_width > 0
    ),

    CONSTRAINT post_img_post_name_fkey FOREIGN KEY (post_name)
        REFERENCES liberanalytics.posts (post_name) MATCH SIMPLE
        ON UPDATE CASCADE
        ON DELETE CASCADE

);

CREATE INDEX post_img_ix
  ON liberanalytics.post_img (post_name, post_img_slot, post_img_number);
  