<?php

    require('vendor/autoload.php');

    /**
     *
     *
     */	
    class sql_deployer {
        

        public function __construct(string $config_file, string $aws_key_id) {



        }

        private function connect() {

        }

        public function deploy() {

        }

    }

    $sd = new sql_deployer( dirname(__FILE__, 2) . '/config/la_demo_deb_demo.ini', 'dd43a73a-30be-4af1-962c-a6fc1dd97790' );


?>