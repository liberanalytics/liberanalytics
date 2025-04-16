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
     * 
     */
    public function vxValidateRefdata($laDb, $pxSource, $pxKey, $pxData)
    {
        $vxFx = $this->getConfigAttr('liberanalytics', 'vx', $pxSource, $pxKey);
        $vxValidateRefdata = call_user_func(array($this, $vxFx), $laDb, $pxData);
        return $vxValidateRefdata;
    }

    /**
     * 
     */
    public function vxSanitizeText(array $pxSearchChars, string $pxReplaceChar, string $pxSanitizeText): string
    {
        $vxSanitizeText = trim($pxSanitizeText);
        $vxSanitizeText = str_replace($pxSearchChars, $pxReplaceChar, $pxSanitizeText);
        return $vxSanitizeText;
    }

    /**
     * 
     */
    public function vxText($pxValidateText, int $pxTextLen = 1024): bool
    {
        $vxText = (strlen($pxValidateText) <= $pxTextLen) ? true : false;
        return $vxText;
    }

    /**
     * Returns an array based on splitting a string by a regex.
     * 
     * @return array
     */
    public function getSzVxArray(

        string $pxSzList, 
        mixed $pxRegexDelimiter, 
        string $pxReturnDelimiter,
        array $pxSearchChars, 
        string $pxReplaceChar

    ): array

    {
        //
        $getSzVxList = [];
        $subject = preg_split($pxRegexDelimiter, $pxSzList);
        $subjectCount = count($subject);

        //
        foreach ( $subject as $index => $val )
        {
            // Remove unwanted characters, replace commas with spaces, then replace spaces with commas.
            $szVal = $val;
            $szVal = $this->vxSanitizeText($pxSearchChars, $pxReplaceChar, $szVal);

            //
            if (!$this->vxText($szVal))
            {
                throw new Error("{$val} is invalid.");
            }
            
            //
            $getSzVxList[] = $szVal;                

        }

        return $getSzVxList;
    }

    /**
     * 
     * 
     * @return string|int
     */
    public function getPostGreList($pxArray, $pxElementType = 'string'): string
    {
        $getPostGreArray = "{" . implode(',', array_map(fn($element): string => '"' . addslashes($element). '"', $pxArray)) . "}";
        return $getPostGreArray;
    }

    /**
     * 
     * 
     */
    public function vxPostCategory($pxLaDb, $pxCategory)
    {

        $vxPostCategories = false;
        if (!empty($pxCategory))
        {
            $postCategories = $pxLaDb->getPostCategories(NULL);
            $vxPostCategory = (in_array($pxCategory, $postCategories)) ? true : false;
        }
        else
        {
            $vxPostCategory = true;
        }

        return $vxPostCategory;
    }

}

?>