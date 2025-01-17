<?php

    require('../vendor/autoload.php');

    // Create new CloudWatch Logs client / KMS client / CloudWatchLogger instance.
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
    catch (Exxception $e) {
        echo $e->getTraceAsString();
    }

    // Open and read the database config file.
    $db_config_file = '/var/www/html/liberanalytics/config/la_demo_db.ini';
    chmod($db_config_file, 0440);
    $handle = fopen($db_config_file , 'rb');
    chmod($db_config_file, 0000);
    $binary_data = fread($handle, filesize($db_config_file));

    // Decrypt the binary data in the database config file.
    try {
        $db_config_data = $aws_kms_client->decrypt([
            'CiphertextBlob' => $binary_data,
            'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
            'KeyId' => 'c7b1107e-aae8-4359-b2b0-8ea26ad0e2d6'
        ]);
    }
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
            "pgsql:host=$db_host;
                port=5432;
                dbname=$db_name;
                user=$db_user;
                password=$db_auth;
                sslmode=require"
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        $error_logger->log(['Problem connecting : ' => $e->getMessage()]);
        exit("Unable to connect to host : ${db_host}.");
    }

    // Run an example query. Log any errors to CloudWatch Logs.
    try {
        $qry = 'SELECT datname, datcollate FROM pg_database;';
        $stmt = $pdo->query($qry);
        $sql_logger->log(['Query run by user ' . $_SERVER['USER'] . ' ' => $qry]);
    }
    catch (PDOException $e) {
        $error_logger->log(['Problem with query ' => $e->getMessage()]);
        exit("Unable to run query : ${qry}.");
    }

?>