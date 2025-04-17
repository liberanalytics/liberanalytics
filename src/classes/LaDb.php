<?php

require_once('LaConfig.php');
require_once('LaAWS.php');

/**
 * 
 * 
 */
class LaDB extends LaConfig
{
    /**
     * 
     */
    private $laAWS;
    private $connection;
    private string $resultsetAsArray;
    private string $resultsetAsRows;
    private string $resultsetAsScalar;
    private string $fetchStyleAssoc;
    private string $fetchStyleNum;
    private $pdoStmt;

    protected function __construct()
    {
        parent::__construct();
        $this->setResultsetTypes();
        $this->setFetchStyles();
        $this->laAWS = new LaAWS();
    }

    /**
     * 
     */
     private function openConnection()
    {
        $connectionFile =
            $this->getConfigAttr('liberanalytics', 'config', 'env', 'dir') .
            $this->getConfigAttr('data', 'database', 'liberanalytics', 'conn_file');

        //chmod($connFile, 0440);
        $handle = fopen($connectionFile, 'rb');
        //chmod($connFile, 0000);
        $binary = fread($handle, filesize($connectionFile));
        
        // Decrypt the binary data in the database config file.
        try
        {
            $connectionData = $this->laAWS->getAWSClient('awsKMS')->decrypt([

                // AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.
                'CiphertextBlob' => $binary,
                'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
                'KeyId' => $this->getConfigAttr('aws', 'kms', 'key_desc', 'liberanalytics_db')

            ]);
        }
        // Log any exceptions to the error log group / stream. Exit on failure
        // since remaining program depends on decrypted data.
        catch (Exception $e)
        {
            $this->laAWS->getAWSClient('logError')->log(['Problem decrypting : ' => $e->getAwsErrorMessage()]);
            exit("Unable to decrypt the data in {$connectionFile}.");
        }
        
        // Decode using base64 decoding, then split data into components per delimiter.
        $connectionData = base64_decode($connectionData['Plaintext']);
        $dbParams = explode( $this->getConfigAttr('data', 'database', 'liberanalytics', 'delim'), $connectionData );    
        $dbHost = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'host_ordinal')];
        $dbName = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'name_ordinal')];
        $dbUser = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'user_ordinal')];
        $dbAuth = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'auth_ordinal')];

        // Try to connect to postgres database. Log any errors to CloudWatch Logs.
        try
        {
            $dsn = "pgsql:host={$dbHost};port=5432;dbname={$dbName};user={$dbUser};password={$dbAuth};sslmode=require";
            $this->connection = new PDO($dsn);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        // Connection attempt failed - log failure to CloudWatch Logs and exit.
        catch (PDOException $e)
        {
            $this->laAWS->getAWSClient('logError')->log(['Problem connecting : ' => $e->getMessage()]);
            exit("Unable to connect to host : {$dbHost}.");
        }
    }



    /**
     * 
     */
    private function setResultsetTypes()
    {
        $this->resultsetAsArray = 'array';
        $this->resultsetAsRows = 'rows';
        $this->resultsetAsScalar = 'scalar';
    }

    private function setFetchStyles()
    {
        $this->fetchStyleAssoc = 'assoc';
        $this->fetchStyleNum = 'num';
    }

    /**
     * 
     */
    protected function getResultsetAsRows()
    {
        return $this->resultsetAsRows;
    }

    /**
     * 
     */
    protected function getResultsetAsArray()
    {
        return $this->resultsetAsArray;
    }

    /**
     * 
     */    
    protected function getResultsetAsScalar()
    {
        return $this->resultsetAsScalar;
    }

    /**
     * 
     */
    protected function getFetchStyleAssoc()
    {
        return $this->fetchStyleAssoc;
    }

    /**
     * 
     */
    protected function getFetchStyleNum()
    {
        return $this->fetchStyleNum;
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
    private function execPDOStatement()
    {
        $this->pdoStmt->execute();
    }

    /**
     * 
     * 
     */
    private function setPDOStmt(string $pxQuery, array $pxParams = [])
    {
        //
        $paramPlaceholders = "(" . implode(',', array_map(fn($ele) => "?", $pxParams)) . ")";
        $qry = $pxQuery . $paramPlaceholders . ";";
        $this->pdoStmt = $this->connection->prepare($qry);

        if (!empty($pxParams))
        {
            //
            $paramPosition = 1;
            foreach($pxParams as $paramKey => $paramValue)
            {
                $paramType = match(gettype($paramValue))
                {
                    'string'    => PDO::PARAM_STR,
                    'integer'   => PDO::PARAM_INT,
                    'boolean'   => PDO::PARAM_BOOL,
                    'NULL'      => PDO::PARAM_NULL
                };

                $this->pdoStmt->bindValue($paramPosition, $paramValue, $paramType);
                $paramPosition += 1;
            }
        }         
    }

    /**
     * 
     * 
     */
    protected function fetchData
    (
        string $pxQuery, 
        array $pxParams = [], 
        string $pxResultsetType,
        string $pxFetchStyle
    )
    {
        $this->openConnection();
        $this->setPDOStmt($pxQuery, $pxParams);
        $this->execPDOStatement();

        $fetchStyle = match($pxFetchStyle)
        {
            'assoc' => PDO::FETCH_ASSOC,
            'num'   => PDO::FETCH_NUM
        };

        $fetchData = $this->pdoStmt->fetchall($fetchStyle);        

        $fetchData = match($pxResultsetType)
        {
            $this->getResultsetAsArray() =>  $fetchData[0],
            $this->getResultsetAsScalar() => $fetchData[0][0],
            $this->getResultsetAsRows() => $fetchData,
            default => $fetchData
        };
       

        $this->closePDOStatement();

        $this->closeConnection();

        return $fetchData;
    // 

}

}
