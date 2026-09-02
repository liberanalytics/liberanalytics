<?php

    namespace metadatavalidation;

    const DB_OPERATIONS = [
        'getOrders' => '[dbo].[usp_get_orders]'
    ];

    function executeQuery(string $dbOperation, array $dbPxs): array
    {
        setDbConnection();

        $dbOperation = DB_OPERATIONS[$dbOperation];
    }

    function setDbConnection(): \PDO
    {
        // read auth.json
        // build sqlsrv DSN
        // return PDO instance
    }

    function getDbConnectionString()
    {

    }


?>