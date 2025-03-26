<?php

/**
 * 
 */
class LaConfig {

    private const ENV = "env";
    private string $configFile;
    private array $config;

    function __construct()
    {
        $this->setConfigFile();
        $this->setConfig();
    }

    private function setConfigFile()
    {
        $this->configFile = getenv('LA_CONFIG_FILE');
    }

    private function getConfigFile()
    {
        return $this->configFile;
    }
    

    private function setConfig()    
    {        
        if (file_exists($this->getConfigFile())) {
            $configJSON = file_get_contents($filename = $this->getConfigFile());
            $this->config = json_decode($configJSON, true);
        }
    }

    private function getConfig()
    {
        return $this->config;
    }

    public function getConfigAttr(string $attrNode, string $attrType, string $attrClass, string $attr)
    {
 
        if ($attrClass == 'env') {
            return getenv($this->getConfig()[$attrNode][$attrType][$attrClass][$attr]);
        }
        else {
            return $this->getConfig()[$attrNode][$attrType][$attrClass][$attr];
        }
 
    }    
}

?>