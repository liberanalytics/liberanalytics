<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
//use Aws\Kms\KmsClient;


/**
 * List your Amazon S3 buckets.
 *
 * This code expects that you have AWS credentials set up per:
 * https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials.html
 */

 
//Create a S3Client
$kmsClient = new Aws\Kms\KmsClient([
    'profile' => 'default',
    'region' => 'us-east-2',
    'version' => 'latest'
]);

$keys = $kmsClient->ListKeys();



echo "";

?>