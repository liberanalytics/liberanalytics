<?php

namespace classes;

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

        // Get the aws client config.
        $awsClientConfig = $this->getAWSClientConfig();
        
        // Create CloudWatch Logs / KMS / CloudWatchLogger instances.
        try 
        {
            $this->awsCWLogs = new CloudWatchLogsClient($awsClientConfig);
            $this->awsKMS = new KmsClient($awsClientConfig);

            $this->logError = new CloudWatchLogger([
                'aws' => $awsClientConfig,
                'group_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_group', 'sdk'),
                'stream_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_stream', 'php_db_errors')
            ]);
            
            $this->logSQL = new CloudWatchLogger([
                'aws' => $awsClientConfig,
                'group_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_group', 'sdk'),
                'stream_name' => $this->laConfig->getConfigAttr('aws', 'cloudwatch', 'log_stream', 'php_db_sql')
            ]);         
        }
        // Report on any exceptions - exit program since all instances are needed.
        catch (\Exception $e)
        {
            echo $e->getTraceAsString();
            exit('Couldn\'t instantiate a needed AWS or Logger class. Exiting.');
        }
    }

    private function getAWSClientConfig(): array
    {
        // Capture region and profile specs.
        $region = $this->laConfig->getConfigAttr('aws', 'account', 'region', 'default');
        $profile = $this->laConfig->getConfigAttr('aws', 'account', 'profile', 'ec2');
        
        // Create a client config assoc array, capture the iam profile and add to client config.
        // If not ec2 use the instance role, i.e. omit the profile from client config data.
        $clientConfig = [
            'region' => $region,
            'version' => 'latest'
        ];

        // Add the profile name if not ec2 and make credentials file readable to access
        // the profile's credential specs.
        if ($profile !== LaConfig::EC2PROFILE)
        {
            $clientConfig['profile'] = $profile;
            putenv('HOME=/home/liberanalytics');
            putenv('AWS_SHARED_CREDENTIALS_FILE=/home/liberanalytics/.aws/credentials');            
        }

        return $clientConfig;
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