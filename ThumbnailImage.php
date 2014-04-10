<?php
class ThumbnailImage{
    private $image;
    private $quality = 100;
    private $mimetype;
    private $imageproperties = array();
    private $initialfilesize;
    
    public function __construct($file, $thumbnailsize = 100){
        //check file
        is_file($file) or die ("File: $file doesn't exist");
        $this->initialfilesize = filesize($file);
        $this->imageproperties = getimagesize($file) or die ("Incorrect file_type");
        
        $this->mimetype = image_type_to_mime_type($this->imageproperties[2]);
        
        switch($this->imageproperties[2]){
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromJPEG($file);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefromGIF($file);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefromPNG($file);
                break;
            default:
                die("couldn't create image");
        }
        
        $this->createThumb($thumbnailsize);
    }
    
    private function createThumb($thumnailsize){
        $srcW = $this->imageproperties[0];
        $srcH = $this->imageproperties[1];
        //only adjust if smaller
        if($srcW > $thumnailsize || $srcH > $thumnailsize){
            $reduction = $this->calculateReduction($thumnailsize);
            $desW = $srcW/$reduction;
            $desH = $srcH/$reduction;
            $copy = imagecreatetruecolor($desW, $desH);
            imagecopyresampled($copy,$this->image,0,0,0,0,$desW,$desH,$srcW,$srcH) or die("image copy failed");
            //destroy original
            imagedestroy($this->image);
            $this->image = $copy;
        }
    }
    
    private function calculateReduction($thumnailsize){
        $srcW = $this->imageproperties[0];
        $srcH = $this->imageproperties[1];
        //adjust
        if($srcW < $srcH){
            $reduction = round($srcH/$thumnailsize);
        } else {
            $reduction = round($srcW/$thumnailsize);
        }
        return $reduction;
    }
    
    public function __destruct(){
        if(isset($this->image)){
            imagedestroy($this->image);
        }
    }
    
    public function getImage(){
        header("Content-type: $this->mimetype");
        switch($this->imageproperties[2]){
            case IMAGETYPE_JPEG:
                imagejpeg($this->image, "", $this->quality);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->image);
                break;
            case IMAGETYPE_PNG:
                //png image quality has to be 0-9
                imagepng($this->image, "", (round($this->quality/10)-1));
                break;
            default:
                die("couldn't create image");
        }
    }
    
    //accessors --------------------------------------------------
    public function getMimeType(){
        return $this->mimetype;
    }
    
    public function getQuality(){
        $quality = null;
        if($this->imageproperties[2] == IMAGETYPE_JPEG || $this->imageproperties[2] == IMAGETYPE_PNG){
            $quality = $this->quality;
        }
        return $quality;
    }
    
    public function getInitialFileSize(){
        return $this->initialfilesize;
    }
}
?>