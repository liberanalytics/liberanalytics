CREATE TABLE refdata.post_category (

     post_category VARCHAR(32) CONSTRAINT post_categories_pk PRIMARY KEY
    ,post_category_desc VARCHAR(32)
    ,post_category_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);