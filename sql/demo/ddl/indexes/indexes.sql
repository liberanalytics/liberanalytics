-- =================================================================
-- indexes.sql
-- =================================================================

ALTER TABLE refdata.business_unit ADD CONSTRAINT fk_bu_lob FOREIGN KEY
(bu_lob_id) REFERENCES refdata.lob (lob_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE prod.sales ADD CONSTRAINT fk_sales_customers FOREIGN KEY
(sales_cust_id) REFERENCES refdata.customers (cu_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE prod.sales ADD CONSTRAINT fk_sales_employees FOREIGN KEY
(sales_emp_id) REFERENCES refdata.employees (emp_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE prod.sales ADD CONSTRAINT fk_sales_bu FOREIGN KEY
(sales_bu_id) REFERENCES refdata.business_unit (bu_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE prod.sales_details ADD CONSTRAINT fk_sales_details_sales FOREIGN KEY
(sales_id) REFERENCES prod.sales (sales_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE prod.sales_details ADD CONSTRAINT fk_sales_details_item FOREIGN KEY
(sales_item_id) REFERENCES prod.items (item_id) ON UPDATE CASCADE ON DELETE RESTRICT;         