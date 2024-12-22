<?php

    /**
     *
     *
     */	 
    function getClassName($strClassName) {
        
        //
        $strClassFile = $strClassName;
        
        //
        $intLen = strlen($strClassName);
        for ($i=0; $i<$intLen; $i++) {
            
            $strReplaceCharClass = '/[A-Z]/';
            $strChar = substr($strClassName, $i, 1);

            //
            if (preg_match($strReplaceCharClass, $strChar)) {
                $strClassFile = str_replace($strChar, '_' . strtolower($strChar), $strClassFile);
            }

        }

        //
        $strClassFile = 'class.' . $strClassFile . '.php';
        return $strClassFile;
    }

    /**
     *
     *
     */	     
    spl_autoload_register(function($strClassName) {
        
        $strClassFile = getClassName($strClassName);
        include $strClassFile;

    });

    /**
     *
     *
     */	    
    $evflowFlatFiles = new evflowFlatFiles();
    $evflowFlatFiles->generateData();    


?>