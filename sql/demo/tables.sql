-- =================================================================
-- tables.sql
-- =================================================================

CREATE TABLE refdata.lob (
    lob_id SERIAL PRIMARY KEY,
    lob_name varchar(255),
    lob_desc varchar(255),
    lob_created_ts TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE refdata.business_unit (
    bu_id SERIAL PRIMARY KEY,
    bu_lob_id INTEGER,
    bu_name varchar(255),
    bu_desc varchar(255),
    bu_created_ts TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE refdata.customers (
    cu_id SERIAL PRIMARY KEY,
    cu_name varchar(255),
    cu_desc varchar(255),
    cu_ts TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE refdata.employees (
    emp_id SERIAL PRIMARY KEY,
    emp_name varchar(255),
    emp_ssn VARCHAR(11) NOT NULL,
    cu_ts TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);  

CREATE TABLE prod.sales (
        sales_id BIGSERIAL PRIMARY KEY,
        sales_cust_id INTEGER,
        sales_desc VARCHAR(255),
        sales_emp_id INTEGER,
        sales_bu_id INTEGER,
        saLes_created_ts TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prod.sales_details (
    sales_detail_id BIGSERIAL PRIMARY KEY,
    sales_id INTEGER,
    sales_item_id INTEGER,
    sales_qty INTEGER
);

CREATE TABLE prod.items (
    item_id SERIAL PRIMARY KEY,
    item_name varchar(255),
    item_created_ts TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);    