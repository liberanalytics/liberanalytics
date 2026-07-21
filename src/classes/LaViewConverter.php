<?php

namespace classes;

class LaViewConverter {

    public const IMG_FILE_TYPE = 'png';
    public const IMG_FILE_PATH = '/assets/';
    public const IMG_CONTEXT_INDEX = 'index';
    public const IMG_CONTEXT_POSTSEARCH = 'postsearch';
    
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

    private function getPictureMedia(string $imgContext):string
    {
        $getPictureMedia = match ($imgContext) {
            self::IMG_CONTEXT_INDEX => 'media="(min-width: 61.25em)"' . "\n",
            self::IMG_CONTEXT_POSTSEARCH => 'media="(min-width: 40em)"' . "\n"
        };
        return $getPictureMedia;
    }

    private function getSrcSet(string $imgName, array $srcSetSizes):string 
    {
        $getSrcSet = "srcset=\"";
        $srcSetCount = count($srcSetSizes);
        for($iter = 0; $iter < $srcSetCount; $iter++)
        {
            $getSrcSet .= $this->getImgFQPath($imgName, $srcSetSizes[$iter]) . " ";
            $getSrcSet .= $srcSetSizes[$iter] . "w" . (($iter + 1) === $srcSetCount ? '"' : ',') . "\n";
        }
        return $getSrcSet;
    }    

    private function getImg(string $imgName, string $imgAlt, int $imgIteration, array $srcSetSizes):string
    {
        $getImg = "<img" . "\n";
        $getImg .= 'src="' . $this->getImgFQPath($imgName) . "\"\n";
        $getImg .= $this->getSrcSet($imgName, $srcSetSizes);
        $getImg .= 'sizes="100vw"' . "\n";
        $getImg .= 'alt="' . $this->escAttr($imgAlt) . '"' . "\n";
        $getImg .= (($imgIteration == 1) ? 'fetchpriority="high"' : 'loading="lazy"') . "\n";
        $getImg .= 'decoding="async"' . "\n";
        $getImg .= ">";
        return $getImg;
    }

    public function getPostPicture(array $post, string $imgContext, int $imgIteration = 1):string
    {
        /* */
        $thumbs = array
        (
            $post['thumb_01_width'],
            $post['thumb_02_width'],
            $post['thumb_03_width']
        );
        $imgs = array
        (
            $post['img_01_width'],
            $post['img_02_width'],
            $post['img_03_width'],
            $post['img_04_width']
        );
        $getPostPicture = "<picture>" . "\n" . "<source" . "\n";
        $getPostPicture .= $this->getPictureMedia($imgContext);        
        $getPostPicture .= $this->getSrcSet($post['post_img'], $thumbs);
        $getPostPicture .= 'sizes="' . $post['thumb_01_width'] . 'px"' . ">" . "\n";
        $getPostPicture .= $this->getImg($post['post_img'], $post['short_desc'], $imgIteration, $imgs) . "\n";
        $getPostPicture .= "</picture>";
        return $getPostPicture;        
    }

    public function getPostImage(array $post, int $imgIteration = 1): string
    {
        $imgs = array
        (
            $post['img_01_width'],
            $post['img_02_width'],
            $post['img_03_width'],
            $post['img_04_width']
        );

        $getPostImage = "<img" . "\n";
        $getPostImage .= 'src="' . $this->getImgFQPath($post['post_img']) . "\"\n";
        $getPostImage .= $this->getSrcSet($post['post_img'], $imgs);
        $getPostImage .= 'sizes="(max-width: 980px) 100vw, 980px"' . "\n";
        $getPostImage .= 'alt="' . $this->escAttr($post['short_desc']) . '"' . "\n";
        $getPostImage .= (($imgIteration == 1) ? 'fetchpriority="high"' : 'loading="lazy"') . "\n";
        $getPostImage .= 'decoding="async"' . "\n";
        $getPostImage .= ">";

        return $getPostImage;
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
        $getPostHeader .= "<h2 class=\"post-desc site-font-std\">" . $postLongDesc . "</h2>";
        $getPostHeader .= $this->getPostImage($post);
        return $getPostHeader;
    }
}

?>
