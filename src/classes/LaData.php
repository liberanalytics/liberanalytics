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
        bool $pFeatured,
        string $pPostName = NULL,        
        string $pPostCategory = NULL, 
        array $pOrderBy = NULL,
        int $pLimit = NULL
    )
    {
        //
        $sqlAliasPosts = "la_posts";

        //
        $featuredOperator   = ($pFeatured != FALSE) ? ">" : "=";

        $postCategoryPredicate  =
            ($pPostCategory != NULL) ? "$sqlAliasPosts.post_category = '$pPostCategory'" :
            "$sqlAliasPosts.post_category = $sqlAliasPosts.post_category";

        $postNamePredicate  =
            ($pPostName != NULL) ? "$sqlAliasPosts.post_name = '$pPostName'" :
            "$sqlAliasPosts.post_name = $sqlAliasPosts.post_name";            

        $limitClause = ($pLimit != NULL) ? "LIMIT " . (string) $pLimit : "LIMIT 1000";

        //
        if ($pOrderBy != NULL) {

            $orderByCount = count($pOrderBy);
            $orderByIter = 1;
            $orderByClause = "ORDER BY ";
            $orderByColumns = '';
            
            foreach ($pOrderBy AS $key => $val) {
                $orderByColumns .= "$sqlAliasPosts.$key $val";
                $orderByColumns .= ($orderByIter < $orderByCount) ? ", " : "";
                $orderByIter += 1;
            }
            $orderByClause .= $orderByColumns;

        } 
        else {
            $orderByClause = "ORDER BY 1";
        }

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
        
        FROM liberanalytics.posts AS {$sqlAliasPosts}

        WHERE 1=1
        AND la_posts.featured " . $featuredOperator . " 0 " .
        "\n\t" ."AND " . $postNamePredicate . 
        "\n\t" ."AND " . $postCategoryPredicate .
        "\n\t" . $orderByClause .
        "\n\t" . $limitClause . ";";

        //print($qry);

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();
        return $stmt;

    }

    /**
    *
    * @return PDO Statement
    */
    public function getPostCategories( )
    {
        $qry =
        "SELECT

        post_category

        FROM refdata.post_category;

        ";

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();
        return $stmt;        

    }

}
