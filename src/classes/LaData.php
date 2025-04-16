<?php

// require_once('../src/vendor/autoload.php');
require_once('LaConfig.php');
require_once('LaAWS.php');

/**
 * 
 * 
 * 
 * 
 * 
 */
class LaData extends LaConfig
{
    private $laAWS;
    private $conn;

    function __construct()
    {
        parent::__construct();
        $this->laAWS = new LaAWS();
    }

    /**
     * 
     * 
     * 
     * 
     * 
     */
    public function openConn()
    {
        $connFile =
            $this->getConfigAttr('liberanalytics', 'config', 'env', 'dir') .
            $this->getConfigAttr('data', 'database', 'liberanalytics', 'conn_file');

        //chmod($connFile, 0440);
        $handle = fopen($connFile, 'rb');
        //chmod($connFile, 0000);
        $binary = fread($handle, filesize($connFile));
        
        // Decrypt the binary data in the database config file.
        try
        {
            $connData = $this->laAWS->getAWSClient('awsKMS')->decrypt([

                // AWS KMS decrypt P(x)'s - Include EncryptionAlgorithm as best practice.
                'CiphertextBlob' => $binary,
                'EncryptionAlgorithm' => 'SYMMETRIC_DEFAULT',
                'KeyId' => $this->getConfigAttr('aws', 'kms', 'key_desc', 'liberanalytics_db')

            ]);
        }
        // Log any exceptions to the error log group / stream. Exit on failure
        // since remaining program depends on decrypted data.
        catch (Exception $e)
        {
            $this->laAWS->getAWSClient('logError')->log(['Problem decrypting : ' => $e->getAwsErrorMessage()]);
            exit("Unable to decrypt the data in {$connFile}.");
        }
        
        // Decode using base64 decoding, then split data into components per delimiter.
        $connData = base64_decode($connData['Plaintext']);
        $dbParams = explode( $this->getConfigAttr('data', 'database', 'liberanalytics', 'delim'), $connData );    
        $dbHost = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'host_ordinal')];
        $dbName = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'name_ordinal')];
        $dbUser = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'user_ordinal')];
        $dbAuth = $dbParams[$this->getConfigAttr('data', 'database', 'liberanalytics', 'auth_ordinal')];

        // Try to connect to postgres database. Log any errors to CloudWatch Logs.
        try
        {
            $dsn = "pgsql:host={$dbHost};port=5432;dbname={$dbName};user={$dbUser};password={$dbAuth};sslmode=require";
            $this->conn = new PDO($dsn);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        // Connection attempt failed - log failure to CloudWatch Logs and exit.
        catch (PDOException $e)
        {
            $this->laAWS->getAWSClient('logError')->log(['Problem connecting : ' => $e->getMessage()]);
            exit("Unable to connect to host : {$dbHost}.");
        }
    }

    public function closeConn()
    {
        $this->conn = NULL;
    }

    /**
    * 
    * @param bool
    * @param string
    * @param string
    * @param array
    * @param int
    *
    * @return PDO Statement
    */
    public function getPosts
    (
        bool $pxFeatured,
        string $pxPostName = NULL,        
        string $pxPostCategory = NULL,
        array $pxPostTags = NULL,
        array $pxPostKeywords = NULL,
        string $pxOrderBy = NULL,
        string $pxOrderByAscDesc = NULL,
        int $pxLimit = NULL
    )  
    {
        //
        $qry = 
        "SELECT

        la_posts.post_name,
        la_posts.post_category,
        la_posts.post_created,
        la_posts.short_desc,
        la_posts.long_desc,
        la_posts.featured,
        la_posts.post_img,
        la_posts.featured_img,
        la_posts.search_img
        
        FROM liberanalytics.fx_get_posts(?, ?, ?, ?, ?, ?, ?, ?) AS la_posts;";

        $stmt = $this->conn->prepare($qry);

        $stmt->bindParam(1, $pxFeatured, PDO::PARAM_BOOL);
        $stmt->bindParam(2, $pxPostName, PDO::PARAM_STR);
        $stmt->bindParam(3, $pxPostCategory, PDO::PARAM_STR);
        $stmt->bindParam(4, $pxPostTags, PDO::PARAM_STR);
        $stmt->bindParam(5, $pxPostKeywords, PDO::PARAM_STR);
        $stmt->bindParam(6, $pxOrderBy, PDO::PARAM_STR);
        $stmt->bindParam(7, $pxOrderByAscDesc, PDO::PARAM_STR);
        $stmt->bindParam(8, $pxLimit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;

    }

    /**
    *
    * @return PDO Statement
    */
    public function getPostCategories($pxPostCategory = NULL)
    {
        $qry =
        "SELECT

        post_category

        FROM refdata.fx_get_post_categories();

        ";

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();
        return $stmt;        

    }

}
