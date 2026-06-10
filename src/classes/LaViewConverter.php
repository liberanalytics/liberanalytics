<?php

namespace classes;

class LaViewConverter {
    public const IMG_FILE_TYPE = 'png';
    public const IMG_FILE_PATH = '/assets/';
    function __construct() 
    {

    }

    private function getImqFQPath($imgBaseFileName, array $imgFileWidths): string
    {
    
        $getImgFQPath = "srcset= ";
        foreach ($imgFileWidths as $imgWidth) {
            $getImqFQPath .= (string)
        }
        $getImqFQPath = '';
        return $getImqFQPath;
    }

    public function renderImg(string $imgBaseFileName, string $imgAlt, array $imgWidthsSpecs, bool $imgAboveFold, int $imgSrcWidth = 820): string
    {
        $imgSrc = self::IMG_FILE_PATH . $imgBaseFileName . '_' . (string)$imgSrcWidth . '.' . self::IMG_FILE_TYPE;
        $renderImg = "<img src=\"{$imgSrc}\"";


        $renderImg .= ' ' . $imgAlt;
        return $renderImg;

    }

}

?>