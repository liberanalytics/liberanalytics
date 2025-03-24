<?php

include_once('LaConfig.php');


class LaData extends LaConfig {

    private $conn;

    function __construct() {

        parent::__construct();

        $this->setConn();
    }

    private function setConn() {

        $configFile = $this->getConfigAttr('data', 'database', 'liberanalytics', 'connection_file');

    }


}

?>