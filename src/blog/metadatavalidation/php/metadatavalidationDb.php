<?php

    namespace metadatavalidation;

    const DB_OPERATIONS = [
        'getOrders' => '[dbo].[usp_get_orders]'
    ];
    const DSN_FILE_ENV = 'LA_METADATAVALIDATION_AUTH';

    function executeQuery(string $pxDbOperation, array $dbPxs)
    {
        $conn = getDbConnection();

        $dbOperation = DB_OPERATIONS[$pxDbOperation];
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

        $getDbConnection = new \PDO(
            $dsn,
            $authConfig['username'],
            $authConfig['auth'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        );

        return $getDbConnection;
    }
?>