<?php

    require('vendor/autoload.php');

    /**
     *
     *
     */	
    class sql_deployer {

        public function __construct() {

            // ^ perms on config file so it's readable and read contents.
            $config_file = '../config/aws_la_config.json';
            chmod($config_file, 0400);
            $aws_config = json_decode(file_get_contents($config_file), true);
            $aws_region = $aws_config['account']['region'];

            // Create new cloudwatch and KMS clients and a CloudWatchLogger instance.
            try {

                //
                $aws_cwlogs_client = new Aws\CloudWatchLogs\CloudWatchLogsClient ([
                    'profile' => 'default',
                    'region' => $aws_region,
                    'version' => 'latest'
                ]);

                //
                $aws_kms_client = new Aws\Kms\KmsClient([
                    'profile' => 'default',
                    'region' => $aws_region,
                    'version' => 'latest'
                ]);

                //
                $logger = new LegalThings\CloudWatchLogger([
                    'aws' => [
                        'profile' => 'default',
                        'region' => $aws_region,
                        'version' => 'latest'
                    ],
                    'group_name' => $aws_config['cloudwatch']['log group']['sdk'],
                    'stream_name' => $aws_config['cloudwatch']['log stream']['php db errors']
                ]);                
            }

            catch (Exxception $e) {
                echo $e->getTraceAsString();
            }

            // Open and read the database config file.
            $db_config_file = $aws_config['database']['config file']['demo'];
            chmod($db_config_file, 0400);
            $handle = fopen($db_config_file , 'rb');
            $binary_data = fread($handle, filesize($db_config_file));
        
            // Decrypt the binary data in the database config file.
            try {
                $db_config_data = $aws_kms_client->decrypt([
                    'CiphertextBlob' => $binary_data,
                    'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
                    'KeyId' => $aws_config['kms']['key desc']['demo']
                ]);
            }
            catch (KmsException $e) {
                $logger->log(['Problem decrypting : ' => $e->getAwsErrorMessage()]);
                exit('Unable to decrypt the data in ${db_config_file}.');
            }
        
            // Decode using base64 decoding, then split data into components per delimiter.
            $db_config_data = base64_decode($db_config_data['Plaintext']);
            $db_params = explode($aws_config['construct']['config delim'], $db_config_data );

            //echo "pgsql:host=$db_params[0];port=5432;dbname=$db_params[1];user=$db_params[2];password=$db_params[3]";
            echo "postgres://$db_params[2]:$db_params[3]@$db_params[0]:5432/$db_params[1]?sslmode=require";
            try {
                $pdo = new PDO(
                    "pgsql:host=$db_params[0];port=5432;dbname=$db_params[1];user=$db_params[2];password=$db_params[3];sslmode=require"
                    //"postgres://$db_params[2]:$db_params[3]@$db_params[0]:5432/$db_params[1]?sslmode=require"
                );
            }
            catch (PDOException $e) {
                $logger->log(['Problem connectiong : ' => $e->getTraceAsString()]);
            }

            chmod($db_config_file, 0000);

            echo "done";




        }

    }

    $sd = new sql_deployer();


?>