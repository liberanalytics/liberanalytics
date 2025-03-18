CREATE TABLE liberanalytics.posts (

     post_name VARCHAR(32) CONSTRAINT posts_pk PRIMARY KEY
    ,post_category VARCHAR(32) NOT NULL REFERENCES refdata.post_category(post_category)
    ,post_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,short_desc VARCHAR(256) NOT NULL
    ,long_desc VARCHAR(256) NOT NULL
    ,featured SMALLINT NULL
    ,post_img VARCHAR(64) NOT NULL
    ,featured_img VARCHAR(64) NULL
    ,search_img VARCHAR(64) NULL

);