<?php
/**
 * User: canhnm
 * Date: 10/4/17
 * Time: 09:21
 */
class gifResize {
    /* Define white list for image compression */ 
    var $arrExtWhiteList = array('gif' => array('image/gif'));

    var $retError = array();
    var $retMessage = array();

    function __construct($source, $size=array(), $nameToChange='', $quality=null) {
       $this->source = $source;
       $this->size = $size;
       $this->nameToChange = $nameToChange;
       $this->quality = $quality;
       $this->resize($this->source,$this->size,$this->nameToChange,$this->quality);
    }
    function resize($source, $size=array(), $nameToChange='', $quality=null){
        if(file_exists($this->source)){
            $source = realpath($this->source);
            if($arrExtWhiteList[strtolower( pathinfo($source, PATHINFO_EXTENSION) )] !=='gif' || mime_content_type($source)!=='gif'){
                if($this->size['width'] || $this->size['height']){
                    if(class_exists('Imagick')){
                        $image = new Imagick($source);
                        $format = $image->getImageFormat();
                        if ($format == 'GIF') {
                            $image = $image->coalesceImages(); 
                            $imageNameToChange = '';
                            if($this->nameToChange===''){
                                $imageNameToChange = pathinfo($this->source)['dirname'].basename($this->source, '.gif'). '-resized' .'.gif';
                            }else{
                                $imageNameToChange = $this->nameToChange;
                            }
                            $image = $image->coalesceImages(); 

                            foreach ($image as $frame) { 
                                $frame->thumbnailImage($this->size['width'], $this->size['height']); 
                                $frame->setImagePage($this->size['width'], $this->size['height'], 0, 0); 
                            } 

                            $image = $image->deconstructImages(); 
                            $image->writeImages($imageNameToChange, true); 
                            
                            $image->clear();
                            $image->destroy();
                        }else{
                            $this->retError[] = true;
                            $this->retMessage[] = 'Image is not GIF!';
                        }
                    }else{
                        $this->retError[] = true;
                        $this->retMessage[] = 'Class Imagick not found, check your php modules again!';
                    }
                }else{
                    $this->retError[] = true;
                    $this->retMessage[] = 'Please input width and height for the image to be resized!';
                }
            } else{
                $this->retError[] = true;
                $this->retMessage[] = 'File: ' . $source . ' is not gif file!';
            }
        }else{
            $this->retError[] = true;
            $this->retMessage[] = 'File: ' . $source . ' not found!';
        }
    }
}