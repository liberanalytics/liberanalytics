<?php

    /**
     * phppdocloudwatchlogs.php
     *
     * Creates a pdo connection to a postgresql database and runs a sample
     * query. Demonstrates how to use AWS Key Management Service keys to 
     * decrypt database connection parameters from a configuration file on
     * filesystem. The configuration file must have been created first
     * with the aws_postgresql_config.py program. Shows how to loq errors
     * encountered as well as queries run to CloudWatch Logs.
     */

    require ('../vendor/autoload.php');

    // Create CloudWatch Logs / KMS / CloudWatchLogger instances.
    try {

        $aws_cwlogs_client = new Aws\CloudWatchLogs\CloudWatchLogsClient([
            'profile' => 'default',
            'region' => 'us-east-2',
            'version' => 'latest'
        ]);

        $aws_kms_client = new Aws\Kms\KmsClient([
            'profile' => 'default',
            'region' => 'us-east-2',
            'version' => 'latest'
        ]);

        $error_logger = new LegalThings\CloudWatchLogger([
            'aws' => [
                'profile' => 'default',
                'region' => 'us-east-2',
                'version' => 'latest'
            ],
            'group_name' => 'sdk',
            'stream_name' => 'php/database/errors/'
        ]);
        
        $sql_logger = new LegalThings\CloudWatchLogger([
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

    // Open and read the database config file: reset perms on database config
    // file when finished opening.
    $db_config_file = '/var/www/html/liberanalytics/config/la_demo_db.ini';
    chmod($db_config_file, 0440);
    $handle = fopen($db_config_file, 'rb');
    chmod($db_config_file, 0000);
    $binary_data = fread($handle, filesize($db_config_file));

    // Decrypt the binary data in the database config file.
    try {
        $db_config_data = $aws_kms_client->decrypt([

            // AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.
            'CiphertextBlob' => $binary_data,
            'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
            'KeyId' => 'c7b1107e-aae8-4359-b2b0-8ea26ad0e2d6'
        ]);
    }
    // Log any exceptions to the error log group / stream. Exit on failure
    // since remaining program depends on decrypted data.
    catch (Exception $e) {
        $error_logger->log(['Problem decrypting : ' => $e->getAwsErrorMessage()]);
        exit("Unable to decrypt the data in ${db_config_file}.");
    }

    // Decode using base64 decoding, then split data into components per delimiter.
    $db_config_data = base64_decode($db_config_data['Plaintext']);
    $db_params = explode( chr(7), $db_config_data );    
    $db_host = $db_params[0];
    $db_name = $db_params[1];
    $db_user = $db_params[2];
    $db_auth = $db_params[3];

    // Try to connect to postgres database. Log any errors to CloudWatch Logs.
    try {
        $pdo = new PDO(
            "pgsql:host=${db_host};
                port=5432;
                dbname=${db_name};
                user=${db_user};
                password=${db_auth};
                sslmode=require"
        );
        // Set PDO error reporting to Exception.
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    // Log failed connection attempts to CloudWatch Logs and exit.
    catch (PDOException $e) {
        $error_logger->log(['Problem connecting : ' => $e->getMessage()]);
        exit("Unable to connect to host : ${db_host}.");
    }

    // Run an example query. Log successful queries to the sql log stream
    // and any errors to error log stream in CloudWatch Logs.
    try {
        $qry = 'SELECT datname, datcollate FROM pg_database;';
        $stmt = $pdo->query($qry);
        $sql_logger->log(["User: ${db_user}: Query: "  => $qry]);
    }
    // Log failed query prepare attements to CloudWatch Logs.
    catch (PDOException $e) {
        $error_logger->log(['Problem with query ' => $e->getMessage()]);
        exit("Unable to prepare query : ${qry}.");
    }

?>