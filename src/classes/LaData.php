<?php

require ('../src/vendor/autoload.php');
include_once('LaConfig.php');

/**
 * 
 */
class LaData extends LaConfig {

    private $conn;
    private $awsCWLogs;
    private $awsKMS;
    private $logError;
    private $logSQL;

    function __construct()
    {
        parent::__construct();
        //$this->setAwsClients();
        //$this->setConn();
    }

    /**
     * 
     */
    private function setAwsClients()
    {
        $awsRegion = $this->getConfigAttr('aws', 'account', 'region', 'default');

        // Create CloudWatch Logs / KMS / CloudWatchLogger instances.
        try {

            $this->awsCWLogs = new Aws\CloudWatchLogs\CloudWatchLogsClient([
                'profile' => 'default',
                'region' => $awsRegion,
                'version' => 'latest'
            ]);

            $this->awsKMS = new Aws\Kms\KmsClient([
                'profile' => 'default',
                'region' => $awsRegion,
                'version' => 'latest'
            ]);

            $this->logError = new LegalThings\CloudWatchLogger([
                'aws' => [
                    'profile' => 'default',
                    'region' => $awsRegion,
                    'version' => 'latest'
                ],
                'group_name' => $this->getConfigAttr('aws', 'cloudwatch', 'log_group', 'sdk'),
                'stream_name' => $this->getConfigAttr('aws', 'cloudwatch', 'log_stream', 'php_db_errors')
            ]);
            
            $this->logSQL = new LegalThings\CloudWatchLogger([
                'aws' => [
                    'profile' => 'default',
                    'region' => $awsRegion,
                    'version' => 'latest'
                ],
                'group_name' => $this->getConfigAttr('aws', 'cloudwatch', 'log_group', 'sdk'),
                'stream_name' => $this->getConfigAttr('aws', 'cloudwatch', 'log_stream', 'php_db_sql')
            ]);         
    }
    // Report on any exceptions - exit program since all instances are needed.
        catch (Exxception $e) {
            echo $e->getTraceAsString();
            exit('Couldn\'t instantiate a needed AWS or Logger class. Exiting.');
        }

    }

    /**
     * 
     */
    private function setConn()
    {

        $connFile =
            $this->getConfigAttr('liberanalytics', 'config', 'env', 'dir') .
            $this->getConfigAttr('data', 'database', 'liberanalytics', 'conn_file');

        chmod($connFile, 0440);
        $handle = fopen($connFile, 'rb');
        chmod($connFile, 0000);
        $binary = fread($handle, filesize($connFile));
        
        // Decrypt the binary data in the database config file.
        try {
            $connData = $this->awsKMS->decrypt([

                // AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.
                'CiphertextBlob' => $binary,
                'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
                'KeyId' => $this->getConfigAttr('aws', 'kms', 'key_desc', 'liberanalytics_db')
            ]);
        }
        // Log any exceptions to the error log group / stream. Exit on failure
        // since remaining program depends on decrypted data.
        catch (Exception $e) {
            $logError->log(['Problem decrypting : ' => $e->getAwsErrorMessage()]);
            exit("Unable to decrypt the data in ${connFile}.");
        }
        
        // Decode using base64 decoding, then split data into components per delimiter.
        $connData = base64_decode($connData['Plaintext']);
        $dbParams = explode( $this->getConfigAttr('data', 'database', 'liberanalytics', 'delim'), $connData );    
        $dbHost = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'host_ordinal')];
        $dbName = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'name_ordinal')];
        $dbUser = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'user_ordinal')];
        $dbAuth = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'auth_ordinal')];

        // Try to connect to postgres database. Log any errors to CloudWatch Logs.
        try {
            $this->conn = new PDO(
                "pgsql:host=${dbHost};
                    port=5432;
                    dbname=${dbName};
                    user=${dbUser};
                    password=${dbAuth};
                    sslmode=require"
            );
            // Set PDO error reporting to Exception.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        // Connection attempt failed - log failure to CloudWatch Logs and exit.
        catch (PDOException $e) {
            $logError->log(['Problem connecting : ' => $e->getMessage()]);
            exit("Unable to connect to host : ${dbHost}.");
        }

    }

    /**
     * 
     */
    public function getPost(
        bool $pFeatured, 
        string $pCategory = NULL, 
        string $pPostName = NULL,
        array $pOrderBy = NULL)
    {

        //
        $sqlAliasPosts = "la_posts.";

        //
        $featuredOperator = ($pFeatured != FALSE) ? ">" : "=";
        $categoryPredicate = ($pCategory != NULL) ? "AND " . $sqlAliasPosts . "post_category = '" . $pCategory . "'" : "";
        $postNamePredicate = ($pPostName != NULL) ? "AND " . $sqlAliasPosts . "post_name = '" . $pPostName . "'" : "";

        //
        if ($pOrderBy != NULL) {

            $orderByCount = count($pOrderBy);
            $orderByIter = 1;
            $orderByClause = "ORDER BY ";
            $orderByColumns = '';
            
            foreach ($pOrderBy AS $key => $val) {
                $orderByColumns .= "$sqlAliasPosts$key $val";
                $orderByColumns .= ($orderByIter < $orderByCount) ? ", " : "";
                $orderByIter += 1;
            }
            $orderByClause .= $orderByColumns;

        } 
        else {
            $orderByClause = '';
        }

        //
        $qry = 
        "SELECT

         la_posts.post_name
        ,la_posts.post_category
        ,la_posts.post_created
        ,la_posts.short_desc
        ,la_posts.long_desc
        ,la_posts.featured
        ,la_posts.post_img
        ,la_posts.featured_img
        ,la_posts.search_img
        
        FROM liberanalytics.posts AS la_posts

        WHERE 1=1
        AND la_posts.featured " . $featuredOperator . " 0 " . "\n" .
        $categoryPredicate . "\n" .
        $postNamePredicate . "\n" .
        $orderByClause . ";";

    }

}
