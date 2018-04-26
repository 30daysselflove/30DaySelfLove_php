<?php

namespace model;

use model\User;

use model\MySQLActiveRecord;

use lib\exception\DatabaseException;
use lib\exception\UnknownException;
use lib\exception\ArgumentException;


//Image size and defaults

define ('DEFAULT_IMAGE_QUALITY', 80);				// Default image quality. 
define ('DEFAULT_ZC', 1);							// Default zoom/crop setting. 
define ('DEFAULT_F', '');							// Default image filters. 
define ('DEFAULT_S', 0);							// Default sharpen value. 
define ('DEFAULT_CC', 'ffffff');					// Default canvas colour. 
define ('DEFAULT_ALIGN', 'c');						// Default align
define ('MAX_WIDTH', 2048);							// Maximum image width
define ('MAX_HEIGHT', 2048);						// Maximum image height
define ('MEMORY_LIMIT', '30M');						// Set PHP memory limit

class media extends MySQLActiveRecord
{
										
	const MAX_IMAGE_SIZE = 3145728;
	const RELATIVE_BASE = "";
	const MEDIA_URL_BASE = MEDIA_URL;
	
	public $owner;
	
	public function __construct($id = null, $owner = null, $injectableDataObject = null)
	{
		parent::__construct($id, $injectableDataObject);
		$this->owner = $owner;
		
	}
	
	protected static function mapToObject($queryResult, $objectForm = true, $singleObjectAsArray = false)
	{
		$db = self::$database;
		
		$objectArray = array();
		while($row = $db->db_fetch_assoc($queryResult))
		{
			$dataObject = $row;
			//We dont want to feed in the media ID as part of the dataObject, as its actually an intrinsic property of an object
			unset($dataObject['mediaID']);
			$dataObject['url'] = self::buildURL($row['type'], $row['mediaPath']);
			if($objectForm)
			{	
				$objectArray[] = new media($row['mediaID'], $row['userID'], $dataObject);
			}
			else
			{
				$dataObject['id'] = $row['mediaID'];
				$objectArray[] = $dataObject;
			}
			
		}

		if(count($objectArray) == 1 && !$singleObjectAsArray) return $objectArray[0];
		return $objectArray;
	}
	
	public static function findByOwner(User $user,$objectForm = true)
	{
	
		$userID = $user->id;
		$protocolBase = !empty($_SERVER['https']) ? "https://" : "http://";
		
		$query = "	SELECT
						mediaID,
						userID,
						mediaPath,
						type,
						title,
						upload_date
					FROM media
					WHERE userID = $userID" ;
		
		$result = self::$database->db_query_error_check($query);
		
		return self::mapToObject($result,$objectForm, true);
	}
	
	public function __set($key, $value)
	{
		$this->$key = $value;
	}
	
	public static function upload(&$type)
	{
	
		$tmp_name = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][0] : $_FILES['file']['tmp_name'];
		if(!is_uploaded_file($tmp_name)) throw new ArgumentException("Invalid file upload"); 
		
		$error = is_array($_FILES['file']['error']) ? $_FILES['file']['error'][0] : $_FILES['file']['error'];
		if($error > 0) throw new ArgumentException("Unknown Upload Error"); 
		
		//Begin File Routing
		$mimeType = is_array($_FILES['file']['type']) ? $_FILES['file']['type'][0] : $_FILES['file']['type'];
		$size = is_array($_FILES['file']['size']) ? $_FILES['file']['size'][0] : $_FILES['file']['size'];
		 
		$type = self::getType($mimeType);
		
		switch ($type)
		{
			case "image":
				
				if($size > self::MAX_IMAGE_SIZE) throw new ArgumentException("File size exceeds " . self::MAX_IMAGE_SIZE / 1048576 * 1024 . "KB");  
				
				$uploadResult = self::moveupload($mimeType, $type);
				if($uploadResult) return $uploadResult;
				throw new UnknownException();
			break;
			case "unknown":
				throw new ArgumentException("Unsupported File Type"); 
				
			break;
		}
	}
	
	private static function moveupload($mimeType)
	{
		$tmp_name = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][0] : $_FILES['file']['tmp_name'];
		$name = is_array($_FILES['file']['name']) ? $_FILES['file']['name'][0] : $_FILES['file']['name'];
		
		
		$type = self::getType($mimeType);
		$typeExt = self::getExtByMime($mimeType);
		if($typeExt == false)
		{
			$typeExtSplit = explode(".", $name);
			$typeExt = strtolower(end($typeExtSplit));
		}

		$fileName = uniqid() . "." . $typeExt;
		$dir = MEDIA_BASE_PATH . "$type";
		$filePath = "$dir/$fileName";
		if(!is_dir($dir)) mkdir($dir);
	
		$upload = move_uploaded_file($tmp_name, $filePath);
		
		if(!$upload) 
		{
			throw new ArgumentException("Invalid file upload"); 
		}
		
		return $fileName;
	}
	
	protected static function downloadJpgFromURL($url, &$type)
	{
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$rawdata=curl_exec($ch);
		echo 'RDATA'.$rawdata;
		curl_close ($ch);
		
		// now you make an image out of it
		
		$type = "jpg";
		echo $url;

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
		
		$fileName = uniqid() . ".$type";
		$dir = MEDIA_BASE_PATH . "image";
		$filePath = "$dir/$fileName";
		if(!is_dir($dir)) mkdir($dir);
		
		imagejpeg($im, $filePath);
		
		return $fileName;
	}
	
	public function deleteRawFile($mediaPath, $type)
	{
		$filePath = MEDIA_BASE_PATH . "$type/$mediaPath";
		@unlink($filePath);
	}
	
	public function getFilePath()
	{
		$type = $this->type;
		$mediaPath = $this->mediaPath;
		return MEDIA_BASE_PATH . "$type/$mediaPath";
	}
	
	public function getPath()
	{
		$type = $this->type;
		return MEDIA_BASE_PATH . "$type/";
	}
	public static function buildURL($type, $mediaPath)
	{
		if(!$mediaPath) return null;
		$protocolBase = !empty($_SERVER['https']) ? "https://" : "http://";
		return $protocolBase . self::MEDIA_URL_BASE . self::RELATIVE_BASE . "/$type/$mediaPath";
	}
	
	public function getURL()
	{
		$type = $this->type;
		$mediaPath = $this->mediaPath;
		return self::buildURL($type, $mediaPath);
	}
	
	public static function create()
	{	
		
		$args = func_get_args();
		$userID = $args[0];
		$title = $args[1];
		$fileURL = isset($args[2]) ? $args[2] : null;
		
		$argsToSanitize = array(&$userID, &$title);
		
		if(self::sanitizeArguments($argsToSanitize, self::SANITIZE_STRING));
		
		
		$fileDataKeys = "";
		$fileDataValues = "";
		$dataArray = array();
		$fileName = null;
		$type = null;
		
		
		//Image files only
		if($fileURL)
		{
			$fileName = self::downloadJpgFromURL($fileURL, $type);
			$fileDataKeys = ", mediaPath, type";
			$fileDataValues = ", '$fileName', 'image'";
			
		}
		else
		{
	
			if(isset($_FILES['file']))
			{
				$tmp_name = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][0] : $_FILES['file']['tmp_name'];
				if(is_uploaded_file($tmp_name))
				{
					if(!$result = self::upload($type)) return false;
					$fileName = $result;
			
					$fileDataKeys = ", mediaPath, type";
					$fileDataValues = ", '$fileName', '$type'";
				}
					
			}
		}
		
		$time = time();
		$query = "	INSERT INTO media 
						(userID, title, upload_date $fileDataKeys)
					VALUES
						($userID, '$title', FROM_UNIXTIME($time) $fileDataValues)";
		
		$result = self::$database->db_query($query);
		
		if(!$result) 
		{
			if($fileDataKeys != "")$this->deleteRawFile($fileName);
			throw new DatabaseException(self::$database->db_error());
		}
		
		$dataArray["title"] = $title;
		$dataArray["mediaPath"] = $fileName;
		$dataArray["type"] = $type;
		$dataArray["url"] = self::buildURL($type, $fileName); 
		
		return new media(self::$database->last_insert_id(), $userID, $dataArray);
	}
	
	protected function getRow()
	{
		return $this->get();
	}
	
	public function get()
	{
		$id = $this->id;
	
		$query = "	SELECT 
						mediaID,
						userID,		 
						mediaPath, 
						type,
						title,
						upload_date
					FROM media
					WHERE mediaID = $id";
			
		$result = self::getSingleResult($query);

		
		return $result;
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
			case "application/octet-stream":
				return "image";
				break;
			default:
				return "unknown";
				break;
		}
	}
	
	/**
	 * 
	 * Get the file extension using the MIME type
	 * @param string $type
	 */
	protected static function getExtByMime($type)
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
		}
		return false;
		
	}
	
	public function set($keyValueArray, $disallowedFields = null)
	{
	
		$disallowedFields = array("mediaID", "restaurantID", "locationID");
		
		$userID = $this->owner;
		$id = $this->id;
		if(isset($keyValueArray['Upload'])) unset($keyValueArray['Upload']);
		$oldName = $this->name;
		if(isset($_FILES['file']))
		{
			$tmp_name = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][0] : $_FILES['file']['tmp_name'];
			if(is_uploaded_file($tmp_name))
			{
				$type = is_array($_FILES['file']['type']) ? $_FILES['file']['type'][0] : $_FILES['file']['type'];
				$type = $this->getType($type);
				if($type != $this->type && $this->type != "") 
				{
					throw new ArgumentException("You may not replace the existing media file with one of a different type.");
				}
				
				if(!$uploadResult = $this->upload()) return false;
				
				$this->name = $keyValueArray["name"] = $uploadResult;
				$newType = $keyValueArray["type"] = $this->type;
				if(!empty($oldName))
				{
					/*
					 * Destroy any old raw/linkage images and create new ones
					 */
					$this->deleteRawFile($oldName, $this->type); //Delete raw
					
				}
			}
			
		}
		
		$query = "	UPDATE media 
					SET " . $this->createUpdateSQLFromKeyValue($keyValueArray, $disallowedFields) . " 
					WHERE mediaID=$id AND userID=$userID";
		
		$result = self::$database->db_query($query);
		
		if(!$result) throw new DatabaseException(self::$database->db_error());
		else if(isset($_FILES['file'])) 
		{
			$result = $this->getURL();
		}
		$this->invalidateMe();
		$this->clearChangedKeys();
		return $result;
	}
	
	public function delete()
	{
		$id = $this->id;
		
		$this->deleteRawFile($this->mediaPath, $this->type); //Delete raw			
	
		$query = "	DELETE media FROM media
					WHERE mediaID = $id
					AND userID = $this->owner";
		$result = self::$database->db_query($query);
		if(!$result) throw new DatabaseException(self::$database->db_error());

		return $result;
	}
	
}