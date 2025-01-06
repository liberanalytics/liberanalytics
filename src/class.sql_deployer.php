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

            $config_data = unpack('C*', $binary_data);

            foreach ($config_data as $key => $val) {
                echo "key = $key : val = $val: char = " . chr($val) . "\n";
            }

            fclose($handle);

            // ^ perms on config file so it's not readable.
            chmod($config_file, 0000);

        }

        public function deploy() {

        }

    }

    $sd = new sql_deployer( dirname(__FILE__, 2) . '/config/la_demo_db_demo.ini', chr(7), 'dd43a73a-30be-4af1-962c-a6fc1dd97790' );


?>