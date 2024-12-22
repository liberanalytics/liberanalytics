<?php

    /**
     *
     *
     */	
    class evflowFlatFiles {

        private static $strProgFilePath = __DIR__;
        //private static $strFlatFilePath = __DIR__;
        private static $strFlatFilePath = "C:\\evflow_dw_flat_files";
        private static $strODBCDataSource = 'odbc:awsr32';

        private $arrayQuerySources;
        private $connDW;

        public function __construct() {
            
            $this->setQuerySources();
            $this->setDWConnection();

        }

        /**
         *
         *
         */        
		public function getClassVar($strInstanceVariable) {
			
			if (property_exists($this, $strInstanceVariable)) {				
				if (isset($strInstanceVariable)) { return $this->$strInstanceVariable; }
				else { return false; }			
			}			
            else {
                return false;
            }
			
        }
        
		/**
		 *
		 *
		 */         
        private function writeLog($logFile, $message) {

            //
            $strTime = date_format(new DateTime(), "Y-m-d H:i:s");
            $message = $message . " @ " . $strTime . "." . "\r\n";

            //
            $logFile = self::$strProgFilePath . "\\" . $logFile;
            file_put_contents($logFile, $message, FILE_APPEND);

        }



		/**
		 *
		 *
		 */
        private function setQuerySources() {

            $this->arrayQuerySources = array(

                'evflow_claims.txt'                 =>  'evflow_claims.sql',
                'evflow_agencies.txt'               =>  'evflow_agencies.sql',
                'evflow_markets.txt'                =>  'evflow_markets.sql',
                'evflow_devero_systems.txt'         =>  'evflow_devero_systems.sql',                
                'evflow_claim_types.txt'            =>  'evflow_claim_types.sql',
                'evflow_claim_types_hospice.txt'    =>  'evflow_claim_types_hospice.sql',
                'evflow_claim_statuses.txt'         =>  'evflow_claim_statuses.sql',
                'evflow_insurances.txt'             =>  'evflow_insurances.sql'

            );

        }

		/**
		 *
		 *
		 */
        private function setDWConnection() {

            //
            try {
                $this->connDW = new PDO(self::$strODBCDataSource);
                $this->connDW->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            //
            catch (PDOException $exception) {
                $this->writeLog(
                    'error.txt', 
                    "Unable to connect to AWS Redshift Instance:" . "\r\n" . $exception
                );
                exit(0);
            }
        }

		/**
		 *
		 *
		 */        
        private function getQueryFromFile($qryFile) {

            $qry = file_get_contents(self::$strProgFilePath . "\\" . $qryFile);
            return $qry;

        }
        
		/**
		 *
		 *
		 */        
        private function getQueryPreparedStmt($sql) {

            //
            try {
                $stmt = $this->connDW->prepare($sql);
                return $stmt;
            }
            //
            catch (PDOException $exception) {
                $this->writeLog(
                    'error.txt', 
                    "Unable to prepare sql " . $sql . "\r\n" . $exception
                );
                exit(0);                

            }

        }

		/**
		 *
		 *
		 */
        private function writeData($dataFile, $data) {

            //
            file_put_contents(self::$strFlatFilePath . "\\" . $dataFile, $data, FILE_APPEND);

        }

		/**
		 *
		 *
		 */
        private function emptyDataFile($dataFile) {

            //
            file_put_contents(self::$strFlatFilePath . "\\" . $dataFile, '');

        }

		/**
		 *
		 *
		 */
        public function generateData() {

            //
            $this->writeLog('log.txt', 'Evflow flat files generate data beginning ');
            //
            foreach ($this->arrayQuerySources as $dataFile => $qryFile) {

                //
                $sql        = $this->getQueryFromFile($qryFile);
                $stmt       = $this->getQueryPreparedStmt($sql);

                //
                if ($stmt->execute()) {

                    $fHandle = fopen(self::$strFlatFilePath . "\\" . $dataFile, 'w');

                    // Empty existing data from data file.
                    //$this->emptyDataFile($dataFile);

                    // Record query source that is running to log file.
                    $this->writeLog('log.txt', $qryFile . ' SQL running ');

                    //
                    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {

                        $colCount   = count($row);
                        $colIter    = 0;
                        $data       = "";

                        //
                        foreach ($row as $attrs) {

                            $datum = ( ($colIter + 1) != $colCount ) ? $row[$colIter] . "\t" : $row[$colIter] . "\n";
                            $data .= $datum;
                            $colIter += 1; 

                        }                       

                        //
                        fwrite($fHandle, $data);
                    }

                    fclose($fHandle);

                    // Record query source that has completed to log file.
                    $this->writeLog('log.txt', $qryFile . ' SQL ending '); 

                }
                //
                else {
                    echo $sql;
                    $this->writeLog('error.txt', 'Error executing SQL ' . $sql);    
                }

            }

            //
            $this->writeLog('log.txt', 'Evflow flat files generate data ending ');            

        }



    }


?>