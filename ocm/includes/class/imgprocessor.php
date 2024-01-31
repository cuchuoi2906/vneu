<?php
/**
 * User: canhnm
 * Date: 10/4/17
 * Time: 09:21
 */
/*
* Test with the below of compression levels to find your best quality compression with lowest images size
* 
imagick::COMPRESSION_UNDEFINED (integer)
imagick::COMPRESSION_NO (integer)
imagick::COMPRESSION_BZIP (integer)
imagick::COMPRESSION_FAX (integer)
imagick::COMPRESSION_GROUP4 (integer)
imagick::COMPRESSION_JPEG (integer)
imagick::COMPRESSION_JPEG2000 (integer)
imagick::COMPRESSION_LOSSLESSJPEG (integer)
imagick::COMPRESSION_LZW (integer)
imagick::COMPRESSION_RLE (integer)
imagick::COMPRESSION_ZIP (integer)
imagick::COMPRESSION_DXT1 (integer)
This constant is available if Imagick has been compiled against ImageMagick version 6.4.0 or higher.
imagick::COMPRESSION_DXT3 (integer)
This constant is available if Imagick has been compiled against ImageMagick version 6.4.0 or higher.
imagick::COMPRESSION_DXT5 (integer)

getImageCompression will return index as following:
imagick::COMPRESSION_UNDEFINED    0
imagick::COMPRESSION_NO    1
imagick::COMPRESSION_BZIP    2
imagick::COMPRESSION_DXT1    3
imagick::COMPRESSION_DXT3    4
imagick::COMPRESSION_DXT5    5
imagick::COMPRESSION_FAX    6
imagick::COMPRESSION_GROUP4    7
imagick::COMPRESSION_JPEG    8
imagick::COMPRESSION_JPEG2000    9
imagick::COMPRESSION_LOSSLESSJPEG    10
imagick::COMPRESSION_LZW    11
imagick::COMPRESSION_RLE    12
imagick::COMPRESSION_ZIP    13
 */

class ImageCompressor {
    /* Define white list for image compression */ 
    var $arrExtWhiteList = array('jpg' => array('image/jpeg'), 'jpeg' => array('image/jpeg'), 'png' => array('image/png', 'image/x-png'));

    /* define input */
    var $type = null;
    var $source = null;
    var $destination=null; 
    var $quality=85;
    var $saveBk = false;
    var $bkSubfix=null;
    var $compressionToReduce = 5;

    var $retError = array();
    var $retMessage = array();
    /*
    * Type: int. 0 single file, 1 multiples file, 2 directory
    * Source: single filename, array filenames, directory. Depends on $type input
    * 
    * Quality: Quality for compression
    * bk_subfix: if not null then backup old filename into filename.ext.bak. Eg: image.jpg => image.jpg.bak after compression complete
     */
    function __construct($type, $source, $destination, $quality, $saveBk, $bkSubfix, $compressionToReduce=5) {
       $this->type = $type;
       $this->source = $source;
       $this->destination = $destination;
       $this->quality = $quality;
       $this->saveBk = $saveBk;
       $this->bkSubfix = $bkSubfix;
       $this->compressionToReduce = $compressionToReduce;

       $this->init();
    }

    function init(){
        switch ($this->type) {
            case 0:
                $ret = $this->processSingle();
                break;
            case 1:
                $ret = $this->processMultiple();
                break;
            case 2:
                $ret = $this->processDirectory();
                break;
            default:
                $this->retError[] = true;
                array_push($this->retMessage, 'Please specific $type');

        }
    }

    function processSingle(){
        //WARNING|ALARM:  DONT NOT REMOVE THE FUCK realpath function, this could avoid of PATH TRANVERSAL ATTACK
        $this->source = realpath($this->source);
        if($this->isFileExisted($this->source)==true && is_file($this->source)){
            $ext = $this->getExt($this->source);

            if($ext && $this->arrExtWhiteList[$ext]){
                switch ($ext){
                    case 'jpg':
                    case 'jpeg':
                        $this->jpegCompressor($this->source);
                        break;
                    case 'png': 
                        $this->pngCompressor($this->source);
                        break;
                    default:
                        $this->retError[] = true;
                        $this->retMessage[] = 'Unrecognize image extension: ' . $this->source;
                }
            }else{
                $this->retError[] = true;
                $this->retMessage[] = 'File is not allowed: ' . $this->source;
            }
        }else{
            $this->retError[] = true;
            $this->retMessage[] = 'File is not existed: ' . $this->source;
        }
    }

    function processMultiple(){
        if(is_array($this->source)){
            $objLength = count($this->source);
            for($i=0; $i<$objLength;$i++){
                $tmpCompress = new ImageCompressor(0, $this->source[$i], null, $this->quality, $this->saveBk, $this->bkSubfix);
                if(is_object($tmpCompress)){
                    $this->retError[] = $tmpCompress->retError[0];
                    $this->retMessage[] = $tmpCompress->retMessage[0];
                }
            }
        }
    }

    function processDirectory(){
        if(!is_dir($this->source)){
            $this->retError[] = true;
            $this->retMessage[] ='Source is not directory: '. $this->source;
        }else{
            // why do we need the fucking open dir while we have glob lol
            foreach(glob($this->source.'*.{jpg,Jpg,JPg,JPG,jpeg,Jpeg,JPeg,JPEg,JPEG,png,Png,PNg,PNG}',GLOB_BRACE) as $file){
                //$imag[] =  realpath($file);
                $tmpCompress = new ImageCompressor(0, $file, null, $this->quality, $this->saveBk, $this->bkSubfix);
                if(is_object($tmpCompress)){
                    $this->retError[] = $tmpCompress->retError[0];
                    $this->retMessage[] = $tmpCompress->retMessage[0];
                }
            }
        }
    }

    function jpegCompressor($sourceFile){
        $copyable = true;
        if($this->bkSubfix != null) {
            if (!copy($sourceFile, $sourceFile.$this->bkSubfix)) {
                $copyable = false;
                $this->retError[] = true;
                $this->retMessage[] = 'Could not copy file: ' .$sourceFile. ' to backup before compress. Process rejected!!!';
            }
        }
        if (is_writable($sourceFile)) {
            $im = new Imagick($sourceFile);
            $imageCompression = $im->getImageCompression();
            $imageCompressionQual = $im->getImageCompressionQuality();
            
            if($imageCompressionQual>0 && ($imageCompressionQual<=$this->quality)){
                $this->quality = $imageCompressionQual - $this->compressionToReduce;
            }

            $im->setImageCompression(Imagick::COMPRESSION_JPEG);
            $im->setImageCompressionQuality($this->quality);  // Với phiên bản PHP 5.5 thì dùng hàm setImageCompressionQuality
            $im->setImageFormat("jpg");
            $im->stripImage();
            // add set sampling factor for imagick
            $im->setSamplingFactors(array('2x2', '1x1', '1x1'));
            // //$thumbnail->thumbnailImage(100,null);  // if you want to make thumbnail and then compress the thumbnail too
            $im->writeImage($sourceFile);
            //$im->destroy();

            if ($copyable) {
                $sizeBefore = filesize($sourceFile . $this->bkSubfix);
                $sizeAfter = filesize($sourceFile);
                
                if ($sizeAfter > $sizeBefore) {
                    copy($sourceFile . $this->bkSubfix, $sourceFile);
                }
                if (!$this->saveBk) {
                    unlink($sourceFile . $this->bkSubfix);
                }
            }

            $this->retError[] = false;
            $this->retMessage[] = 'Compressed: '. $sourceFile;
            //return $this->ret;
        }else{
            $this->retError[] = false;
            $this->retMessage[] = 'Unwriteable: '.$sourceFile;
        }
    }

    function pngCompressor($sourceFile){
        $copyable = true;
        if($this->bkSubfix != null){
            if (!copy($sourceFile, $sourceFile.$this->bkSubfix)) {
                $copyable = false;
                $this->retError[] = true;
                $this->retMessage[] = 'Could not copy file: ' .$sourceFile. ' to backup before compress. Process rejected!!!';
            }
        }
        if (is_writable($sourceFile)) {
            $im = new Imagick($sourceFile);

            //$im->setImageCompression(Imagick::COMPRESSION_JPEG);
            //$im->setImageCompressionQuality(80);
            $im->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);
            $im->setImageCompressionQuality(0);
            $im->setImageFormat("png");
            $im->stripImage();
            $im->writeImage($sourceFile);
            
            if ($copyable) {
                $sizeBefore = filesize($sourceFile . $this->bkSubfix);
                $sizeAfter = filesize($sourceFile);

                if ($sizeAfter > $sizeBefore) {
                    copy($sourceFile . $this->bkSubfix, $sourceFile);
                }
                if (!$this->saveBk) {
                    unlink($sourceFile . $this->bkSubfix);
                }
            }
            $im->destroy();

            $this->retError[] = false;
            $this->retMessage[] = 'Compressed: '. $sourceFile;
            //return $this->ret;
        }else{
            $this->retError[] = false;
            $this->retMessage[] = 'Unwriteable: '.$sourceFile;
        }
    }


    function getExt($str){
        //check extension
        return strtolower(pathinfo($str, PATHINFO_EXTENSION));
    }

    function isFileExisted($filename){
        return file_exists($filename);
    }
}