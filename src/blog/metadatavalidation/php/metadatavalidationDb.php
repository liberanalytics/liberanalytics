<?php

    namespace metadatavalidation;

    const DB_OPERATIONS = [
        'getOrders' => "
            EXEC [dbo].[usp_get_orders]
                @pxCustomerName      = :pxCustomerName,
                @pxBusinessUnitCode  = :pxBusinessUnitCode,
                @pxOrderStatusCode   = :pxOrderStatusCode,
                @pxSalesChannelCode  = :pxSalesChannelCode,
                @pxOrderDate         = :pxOrderDate,
                @pxRequestedShipDate = :pxRequestedShipDate,
                @pxMinTotalAmount    = :pxMinTotalAmount,
                @pxMaxTotalAmount    = :pxMaxTotalAmount,
                @pxExpedited         = :pxExpedited
        "
    ];
    const DSN_FILE_ENV = 'LA_METADATAVALIDATION_AUTH';

    function executeQuery(string $pxDbOperation, array $dbPxs): array
    {

        $connection = getDbConnection(); 
        $dbOperation = DB_OPERATIONS[$pxDbOperation]; 
        $stmt = prepareStmt($connection, $dbOperation);
        $executeQuery
        $connection = null;
        return [1];

    }

    function getDbConnection(): \PDO
        {

        $authFileName = getenv(DSN_FILE_ENV);
        if ($authFileName === false) {
            throw new \RuntimeException(
                'Database auth environment variable ' . DSN_FILE_ENV . ' is not defined.'
            );
        }

        $authJSON = file_get_contents($authFileName);
        if ($authJSON === false) {
            throw new \RuntimeException(
                'Unable to read database auth configuration file.'
            );
        }

        $authConfig = json_decode(
            $authJSON,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $dsn = "sqlsrv:Server={$authConfig['server']},{$authConfig['port']};" .
                "Database={$authConfig['database']};" .
                "TrustServerCertificate=yes";

        try {
            $getDbConnection = new \PDO(
                $dsn,
                $authConfig['username'],
                $authConfig['auth'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            );
        }
        catch (\PDOException $e) { throw $e; }

        return $getDbConnection;
    }

    function prepareStmt(\PDO $connection, string $dbOperation): \PDOStatement
    {
        //
        try { 
            $prepareStmt = $connection->prepare($dbOperation);
            return $prepareStmt;
        }
    }

    function executeStmt(\PDOStatement $stmt, array $dbPxs): array
    {
        try {
            $stmt->execute($dbPxs);
            $executeStmt = $stmt->fetchAll(\PDO::FETCH_NUM);
            return $executeStmt;
        }        
    }
?>