<?php 

/**
 * @author bangnd <bangnd@24h.com.vn>
 */
class Uploader 
{
	//  file 
	protected $file;
	protected $fileNameOrgin;
	protected $fileMimeType;
	protected $fileSize;
	protected $fileTmpName;
	protected $extension;
	protected $fileType = null;
    protected $extendFileName = '';
	// upload
	protected $uploadPath;
	protected $rootDirectory;
	protected $chmod = 0777; // production: 755
	protected $fileName;
	// validation
	protected $maxFileSize;
	protected $allowedExtensions = array();
	protected $allowedMimeTypes = array();

	protected $isUploaded = false; 

	protected $fileContent = '';
	protected $hash = '';
	// error
	public $errors = array();

	function __construct($file, $uploadPath = '')
	{
		if(!intval($file) || $file[ 'error' ] != UPLOAD_ERR_OK) {
			throw new \Exception("Có lỗi xảy ra khi tải lên");
		}

		$this->file($file);

		$this->setUploadPath($uploadPath);

		$this->maxFileSize = ini_get( 'upload_max_filesize' );
	}

	public function file($file)
	{
		$this->file 			= $file;
		$this->fileNameOrgin 	= $this->file[ 'name' ];
		$this->fileSize 		= $this->file[ 'size' ];
		$this->fileTmpName 		= $this->file[ 'tmp_name' ];
		$this->hash 			= sha1_file( $this->fileTmpName );
		$this->fileMimeType 	= $this->file[ 'type' ];
		$this->extension 		= strtolower( substr( $this->fileNameOrgin, strripos($this->fileNameOrgin, '.') + 1 ) );
		$this->fileType 		= $this->getFileType();

		return $this;
	}

	public function setMaxFileSize($maxFileSize)
	{
		$this->maxFileSize = (int) $maxFileSize;

		return $this;
	}

	public function setAllowedFileExtensions(array $extensions)
	{
		$this->allowedExtensions = array_merge( $this->allowedExtensions, $extensions );

		return $this;
	}

	public function setAllowedMimeTypes(array $mimeTypes)
	{
		$this->allowedMimeTypes = array_merge( $this->allowedMimeTypes, $mimeTypes );

		return $this;
	}

	public function setUploadPath($uploadPath)
	{
		$this->uploadPath = $uploadPath;

		return $this;
	}

	public function log($message)
	{
		$this->errors[] = $message;
	}
	
	// get the first error
	public function getError()
	{
		return reset($this->errors);
	}

	public function setFileName($fileName = null)
	{
		if(!$fileName) { // neu ko truyen vao file name thi tu dong tao
			$fileName = basename( $this->fileNameOrgin, '.' . $this->extension );
		}		
		// loại bỏ các kí tự đặc biệt khỏi tên file
		$fileName = preg_replace('/[^\-a-zA-Z0-9_]/', '-', _utf8_to_ascii( $fileName ));
		$this->fileName = $fileName . '-' . round(microtime(true)). '-' . rand(1,100) . '.' . $this->extension;
	}

    /**
     * bulk set validate properties
     * @author bangnd <bangnd@24h.com.vn>
     * @param array $config
     * @return Uploader
     */
	public function setConfig(array $config) {
		if(!empty($config[ 'max_file_size' ])) 	  $this->maxFileSize 		= (int) $config[ 'max_file_size' ];
		if(!empty($config[ 'allow_mime_types' ])) $this->allowedMimeTypes 	= $config[ 'allow_mime_types' ];
		if(!empty($config[ 'allow_extensions' ])) $this->allowedExtensions 	= $config[ 'allow_extensions' ];
		return $this;
	}


	/**
	 * Process uploading file
	 * @return boolean
	 */
	public function upload()
	{
		if($this->validate()) {

			if (in_array($this->fileType, ['image', 'video'])) {
			    if ($this->fileType == 'image') {
                    $uploadObj = new  upload_image_block();
                    $result =  $uploadObj->act_upload_single_image($this->file, $this->maxFileSize, [0,0], '', implode(',', $this->allowedExtensions));
                } else {
                    $uploadObj = new upload_video_block();
                    $result = $uploadObj->act_upload_single_video($this->file, '', $this->maxFileSize, implode(',', $this->allowedExtensions), '',$this->extendFileName,0, 0);
                }

                if (count($result['errors']) > 0) {
                    $this->log($result['errors'][0]);
                    return false;
                } else {
                    $this->isUploaded = true;
                    $segments = explode('/', $result['file_path']);
                    $this->fileName = end($segments);
                    $this->uploadPath = rtrim(ROOT_FOLDER, '/') . '/' . ltrim(str_replace($this->fileName, '', $result['file_path']), '/');
                    return true;
                }
            } else {
                // create dir if not exists
                if(!is_dir( $this->uploadPath )){
                    mkdir( $this->uploadPath, $this->chmod, true );
                }
                // chua co filename thi tu dong tao
                if (!$this->fileName) {
                    $this->setFileName();
                }
                if(move_uploaded_file( $this->fileTmpName, $this->getFilePath() )) {
                    $this->isUploaded = true;
                    return true;
                }
            }
		}

		$this->log( vsprintf( 'Có lỗi xảy ra khi tải lên file \'%s\'', $this->fileNameOrgin ) );
		return false;
	}

	public function validate()
	{
		if($this->checkUploadPath()
			&& $this->checkFileSize() 
			&& $this->checkFileExtension() 
			&& $this->checkMimeType()) {
			return true;
		}
		return false;
	}

	public function checkUploadPath() {
		if(!$this->uploadPath || !preg_match('/^(\/?[a-zA-Z0-9\/\-\._]+)+$/', $this->uploadPath)) {
			$this->log('Upload path is not set or invalid format, accept only letters (a-z), digits (0-9), hyphen (-), underscore (_) and dot (.)');
			return false;
		}
		return true;
	}
	
	public function checkMimeType()
	{
		if(!empty($this->allowedMimeTypes) 
			&& in_array($this->fileMimeType, $this->allowedMimeTypes)) {
			return true;
		}
		$this->log(vsprintf('File \'%s\' có đinh dạng không hợp lệ', $this->fileNameOrgin));

		return false;
	}

	public function checkFileExtension()
	{
		if(!empty( $this->allowedExtensions ) 
			&& in_array( $this->extension, $this->allowedExtensions )) {
			return true;
		}
		$this->log(vsprintf('File \'%s\' có phần mở rộng không hợp lệ', $this->fileNameOrgin));

		return false;
	}

	public function checkFileSize()
	{
		if( $this->fileSize > $this->maxFileSize ) {
			$this->log(vsprintf('File \'%s\' có kích thước quá lớn', $this->fileNameOrgin));
			return false;
		}
		return true;
	}

	/**
	 * duong dan tuyet doi cua file
	 * ex:'/home/24h/public/upload/css/file.css'
	 */
	public function getFilePath()
	{
		return rtrim($this->uploadPath, '/') . '/' .  $this->fileName;
	}

	/**
	 * duong dan file doi voi thu muc goc cua website
	 * ex: /upload/css/file.css
	 */
	public function getUploadFilePath()
	{
		return $this->getUploadPath() . $this->fileName;
	}
	/**
	 * duong dan thu muc chua file so voi thu muc goc cua website
	 * ex: /upload/css/
	 */
	public function getUploadPath()
	{
		return '/'. trim(str_replace(ROOT_FOLDER, '', $this->uploadPath), '/') . '/';
	}

	public function getFileName()
	{
		return $this->fileName;
	}

	public function getOriginalFileName()
	{
		return $this->fileNameOrgin;
	}

	public function getFileExtension()
	{
		return $this->extension;
	}

	public function getFileMimeType()
	{
		return $this->fileMimeType;
	}

	public function info()
	{
		if(!$this->isUploaded) {
			throw new \Exception("File chưa được tải lên.");
		}
		return array(
			'file_name' 		=> $this->fileName,
			'file_name_origin' 	=> $this->fileNameOrgin,
			'path' 				=> $this->getUploadPath(),
			'file_path'			=> $this->getUploadFilePath(),
			'file_type' 		=> $this->fileType,
			'mime_type' 		=> $this->fileMimeType,
			'extension' 		=> $this->extension,
			'file_meta' 		=> $this->getFileMetaData(),
			'file_content' 		=> $this->getFileContent(),
			'hash' 				=> $this->hash
		);
	}

	public function getFileContent() {

		if(!$this->isUploaded) {
			throw new \Exception("File chưa được tải lên.");
		}

		if(in_array($this->fileType, ['html', 'css', 'js'])) {
			return file_get_contents($this->getFilePath());
		}
		return '';
	}

	public function getFileMetaData()
	{
		$data = array();

		if ($this->fileType == 'image') {
			$imageInfo = getimagesize($this->getFilePath());
			$data['width'] = $imageInfo[0];
			$data['height'] = $imageInfo[1];
		}
		return $data;
	}

	// TODO: upload image by base64 string and url

	public function getFileType()
	{
		$fileTypes = array(
			'mp4' 		=> 'video',
			'flv' 		=> 'video',
			'woff' 		=> 'font',
			'woff2' 	=> 'font',
			'otf' 		=> 'font',
			'ttf' 		=> 'font',
			'eot' 		=> 'font',
			'gif' 		=> 'image',
			'png' 		=> 'image',
			'jpeg' 		=> 'image',
			'jpg' 		=> 'image',
			'svg' 		=> 'image',
			'html' 		=> 'html',
			'htm' 		=> 'html',
			'aac' 		=> 'audio',
			'mp3' 		=> 'audio',
			'css' 		=> 'css',
			'js' 		=> 'js'
		);

		return !empty($fileTypes[$this->extension]) ? $fileTypes[$this->extension] : 'unknown';
	}

	public function cropImage($x, $y, $w, $h)
	{
		if ($this->fileType == 'image') {

			$file_path = $this->getFilePath();
			list($width, $height) = getimagesize( $file_path );

			switch ($this->extension) {
		        case 'gif': 
		        	$src_img = imagecreatefromgif($this->file); break;
		        case 'jpeg': 
		        case 'jpg':
		        	$src_img = imagecreatefromjpeg($this->file); break;
		        case 'png': 
		        	$src_img = imagecreatefrompng($this->file); break;
		        default: 
		        	throw new \Exception("Loại ảnh không xác định");
		        	break;
		    }

		    $dst_img = imagecreatetruecolor($w, $h);
		    imagecopyresampled($dst_img, $src_img, 0, 0, $x, $y, $w, $h, $width, $height);

    		switch ($this->extension) {
    	        case 'gif': 
    	        	$is_ok = imagegif($dst_img, $file_path); break;
    	        case 'jpeg': 
    	        case 'jpg':
    	        	$is_ok = imagejpeg($dst_img, $file_path, 100); break;
    	        case 'png': 
    	        	$is_ok = imagepng($dst_img, $file_path); break;
    	        default: 
    	        	$is_ok = false;
    	        	break;
    	    }

    	    imagedestroy($dst_img);

    	    if ($is_ok === false) {
		    	throw new \Exception("Lỗi cắt ảnh.");
		    }
		}
	}
    public function setextendFileName($extendFileName)
	{
		if(empty($extendFileName)){
			return;
		}		
		// loại bỏ các kí tự đặc biệt khỏi tên file
		$extendFileName = preg_replace('/[^\-a-zA-Z0-9_]/', '_', _utf8_to_ascii( $extendFileName ));
		$this->extendFileName = $extendFileName;
	}
}