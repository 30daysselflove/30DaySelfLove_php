<?php
namespace lib;
class UploadedFile
{
	const ERROR_BAD_UPLOAD = 1;
    public static $MAX_FILE_SIZE = 0; //1 Gig
    public $allowUnknownFileType = false;
 	protected $_extension;
 	protected $_type;
 	protected $_tempFile;
 	protected $_isURL;
 	protected $_fileKey;
 	protected $error;


    public static function sanitizeFileName($filename)
    {
        $filename_raw = $filename;
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
        $special_chars = apply_filters('sanitize_file_name_chars', $special_chars, $filename_raw);
        $filename = str_replace($special_chars, '', $filename);
        $filename = preg_replace('/[\s-]+/', '-', $filename);
        $filename = trim($filename, '.-_');
        return apply_filters('sanitize_file_name', $filename, $filename_raw);
    }

 	/**
 	 * Returns true if there is an uploaded file to deal with
 	 */
 	public static function check()
 	{
 		return !empty($_FILES);    
 	}

 	public function __get($key)
 	{
 	    switch($key)
 	    {
 	    	case "extension":
 	    	    return $this->_extension;
 	    	break;
 	    	case "type":
 	    	    return $this->_type;
 	    	break;
 	    }
 	    return null;
 	}
    
 	protected static function getType($type)
 	{
 	
 		switch ($type)
 		{
 				
 			case "image/gif":
 			case 'image/jpg': //This isn't a valid mime type so we should probably remove it
 			case "image/jpeg":
 			case "image/pjpeg":
 			case "image/png":
 			case "image/bmp":
 			case "image/vnd.microsoft.icon":
 			case "application/octet-stream":
 				return "image";
 				break;
            case "video/mp4":
                return "video";
                break;
 			case "application/pdf":
 			case "application/x-pdf":
 			case "application/x-bzpdf":
			case "application/x-gzpdf":
				return "pdf";
				break;
 			default:
 				return "file";
 				break;
 		}
 	}
 	
 	/**
 	 *
 	 * Get the file extension using the MIME type
 	 * @param string $type
 	 */
 	protected static function getExtByMime($type, $name)
 	{
 		switch ($type)
 		{
 			case 'image/jpg': //This isn't a valid mime type so we should probably remove it
 			case "image/jpeg":
 			case "image/pjpeg":
 				return "jpg";
 				break;
 			case "image/png":
 				return "png";
 				break;
 			case "image/gif":
 				return "gif";
 				break;
 			case "image/bmp":
 				return "bmp";
 				break;
            case "video/mp4":
                return "mp4";
                break;
 			case "image/vnd.microsoft.icon":
 				return "ico";
 				break;
 		}
 		
 		$typeExtSplit = explode(".", $name);
 		$typeExt = end($typeExtSplit);
 		
 		return $typeExt;
 	
 	}
 	
    public function __construct($fileKey = "file")
    {
        $this->_fileKey = $fileKey;
        
        //URL based uploaded file (so its essentially a download, not an upload)
        if(strpos($fileKey, "http:") === 0 || strpos($fileKey, "https:") === 0)
        {
            $this->_isURL = true;
            $this->_extension = $this->getExtByMime(null, $fileKey);
            if($this->_extension == "jpg")
            {
                $fileName =  $this->downloadJpgFromURL($fileKey);
            }
            else
            {
                $data =  $this->downloadFileFromURL($fileKey);
                $this->_tempFile = $data;

            }

        }
        else
        {
            $this->_isURL = false;
            $tmp_name = is_array($_FILES[$fileKey]['tmp_name']) ? $_FILES[$fileKey]['tmp_name'][0] : $_FILES[$fileKey]['tmp_name'];
        
            if(is_uploaded_file($tmp_name))
            {
                if(!$this->upload($fileKey)) $this->error = self::ERROR_BAD_UPLOAD;
            }
            else
            {
            	$this->error = self::ERROR_BAD_UPLOAD;
            }
            
        }
        
       
    }
    
    protected function upload($fileKey)
    {
    
        $tmp_name = is_array($_FILES[$fileKey]['tmp_name']) ? $_FILES[$fileKey]['tmp_name'][0] : $_FILES[$fileKey]['tmp_name'];
        $name = is_array($_FILES[$fileKey]['name']) ? $_FILES[$fileKey]['name'][0] : $_FILES[$fileKey]['name'];
        if(!is_uploaded_file($tmp_name)) throw new \Exception("Invalid file upload");
    
        $error = is_array($_FILES[$fileKey]['error']) ? $_FILES[$fileKey]['error'][0] : $_FILES[$fileKey]['error'];
        if($error > 0) throw new \Exception("Unknown Upload Error");
    
        //Begin File Routing
        $mimeType = is_array($_FILES[$fileKey]['type']) ? $_FILES[$fileKey]['type'][0] : $_FILES[$fileKey]['type'];
        $size = is_array($_FILES[$fileKey]['size']) ? $_FILES[$fileKey]['size'][0] : $_FILES[$fileKey]['size'];
        	
        $this->_type = $type = self::getType($mimeType);
    	
        if(self::$MAX_FILE_SIZE && $size > self::$MAX_FILE_SIZE) throw new \Exception("File size exceeds " . round(self::$MAX_FILE_SIZE / 1048576, 1) . " MB");
        
        $typeExt = self::getExtByMime($mimeType, $name);
        $this->_extension = $typeExt;
        $this->_tempFile = $tmp_name;
        
        if($type == "file" && !$this->allowUnknownFileType)
        {
        	throw new \Exception("Unsupported File Type");
        }
      
    }


    protected function downloadFileFromURL($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $rawdata=curl_exec($ch);

        curl_close ($ch);

        return $rawdata;

    }

    protected function downloadJpgFromURL($url)
    {
       $rawdata = $this->downloadFileFromURL($url);
    	$this->_type = "image";
        // now you make an image out of it
    
        $type = "jpg";
    
        $im = imagecreatefromstring($rawdata);
    
        $imageWidth = imagesx($im);
        $imageHeight = imagesy($im);
    
        if($imageHeight > MAX_HEIGHT || $imageWidth > MAX_WIDTH)
        {
            $shrinkRatio = $imageWidth > $imageHeight ? MAX_WIDTH / $imageWidth : MAX_HEIGHT / $imageHeight;
            	
            $newWidth = $imageWidth * $shrinkRatio;
            $newHeight = $imageHeight * $shrinkRatio;
            	
            $im2 = imagecreatetruecolor($newWidth,$newHeight);
            	
            imagecopyresized($im2,$im,0,0,0,0,$newWidth,$newHeight,$imageWidth,$imageHeight);
            	
            	
            imagecopyresampled($im2,$im,0,0,0,0,$newWidth,$newHeight,$imageWidth,$imageHeight);
            imagedestroy($im);
            $im = $im2;
            	
        }
    	
        $this->_tempFile = $im;
    }
    
    public function saveAs($filePath)
    {

        $dirSplit = explode("/", $filePath);
 
        $fileName = array_pop($dirSplit);
        $dirOnly = implode("/", $dirSplit);

        if(!is_dir($dirOnly)) mkdir($dirOnly, 0777, true);

        if(strpos($fileName, ".") === false)
        {
        	$filePath .= ".$this->_extension";   
        }

        if($this->_isURL)
        {
            if($this->_extension == 'jpg')
            {
                imagejpeg($this->_tempFile, $filePath);
            }
            else
            {
                file_put_contents($filePath, $this->_tempFile);
            }

        }else
        {
            move_uploaded_file($this->_tempFile, $filePath);
        }	
        
        return $filePath;
    }
}
