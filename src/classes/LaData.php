<?php

require ('../src/vendor/autoload.php');

include_once('LaConfig.php');


class LaData extends LaConfig {

    private $conn;
    private $awsCWLogs;
    private $awsKMS;
    private $logError;
    private $logSQL;

    function __construct() {

        parent::__construct();

        $bt = round(microtime(true) * 1000);
        $this->setAwsClients();
        $this->setConn();
        $et = round(microtime(true) * 1000);
        print( "\n" . ($et - $bt));
        //print( "\n" .  date("H:i:s", $et));


    }

    private function setAwsClients() {

        // Create CloudWatch Logs / KMS / CloudWatchLogger instances.
        try {

            $this->awsCWLogs = new Aws\CloudWatchLogs\CloudWatchLogsClient([
                'profile' => 'default',
                'region' => 'us-east-2',
                'version' => 'latest'
            ]);

            $this->awsKMS = new Aws\Kms\KmsClient([
                'profile' => 'default',
                'region' => 'us-east-2',
                'version' => 'latest'
            ]);

            $this->logError = new LegalThings\CloudWatchLogger([
                'aws' => [
                    'profile' => 'default',
                    'region' => 'us-east-2',
                    'version' => 'latest'
                ],
                'group_name' => 'sdk',
                'stream_name' => 'php/database/errors/'
            ]);
            
            $this->logSQL = new LegalThings\CloudWatchLogger([
                'aws' => [
                    'profile' => 'default',
                    'region' => 'us-east-2',
                    'version' => 'latest'
                ],
                'group_name' => 'sdk',
                'stream_name' => 'php/database/sql/'
            ]);         
    }
    // Report on any exceptions - exit program since all instances are needed.
    catch (Exxception $e) {
        echo $e->getTraceAsString();
        exit('Couldn\'t instantiate a needed AWS or Logger class. Exiting.');
    }

    }

    private function setConn() {

        $connFile =
            getenv($this->getConfigAttr('liberanalytics', 'config', 'env', 'dir')) .
            $this->getConfigAttr('data', 'database', 'liberanalytics', 'connection_file');

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
                'KeyId' => 'bf81ede9-e40e-4487-b795-f15eb914f65b'
            ]);
        }
        // Log any exceptions to the error log group / stream. Exit on failure
        // since remaining program depends on decrypted data.
        catch (Exception $e) {
            $logError->log(['Problem decrypting : ' => $e->getAwsErrorMessage()]);
            exit("Unable to decrypt the data in ${db_config_file}.");
        }
        
        // Decode using base64 decoding, then split data into components per delimiter.
        $connData = base64_decode($connData['Plaintext']);
        $dbParams = explode( $this->getConfigAttr('data', 'construct', 'delim', 'config_delim'), $connData );    
        $dbHost = $dbParams[0];
        $dbName = $dbParams[1];
        $dbUser = $dbParams[2];
        $dbAuth = $dbParams[3];

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

        // Run an example query. Log successful queries to the sql log stream
        // and any errors to the error log stream in CloudWatch Logs.
        try {
            $qry = 'SELECT datname, datcollate FROM pg_database111;';
            $stmt = $this->conn->query($qry);
            //$this->logSQL->log(["User: ${dbUser}: Query: "  => $qry]);

        }
        // Log failed query prepare statements to CloudWatch Logs.
        catch (PDOException $e) {
            $this->logError->log(['Problem with query ' => $e->getMessage()]);
            exit("Unable to prepare query : ${qry}.");
        }


    }

}
