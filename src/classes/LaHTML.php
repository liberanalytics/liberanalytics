<?php

namespace classes;

class LaHTML {
    public const IMG_FILE_TYPE = 'png';
    public const IMG_FILE_PATH = '/assets/';
    function __construct() 
    {

    }

    private function getImgFullyQualifiedPath(): string
    {
        
    }

    public function renderImg(string $imgFileName, string $imgAlt, array $imgWidths, bool $imgAboveFold, int $imgSrcWidth = 820): string
    {
        $imgSrc = self::IMG_FILE_PATH . $imgFileName . '_' . (string)$imgSrcWidth . '.' . self::IMG_FILE_TYPE;
        $renderImg = "<img src=\"{$imgSrc}\"";

        $renderImg .= ' ' . $imgAlt;
        return $renderImg;

    }

}

?>