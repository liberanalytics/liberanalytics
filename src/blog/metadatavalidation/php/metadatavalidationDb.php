<?php

    namespace metadatavalidation;

    /**
     * Maps approved application database operations to parameterized
     * SQL Server stored procedure execution statements.
     *
     * @var array<string, string>
     */    
    const DB_OPERATIONS = [
        'getOrders' => "
            EXEC [dbo].[usp_get_orders]
                @pxCustomerName                 = :pxCustomerName,
                @pxBusinessUnitCode             = :pxBusinessUnitCode,
                @pxOrderStatusCode              = :pxOrderStatusCode,
                @pxSalesChannelCode             = :pxSalesChannelCode,
                @pxOrderDate                    = :pxOrderDate,
                @pxRequestedShipDate            = :pxRequestedShipDate,
                @pxRequestedShipDateOperator    = :pxRequestedShipDateOperator,
                @pxMinTotalAmount               = :pxMinTotalAmount,
                @pxMaxTotalAmount               = :pxMaxTotalAmount,
                @pxExpedited                    = :pxExpedited
        ",
        'validateDomainMembership' => "
            SELECT 
                membership_value
            FROM [dbo].[fx_get_membership_values] (:membershipKey);
        ",
        'validateType' => "
            SELECT
                specific_schema,
                specific_name,
                parameter_name,
                ordinal_position,
                data_type,
                character_maximum_length,
                numeric_precision,
                numeric_scale
            FROM dbo.fx_get_metadata(:pxSchema, :pxObjectName, :pxName);
        "        
    ];

    /**
     * The user or system environment variable representing a
     * JSON file of database DSN and credential properties.
     *
     * @var string
     */
    const DSN_FILE_ENV = 'LA_METADATAVALIDATION_AUTH';

    /**
     * executeQuery
     *
     * @param string $pxDbOperation
     * @param array $dbPxs
     *
     * @return array
     */    
    function executeQuery(string $pxDbOperation, array $dbPxs): array
    {
        $connection = getDbConnection(); 
        $dbOperation = DB_OPERATIONS[$pxDbOperation]; 
        $stmt = prepareStmt($connection, $dbOperation);
        $connection = null;
        $executeQuery = executeStmt($stmt, $dbPxs);
        return $executeQuery;        
    }

    /**
     * getDbConnection
     *
     *
     * @return \PDO
     */
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

    /**
     * executeQuery
     *
     * @param \PDO $connection
     * @param string $dbOperation
     *
     * @return \PDOStatement
     */  
    function prepareStmt(\PDO $connection, string $dbOperation): \PDOStatement
    {
        $prepareStmt = $connection->prepare($dbOperation);
        return $prepareStmt;
    }

    /**
     * executeQuery
     *
     * @param \PDOStatement $stmt
     * @param array $dbPxs
     *
     * @return array
     */  
    function executeStmt(\PDOStatement $stmt, array $dbPxs): array
    {
        $stmt->execute($dbPxs);
        $executeStmt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $executeStmt;  
    }

?>
