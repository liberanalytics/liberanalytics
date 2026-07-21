<?php

namespace classes;

/**
 * 
 */
class LaConfig {

    public const EC2PROFILE = 'ec2';
    private const ENV = 'env';
    private array $config;


    function __construct()
    {
        $this->setConfig();
    }

    private function setConfig(): void
    {        
        if (file_exists(getenv('LA_CONFIG_FILE'))) {
            $configJSON = file_get_contents($filename = getenv('LA_CONFIG_FILE'));
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