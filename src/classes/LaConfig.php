<?php

class LaConfig {

    private string $configFile;
    protected string $config;
    protected string $delimiter;

    function __construct() {

        $this->setConfigFile();
        
    }

    private function setConfigFile() {
        $this->configFile = getenv('LA_CONFIG_FILE');
    }

    private function getConfigFile() {
        return $this->configFile;
    }

    private function setConfig() {
        
        //chmod($db_config_file, 440);
        $configJSON = file_get_contents($this->getConfigFile());
        $this->config = json_decode($configJSON);
        //chmod($db_config_file, 000);
    }

    private function getConfig() {
        return $this->configFile;
    }    

}

?>