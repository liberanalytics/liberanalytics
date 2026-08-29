
/* ============================================================
   LiberAnalytics
   Metadata-Driven Validation Sample Database
   SQL Server
   ============================================================ */

USE master;
GO

IF DB_ID('LiberAnalytics') IS NOT NULL
BEGIN
    ALTER DATABASE LiberAnalytics
        SET SINGLE_USER
        WITH ROLLBACK IMMEDIATE;

    DROP DATABASE LiberAnalytics;
END;
GO

CREATE DATABASE LiberAnalytics;
GO

USE LiberAnalytics;
GO


/* ============================================================
   dbo.customers
   Master/business entity data
   ============================================================ */

CREATE TABLE dbo.customers
(
    customer_id         INT IDENTITY(1,1) NOT NULL,
    customer_code       VARCHAR(12) NOT NULL,
    customer_name       NVARCHAR(100) NOT NULL,
    email_address       VARCHAR(254) NULL,
    phone_number        VARCHAR(20) NULL,
    active              BIT NOT NULL
        CONSTRAINT DF_customers_active DEFAULT (1),
    customer_since      DATE NOT NULL,
    credit_limit        DECIMAL(12,2) NULL,

    CONSTRAINT PK_customers
        PRIMARY KEY (customer_id),

    CONSTRAINT UQ_customers_customer_code
        UNIQUE (customer_code)
);
GO


/* ============================================================
   dbo.business_units
   Controlled organizational domain
   ============================================================ */

CREATE TABLE dbo.business_units
(
    business_unit_id    SMALLINT IDENTITY(1,1) NOT NULL,
    business_unit_code  CHAR(3) NOT NULL,
    business_unit_name  VARCHAR(60) NOT NULL,
    company_name        VARCHAR(60) NOT NULL
        CONSTRAINT DF_business_units_company_name
        DEFAULT ('LiberAnalytics'),
    active              BIT NOT NULL
        CONSTRAINT DF_business_units_active DEFAULT (1),

    CONSTRAINT PK_business_units
        PRIMARY KEY (business_unit_id),

    CONSTRAINT UQ_business_units_business_unit_code
        UNIQUE (business_unit_code)
);
GO


/* ============================================================
   dbo.order_statuses
   Reference/domain data
   ============================================================ */

CREATE TABLE dbo.order_statuses
(
    order_status_code   VARCHAR(12) NOT NULL,
    order_status_desc   VARCHAR(60) NOT NULL,
    terminal_status     BIT NOT NULL
        CONSTRAINT DF_order_statuses_terminal_status DEFAULT (0),

    CONSTRAINT PK_order_statuses
        PRIMARY KEY (order_status_code)
);
GO


/* ============================================================
   dbo.sales_channels
   Reference/domain data
   ============================================================ */

CREATE TABLE dbo.sales_channels
(
    sales_channel_code  CHAR(3) NOT NULL,
    sales_channel_desc  VARCHAR(50) NOT NULL,
    active              BIT NOT NULL
        CONSTRAINT DF_sales_channels_active DEFAULT (1),

    CONSTRAINT PK_sales_channels
        PRIMARY KEY (sales_channel_code)
);
GO


/* ============================================================
   dbo.orders
   Transactional table
   ============================================================ */

CREATE TABLE dbo.orders
(
    order_id            BIGINT IDENTITY(100000,1) NOT NULL,
    customer_id         INT NOT NULL,
    business_unit_id    SMALLINT NOT NULL,
    order_status_code   VARCHAR(12) NOT NULL,
    sales_channel_code  CHAR(3) NOT NULL,

    order_reference     VARCHAR(24) NOT NULL,
    order_date          DATETIME2(0) NOT NULL
        CONSTRAINT DF_orders_order_date DEFAULT (SYSDATETIME()),

    requested_ship_date DATE NULL,

    subtotal_amount     DECIMAL(12,2) NOT NULL,
    tax_amount          DECIMAL(10,2) NOT NULL
        CONSTRAINT DF_orders_tax_amount DEFAULT (0.00),
    shipping_amount     DECIMAL(10,2) NOT NULL
        CONSTRAINT DF_orders_shipping_amount DEFAULT (0.00),

    expedited           BIT NOT NULL
        CONSTRAINT DF_orders_expedited DEFAULT (0),

    customer_note       NVARCHAR(500) NULL,

    CONSTRAINT PK_orders
        PRIMARY KEY (order_id),

    CONSTRAINT UQ_orders_order_reference
        UNIQUE (order_reference),

    CONSTRAINT FK_orders_customers
        FOREIGN KEY (customer_id)
        REFERENCES dbo.customers(customer_id),

    CONSTRAINT FK_orders_business_units
        FOREIGN KEY (business_unit_id)
        REFERENCES dbo.business_units(business_unit_id),

    CONSTRAINT FK_orders_order_statuses
        FOREIGN KEY (order_status_code)
        REFERENCES dbo.order_statuses(order_status_code),

    CONSTRAINT FK_orders_sales_channels
        FOREIGN KEY (sales_channel_code)
        REFERENCES dbo.sales_channels(sales_channel_code),

    CONSTRAINT CK_orders_subtotal_amount
        CHECK (subtotal_amount >= 0),

    CONSTRAINT CK_orders_tax_amount
        CHECK (tax_amount >= 0),

    CONSTRAINT CK_orders_shipping_amount
        CHECK (shipping_amount >= 0)
);
GO


/* ============================================================
   Seed: dbo.business_units
   ============================================================ */

INSERT INTO dbo.business_units
(
    business_unit_code,
    business_unit_name,
    company_name
)
VALUES
    ('ANA', 'Analytics', 'LiberAnalytics'),
    ('DAT', 'Data Architecture', 'LiberAnalytics'),
    ('ENG', 'Database Engineering', 'LiberAnalytics'),
    ('AIM', 'AI and Metadata', 'LiberAnalytics');
GO


/* ============================================================
   Seed: dbo.order_statuses
   ============================================================ */

INSERT INTO dbo.order_statuses
(
    order_status_code,
    order_status_desc,
    terminal_status
)
VALUES
    ('PENDING',   'Pending',   0),
    ('APPROVED',  'Approved',  0),
    ('FULFILLED', 'Fulfilled', 1),
    ('CANCELLED', 'Cancelled', 1);
GO


/* ============================================================
   Seed: dbo.sales_channels
   ============================================================ */

INSERT INTO dbo.sales_channels
(
    sales_channel_code,
    sales_channel_desc
)
VALUES
    ('WEB', 'Website'),
    ('DIR', 'Direct'),
    ('REF', 'Referral'),
    ('CON', 'Consulting');
GO


/* ============================================================
   Seed: dbo.customers
   ============================================================ */

INSERT INTO dbo.customers
(
    customer_code,
    customer_name,
    email_address,
    phone_number,
    active,
    customer_since,
    credit_limit
)
VALUES
(
    'CUS000001',
    N'Ariadne Systems',
    'contact@ariadne.example',
    '937-555-0101',
    1,
    '2025-02-14',
    25000.00
),
(
    'CUS000002',
    N'Orion Data Group',
    'operations@orion.example',
    NULL,
    1,
    '2025-08-03',
    50000.00
),
(
    'CUS000003',
    N'Northstar Analytics',
    'finance@northstar.example',
    '614-555-0177',
    1,
    '2026-01-12',
    15000.00
),
(
    'CUS000004',
    N'Mercury Research LLC',
    NULL,
    NULL,
    0,
    '2024-11-19',
    NULL
);
GO


/* ============================================================
   Seed: dbo.orders
   ============================================================ */

INSERT INTO dbo.orders
(
    customer_id,
    business_unit_id,
    order_status_code,
    sales_channel_code,
    order_reference,
    order_date,
    requested_ship_date,
    subtotal_amount,
    tax_amount,
    shipping_amount,
    expedited,
    customer_note
)
VALUES
(
    1,
    2,
    'APPROVED',
    'DIR',
    'LA-2026-000001',
    '2026-08-20T10:30:00',
    '2026-08-25',
    8500.00,
    612.00,
    0.00,
    0,
    N'Data architecture consulting engagement.'
),
(
    2,
    3,
    'FULFILLED',
    'WEB',
    'LA-2026-000002',
    '2026-08-21T14:05:00',
    NULL,
    3200.50,
    230.44,
    75.00,
    1,
    N'Database engineering implementation.'
),
(
    3,
    4,
    'PENDING',
    'REF',
    'LA-2026-000003',
    '2026-08-24T09:15:00',
    '2026-09-02',
    12750.00,
    918.00,
    0.00,
    0,
    NULL
),
(
    1,
    1,
    'CANCELLED',
    'CON',
    'LA-2026-000004',
    '2026-08-26T16:45:00',
    NULL,
    1800.00,
    129.60,
    25.00,
    0,
    N'Cancelled before fulfillment.'
);
GO


/* ============================================================
   Smoke tests
   ============================================================ */

SELECT *
FROM dbo.customers;

SELECT *
FROM dbo.business_units;

SELECT *
FROM dbo.order_statuses;

SELECT *
FROM dbo.sales_channels;

SELECT *
FROM dbo.orders;
GO