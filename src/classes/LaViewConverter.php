<?php

namespace classes;

class LaViewConverter {
    public const IMG_FILE_TYPE = 'png';
    public const IMG_FILE_PATH = '/assets/';
    public const IMG_FILE_BASE_SIZE = '820';
    
    public function __construct() {}

    private function getImgFQPath(string $imgName, string $imgSize = '820'):string
    {
        
        $getImgFQPath = 
            self::IMG_FILE_PATH . 
            $imgName . "_" . 
            $imgSize . "." .
            self::IMG_FILE_TYPE;
            
        return $getImgFQPath;

    }

    public function convertImage(int $imgIteration, array $post):string
    {
        
        /* <img src= */
        $convertImage = "<img ";
        $convertImage .= "src = \"" . $this->getImgFQPath($post['post_img']) . "\" ";

        /* <img srcset= */
        $convertImage .= 
            "srcset = \"" . 
            $this->getImgFQPath($post['post_img'], $post['img_01_width']) . " " .
            $post['img_01_width'] . "w, " .
            $this->getImgFQPath($post['post_img'], $post['img_02_width']) . " " .
            $post['img_02_width'] . "w, " .
            $this->getImgFQPath($post['post_img'], $post['img_03_width']) . " " .
            $post['img_03_width'] . "w, " .
            $this->getImgFQPath($post['post_img'], $post['img_04_width']) . " " .
            $post['img_04_width'] . "w\" " .
            "alt = \"" . $post['short_desc'] . "\" ";

        /* fetchpriority=high for high priority images */
        $imgPriority = 
            ($imgIteration == 1) ? "fetchpriority=\"high\"" : "loading=\"lazy\"";

        /* decode=async let browser decide to keep rendering with or without blocking */
        $convertImage .= 
            $imgPriority . " " .
            "decoding = \"async\"" .
            ">";
        
        return $convertImage;
        
    }

    // $postShortDesc	= htmlspecialchars($post['short_desc'] ?? '', ENT_QUOTES, 'UTF-8');
    // $postLongDesc	= htmlspecialchars($post['long_desc'] ?? '', ENT_QUOTES, 'UTF-8');
    // $postImg    	= htmlspecialchars($post['post_img'] ?? '', ENT_QUOTES, 'UTF-8');
    // $postAlt		= $postShortDesc;
    // $postDate		= htmlspecialchars(date("F j, Y", strtotime($post['post_created'] ?? 'now')), ENT_QUOTES, 'UTF-8');
    // $postISODate	= htmlspecialchars(date("Y-m-d", strtotime($post['post_created'] ?? 'now')), ENT_QUOTES, 'UTF-8');
    // $postImgWidth	= (int)$postImgWidth;
    // $postImgHeight	= (int)$postImgHeight;

    // <h1 class="post-heading site-font-brand"><?php echo "{$postShortDesc}"; </h1>
    // <p>
    // <time datetime=" echo "{$postISODate}";" class="site-font-date-std">
    //     echo "{$postDate}";
    // </time>
    // </p>
    // <h2 class="post-desc site-font-std"> echo "{$postLongDesc}";</h2>

    public function getPostHeader():string 
    {

        $getPostHeader = '';

        return $getPostHeader;
    }

}

?>