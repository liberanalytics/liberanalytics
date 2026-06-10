<?php

namespace classes;

class LaViewConverter {
    public const IMG_FILE_TYPE = 'png';
    public const IMG_FILE_PATH = '/assets/';
    public const IMG_FILE_BASE_SIZE = '820';
    public function __construct() 
    {

    }

// $post = [
//     'post_name'     => 'whydata',
//     'post_category' => 'Why Data',
//     'post_created'  => '2026-01-01 12:38:26.183569',
//     'short_desc'    => 'Why do we need Data?',
//     'long_desc'     => 'Explores the importance...',
//     'intro'         => 'Why do we go through...',
//     'post_img'      => 'whydata',
//     'img_01_width'  => 288,
//     'img_02_width'  => 820,
//     'img_03_width'  => 1080,
//     'img_04_width'  => 1640
// ];  

// 	<img
//   src="/assets/whydata_820.png"
//   srcset=
//   "
//     /assets/whydata_288.png 288w,
//     /assets/whydata_820.png 820w,
// 	/assets/whydata_1080.png 1080w,
//     /assets/whydata_1640x655.png 1640w
//   "
 
 
//   alt="Why data…"
//   loading="lazy"
//   decoding="async"
// />

    private function getImgFQPath(string $imgName, string $imgSize = '820'):string {
        
        $getImgFQPath = 
            self::IMG_FILE_PATH . 
            $imgName . "_" . 
            $imgSize . "." .
            self::IMG_FILE_TYPE;
            
        return $getImgFQPath;

    }

    public function convertImage(array $post):string {
        
        $convertImage = "<img ";
        $convertImage .= "src = " . $this->getImgFQPath($post['post_img']);
        
        return $convertImage;
        
    }

}

?>