USE [LiberAnalytics];
GO

/* 1. No filters: expect all rows */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL;
GO

SELECT *
FROM dbo.orders;
GO

/* 2. Customer name contains match */
EXEC [dbo].[usp_get_orders]
     N'Ariadne',
     NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL;
GO


SELECT *
FROM dbo.orders;
GO

/* 3. Business unit code */
EXEC [dbo].[usp_get_orders]
     NULL,
     'ANA',
     NULL, NULL, NULL, NULL, NULL, NULL, NULL;
GO

SELECT *
FROM dbo.orders;
GO

/* 4. Order status */
EXEC [dbo].[usp_get_orders]
     NULL, NULL,
     'APPROVED',
     NULL, NULL, NULL, NULL, NULL, NULL;
GO


SELECT *
FROM dbo.orders;
GO

/* 5. Sales channel */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL,
     'WEB',
     NULL, NULL, NULL, NULL, NULL;
GO

SELECT *
FROM dbo.orders;
GO

/* 6. Order date: tests DATE against DATETIME2 range logic */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL,
     '2026-08-21',
     NULL, NULL, NULL, NULL;
GO


SELECT *
FROM dbo.orders;
GO

/* 7. Requested ship date */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL,
     '2026-08-25',
     NULL, NULL, NULL;
GO

SELECT *
FROM dbo.orders;
GO

/* 8. Minimum subtotal */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL, NULL,
     3000.00,
     NULL,
     NULL;
GO


/* 9. Maximum subtotal */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL, NULL,
     NULL,
     4000.00,
     NULL;
GO


/* 10. Expedited = Yes */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL, NULL,
     NULL, NULL,
     1;
GO


/* 11. Expedited = No */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL, NULL,
     NULL, NULL,
     0;
GO


/* 12. Combined predicates */
EXEC [dbo].[usp_get_orders]
     N'Ariadne',
     NULL,
     NULL,
     NULL,
     NULL,
     NULL,
     1000.00,
     10000.00,
     0;
GO


/* 13. Customer contains match + status */
EXEC [dbo].[usp_get_orders]
     N'Ariadne',
     NULL,
     'APPROVED',
     NULL,
     NULL,
     NULL,
     NULL,
     NULL,
     NULL;
GO


/* 14. Expected empty result set */
EXEC [dbo].[usp_get_orders]
     N'Does Not Exist',
     NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL;
GO


/* 15. Range intentionally impossible: expect no rows */
EXEC [dbo].[usp_get_orders]
     NULL, NULL, NULL, NULL, NULL, NULL,
     10000.00,
     1000.00,
     NULL;
GO