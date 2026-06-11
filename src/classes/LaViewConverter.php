<?php

namespace classes;

class LaViewConverter {
    public const IMG_FILE_TYPE = 'png';
    public const IMG_FILE_PATH = '/assets/';
    public const IMG_FILE_BASE_SIZE = '820';
    
    public function __construct() {}

    private function escAttr(string $value): string
    {
        $escAttr = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        return $escAttr;
    }

    private function getImgFQPath(string $imgName, string $imgSize = '820'):string
    {        
        $getImgFQPath = 
            self::IMG_FILE_PATH . 
            $imgName . "_" . 
            $imgSize . "." .
            self::IMG_FILE_TYPE;            
        return $getImgFQPath;
    }

    public function convertImage(array $post, int $imgIteration = 1):string
    {
        /* <img src= */
        $convertImage = "<img ";
        $convertImage .= "src=\"" . $this->getImgFQPath($post['post_img']) . "\" ";

        /* <img srcset= */
        $convertImage .= 
            "srcset=\"" . 
            $this->getImgFQPath($post['post_img'], $post['img_01_width']) . " " .
            $post['img_01_width'] . "w, " .
            $this->getImgFQPath($post['post_img'], $post['img_02_width']) . " " .
            $post['img_02_width'] . "w, " .
            $this->getImgFQPath($post['post_img'], $post['img_03_width']) . " " .
            $post['img_03_width'] . "w, " .
            $this->getImgFQPath($post['post_img'], $post['img_04_width']) . " " .
            $post['img_04_width'] . "w\" " .
            "alt=\"" . $this->escAttr($post['short_desc']) . "\" ";

        /* fetchpriority = high for high priority images */
        $imgPriority = 
            ($imgIteration == 1) ? "fetchpriority=\"high\"" : "loading=\"lazy\"";

        /* decode = async let browser decide to keep rendering with or without blocking */
        $convertImage .= 
            $imgPriority . " " .
            "decoding = \"async\"" .
            ">";        
        return $convertImage;        
    }

    public function getPostHeader(array $post):string 
    {
        /* Post header variables from the post record. */
        $postShortDesc = $this->escAttr($post['short_desc']);
        $postLongDesc = $this->escAttr($post['long_desc']);
        $postDate = $this->escAttr(date("F j, Y", strtotime($post['post_created'] ?? 'now')));
        $postISODate = $this->escAttr(date("Y-m-d", strtotime($post['post_created'] ?? 'now')));

        /* Formation of the html for the post header. */
        $getPostHeader = "<h1 class=\"post-heading site-font-brand\">" . $postShortDesc . "</h1>";
        $getPostHeader .= "<p>";
        $getPostHeader .= "<time datetime=\"$postISODate\" class=\"site-font-date-std\">" . $postDate . "</time>";
        $getPostHeader .= "</p>";
        $getPostHeader .= "<h2 class=\"post-desc\">" . $postLongDesc . "</h2>";
        $getPostHeader .= $this->convertImage($post);
        return $getPostHeader;
    }
}

?>
