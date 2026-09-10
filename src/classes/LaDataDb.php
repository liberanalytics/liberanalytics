<?php

namespace classes;

use enums\CrudFetchStyle;
use \PDO;

/**
 * 
 * 
 */
class LaDataDB
{

    protected LaConfig $laConfig; 
    protected LaAws $laAWS;       
    private $connection;
    private $pdoStmt;

    protected function __construct()
    {
        $this->laConfig = new LaConfig();
        $this->laAWS = new LaAWS();
    }

    /**
     * 
     */
     private function openConnection()
    {
        $connectionFile =
            $this->laConfig->getConfigAttr('liberanalytics', 'config', 'env', 'dir') .
            $this->laConfig->getConfigAttr('data', 'database', 'liberanalytics', 'conn_file');

        $awsProfile = $this->laConfig->getConfigAttr('aws', 'account', 'profile', 'default');

        //
        $handle = fopen($connectionFile, 'rb');
        $binary = fread($handle, filesize($connectionFile));
        
        // Decrypt the binary data in the database config file.
        $connectionData = $this->laAWS->getAWSClient('awsKMS')->decrypt([

            // AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.
            'CiphertextBlob' => $binary,
            'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
            'KeyId' => $this->laConfig->getConfigAttr('aws', 'kms', 'key_desc', $awsProfile)

        ]);
        
        // Decode using base64 decoding, then split data into components per delimiter.
        $connectionData = base64_decode($connectionData['Plaintext']);
        $dbParams = explode( $this->laConfig->getConfigAttr('data', 'database', 'liberanalytics', 'delim'), $connectionData );    
        $dbHost = $dbParams[$this->laConfig->getConfigAttr('data', 'database', 'liberanalytics', 'host_ordinal')];
        $dbName = $dbParams[$this->laConfig->getConfigAttr('data', 'database', 'liberanalytics', 'name_ordinal')];
        $dbUser = $dbParams[$this->laConfig->getConfigAttr('data', 'database', 'liberanalytics', 'user_ordinal')];
        $dbAuth = $dbParams[$this->laConfig->getConfigAttr('data', 'database', 'liberanalytics', 'auth_ordinal')];

        $dsn = "pgsql:host={$dbHost};port=5432;dbname={$dbName};user={$dbUser};password={$dbAuth};sslmode=require";
        $this->connection = new PDO($dsn);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    /**
     * 
     */    
    private function closeConnection()
    {
        $this->connection = NULL;
    }

    /**
     * 
     */    
    private function closePDOStatement()
    {
        $this->pdoStmt->closeCursor();
        $this->pdoStmt = NULL;
    }

    /**
     * 
     */
    private function execPDOStatement(array $pxParams): void
    {
        foreach ($pxParams as $pxName => $pxValue)
        {
            $pdoType = match (true)
            {
                \is_bool($pxValue) => PDO::PARAM_BOOL,
                \is_int($pxValue)  => PDO::PARAM_INT,
                \is_null($pxValue) => PDO::PARAM_NULL,
                default            => PDO::PARAM_STR
            };

            $this->pdoStmt->bindValue(
                ':' . $pxName,
                $pxValue,
                $pdoType
            );
        }

        $this->pdoStmt->execute();
    }

    /**
     * 
     * 
     */
    private function setPDOStmt(string $pxQuery)
    {
        $this->pdoStmt = $this->connection->prepare($pxQuery);   
    }

    /**
     * 
     */
    private function returnExecResults(CrudFetchStyle $pxFetchStyle)
    {
        //
        $returnExecResults = null;
        $fetchStyle = match($pxFetchStyle)
        {
            CrudFetchStyle::FETCHSTYLE_ASSOC => PDO::FETCH_ASSOC,
            CrudFetchStyle::FETCHSTYLE_NUM  => PDO::FETCH_NUM,
            CrudFetchStyle::FETCHSTYLE_ROWCOUNT => ''
        }; 

        //
        $returnExecResults = ($fetchStyle) ? $this->pdoStmt->fetchall($fetchStyle) : $this->pdoStmt->rowCount();
        return $returnExecResults;
    }

    /**
     * 
     */
    protected function executeCrudOperation(
        string $pxQry,
        array $pxParams, 
        CrudFetchStyle $pxFetchStyle
    )
    {
        //
        $this->openConnection();
        $this->setPDOStmt($pxQry);
        $this->execPDOStatement($pxParams);
        $executeCrudOperation = $this->returnExecResults($pxFetchStyle);
        $this->closePDOStatement();
        $this->closeConnection();
        return $executeCrudOperation;  
    }

}
