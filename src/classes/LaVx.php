<?php

require_once('LaConfig.php');

/**
 * 
 */
class LaVx extends LaConfig
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    public function sanitizeText(array $pxSearchChars, string $pxReplaceChar, string $pxSanitizeText): string
    {
        $sanitizeText = trim($pxSanitizeText);
        $sanitizeText = str_replace($pxSearchChars, $pxReplaceChar, $pxSanitizeText);
        return $sanitizeText;
    }

    /**
     * 
     */
    public function validateText($pxValidateText, int $pxTextLen = 1024): bool
    {
        $validateText = (strlen($pxValidateText) <= $pxTextLen) ? true : false;
        return $validateText;
    }

    /**
     * 
     */
    public function getSzVxList(

        string $pxSzList, 
        mixed $pxRegexDelimiter, 
        string $pxReturnDelimiter,
        array $pxSearchChars, 
        string $pxReplaceChar

    ): string

    {
        //
        $getSzVxList = "";
        $subject = preg_split($pxRegexDelimiter, $pxSzList);
        $subjectCount = count($subject);

        //
        foreach ( $subject as $index => $val )
        {
            // Remove unwanted characters, replace commas with spaces, then replace spaces with commas.
            $szVal = $val;
            $szVal = $this->sanitizeText($pxSearchChars, $pxReplaceChar, $szVal);

            //
            if (!$this->validateText($szVal, 4))
            {
                throw new Error("{$val} is invalid.");
            }
            
            //
            $getSzVxList .= (($index + 1) < $subjectCount) ? $szVal . $pxReturnDelimiter : $szVal;                

        }

        return $getSzVxList;
    }

}

?>