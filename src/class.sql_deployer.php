<?php

    require('vendor/autoload.php');

    /**
     *
     *
     */	
    class sql_deployer {

        private $pdo;

        public function __construct(string $config_file, string $config_delim, string $aws_key_id) {

            $this->connect($config_file, $config_delim, $aws_key_id);

        }

        private function connect(string $config_file, string $config_delim, string $aws_key_id) {

            // ^ perms on config file so it's readable.
            chmod($config_file, 0400);

            $kmsClient = new Aws\Kms\KmsClient([
                'profile' => 'default',
                'region' => 'us-east-2',
                'version' => 'latest'
            ]);

            $handle = fopen($config_file, 'rb');

            $binary_data = fread($handle, filesize($config_file));

            try {
                $rs = $kmsClient->decrypt([
                    'CiphertextBlob' => $binary_data,
                    'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
                    'KeyId' => 'd15d4228-640f-42ec-a0a5-b39636d9c051'
                ]);
            }
            catch (KmsException $e) {
                echo "Problem decrypting : {$e->getAwsErrorMessage()}\n";
            }

            $data = base64_decode($rs['Plaintext']);           


            echo "done";

            // ^ perms on config file so it's not readable.
            #chmod($config_file, 0000);

        }

        public function deploy() {

        }

    }

    $sd = new sql_deployer( dirname(__FILE__, 2) . '/config/la_site_db_test.db.ini', chr(7), 'dd43a73a-30be-4af1-962c-a6fc1dd97790' );


?>