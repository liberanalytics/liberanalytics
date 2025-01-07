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



            $rs = $kmsClient->encrypt([
                'KeyId' => 'dd43a73a-30be-4af1-962c-a6fc1dd97790',
                'Plaintext' => 'HW',
                'EncryptionAlgorithm' => 'RSAES_OAEP_SHA_256',                       
            ]);

            $ciphertextBlob = base64_encode($rs['CiphertextBlob']);

            echo "Encrypted data : " . $ciphertextBlob . PHP_EOL; 

            // ^ perms on config file so it's not readable.
            chmod($config_file, 0000);

        }

        public function deploy() {

        }

    }

    $sd = new sql_deployer( dirname(__FILE__, 2) . '/config/la_demo_db_demo.ini', chr(7), 'dd43a73a-30be-4af1-962c-a6fc1dd97790' );


?>