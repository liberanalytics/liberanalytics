<?php

namespace classes;

require_once(dirname(__DIR__) . "/" . "vendor/autoload.php");
require_once('LaConfig.php');

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\Kms\KmsClient;
use LegalThings\CloudWatchLogger;

class LaAWS {

    private LaConfig $laConfig;
    public $awsCWLogs;
    public $awsKMS;
    public $logError;
    public $logSQL;

    function __construct() 
    {
        $this->laConfig = new LaConfig();
        $this->setAWSClients();
    }

    private function setAWSClients(): void
    {
        $awsRegion = $this->laConfig->getConfigAttr('aws', 'account', 'region', 'default');

        // Create CloudWatch Logs / KMS / CloudWatchLogger instances.
        try 
        {
            $this->awsCWLogs = new CloudWatchLogsClient([
                'profile' => 'default',
                'region' => $awsRegion,
                'version' => 'latest'
            ]);

            $this->awsKMS = new KmsClient([
                'profile' => 'default',
                'region' => $awsRegion,
                'version' => 'latest'
            ]);

            $this->logError = new CloudWatchLogger([
                'aws' => [
                    'profile' => 'default',
                    'region' => $awsRegion,
                    'version' => 'latest'
                ],
                'group_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_group', 'sdk'),
                'stream_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_stream', 'php_db_errors')
            ]);
            
            $this->logSQL = new CloudWatchLogger([
                'aws' => [
                    'profile' => 'default',
                    'region' => $awsRegion,
                    'version' => 'latest'
                ],
                'group_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_group', 'sdk'),
                'stream_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_stream', 'php_db_sql')
            ]);         
        }
        // Report on any exceptions - exit program since all instances are needed.
        catch (Exxception $e)
        {
            echo $e->getTraceAsString();
            exit('Couldn\'t instantiate a needed AWS or Logger class. Exiting.');
        }
    }

    public function getAWSClient(string $awsClient)
    {
        switch ($awsClient)
        {
            case 'awsCWLogs':   return $this->awsCWLogs; break;
            case 'awsKMS':      return $this->awsKMS; break;
            case 'logError':    return $this->logError; break;
            case 'logSQL':      return $this->logSQL; break;
        }
    }
}
?>