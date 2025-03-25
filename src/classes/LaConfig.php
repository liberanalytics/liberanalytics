<?php

/**
 * 
 */
class LaConfig {

    private const ENV = "env";

    private string $configFile;
    protected mixed $config;
    protected string $delimiter;
    protected array $awsAccount;

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

    /**
     * 
     */  
    private function setConfig()
    {        
        //chmod($db_config_file, 440);
        $configJSON = file_get_contents($this->getConfigFile());
        $this->config = json_decode($configJSON, true); 
        //chmod($db_config_file, 000);
    }

    private function getConfig()
    {
        return $this->config;
    }

    /**
     * 
     */
    public function getConfigAttr(string $attrNode, string $attrType, string $attrClass, string $attr)
    {

        if ($attrClass == self::ENV) {
            return getenv($this->getConfig()[$attrNode][$attrType][$attrClass][$attr]);
        }
        else {
            return $this->getConfig()[$attrNode][$attrType][$attrClass][$attr];
        }

    }
}

?>