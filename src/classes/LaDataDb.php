<?php

require_once('LaConfig.php');
require_once('LaAWS.php');

/**
 * 
 * 
 */
class LaDataDB
{
    /**
     * 
     */
    public const RESULTSET_ARRAY = 'array';
    public const RESULTSET_ROWS = 'rows';
    public const RESULTSET_SCALAR = 'scalar';
    public const FETCHSTYLE_ASSOC = 'assoc';
    public const FETCHSTYLE_NUM = 'num';
    public const CRUD_CREATE = 'create';
    public const CRUD_READ = 'fetchData';
    public const CRUD_UPDATE = 'update';
    public const CRUD_DELETE = 'delete';    
    private LaConfig $laConfig; 
    private LaAws $laAWS;       
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

        //
        // chmod($connectionFile, 0440);
        $handle = fopen($connectionFile, 'rb');
        // chmod($connectionFile, 0000);
        $binary = fread($handle, filesize($connectionFile));
        
        // Decrypt the binary data in the database config file.
        $connectionData = $this->laAWS->getAWSClient('awsKMS')->decrypt([

            // AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.
            'CiphertextBlob' => $binary,
            'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
            'KeyId' => $this->laConfig->getConfigAttr('aws', 'kms', 'key_desc', 'liberanalytics_db')

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
    private function execPDOStatement($pxParams)
    {
        $this->pdoStmt->execute($pxParams);
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
     * 
     */
    protected function fetchData
    (
        string $pxResultsetType,
        string $pxFetchStyle
    )
    {
        //
        $fetchStyle = match($pxFetchStyle)
        {
            'assoc' => PDO::FETCH_ASSOC,
            'num'   => PDO::FETCH_NUM
        };        
        $fetchData = $this->pdoStmt->fetchall($fetchStyle);

        //
        $fetchData = match($pxResultsetType)
        {
            self::RESULTSET_ARRAY =>  $fetchData[0],
            self::RESULTSET_SCALAR => $fetchData[0][0],
            self::RESULTSET_ROWS => $fetchData,
            default => $fetchData
        };    

        return $fetchData;

    }

    /**
     * 
     */
    protected function dispatchCrudOperation
    (
        string $pxQry, 
        string $pxCrudType, 
        array $pxParams, 
        string $pxResultsetType, 
        string $pxFetchStyle
    )
    {
        //
        $this->openConnection();   

        $this->setPDOStmt($pxQry);                  
        $this->execPDOStatement($pxParams);
        $dispatchCrudOperation = call_user_func_array([$this, $pxCrudType], [$pxResultsetType, $pxFetchStyle]);    
        $this->closePDOStatement();

        $this->closeConnection();

        return $dispatchCrudOperation;
  
    }

}
