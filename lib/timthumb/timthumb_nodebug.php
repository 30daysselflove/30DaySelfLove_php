<?php

namespace core\timthumb;

/**
 * TimThumb by Ben Gillbanks and Mark Maunder
 * Based on work done by Tim McDaniels and Darren Hoyt
 * http://code.google.com/p/timthumb/
 * 
 * GNU General Public License, version 2
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Examples and documentation available on the project homepage
 * http://www.binarymoon.co.uk/projects/timthumb/
 * 
 * $Rev$
 */

/*
 * --- TimThumb CONFIGURATION ---
 * To edit the configs it is best to create a file called timthumb-config.php
 * and define variables you want to customize in there. It will automatically be
 * loaded by timthumb. This will save you having to re-edit these variables
 * everytime you download a new version
*/
define ('VERSION', '2.8.5');																		// Version of this script 
//Load a config file if it exists. Otherwise, use the values below
if( file_exists(dirname(__FILE__) . '/timthumb-config.php'))	require_once('timthumb-config.php');
define('MEDIA_PATH', '/media/image');
define('LOCAL_FILE_BASE_DIRECTORY', '/var/www/vhosts/blizzfull/htdocs/public');
if(! defined('DEBUG_ON') )					define ('DEBUG_ON', false);								// Enable debug logging to web server error log (STDERR)
if(! defined('DEBUG_LEVEL') )				define ('DEBUG_LEVEL', 1);								// Debug level 1 is less noisy and 3 is the most noisy
if(! defined('MEMORY_LIMIT') )				define ('MEMORY_LIMIT', '30M');							// Set PHP memory limit
if(! defined('BLOCK_EXTERNAL_LEECHERS') ) 	define ('BLOCK_EXTERNAL_LEECHERS', false);				// If the image or webshot is being loaded on an external site, display a red "No Hotlinking" gif.

//Image fetching and caching
if(! defined('ALLOW_EXTERNAL') )			define ('ALLOW_EXTERNAL', TRUE);						// Allow image fetching from external websites. Will check against ALLOWED_SITES if ALLOW_ALL_EXTERNAL_SITES is false
if(! defined('ALLOW_ALL_EXTERNAL_SITES') ) 	define ('ALLOW_ALL_EXTERNAL_SITES', false);				// Less secure. 
if(! defined('FILE_CACHE_ENABLED') ) 		define ('FILE_CACHE_ENABLED', TRUE);					// Should we store resized/modified images on disk to speed things up?
if(! defined('FILE_CACHE_TIME_BETWEEN_CLEANS'))	define ('FILE_CACHE_TIME_BETWEEN_CLEANS', 604800);	// How often the cache is cleaned 

if(! defined('FILE_CACHE_MAX_FILE_AGE') ) 	define ('FILE_CACHE_MAX_FILE_AGE', 604800);				// How old does a file have to be to be deleted from the cache
if(! defined('FILE_CACHE_SUFFIX') ) 		define ('FILE_CACHE_SUFFIX', '.timthumb.txt');			// What to put at the end of all files in the cache directory so we can identify them
if(! defined('FILE_CACHE_PREFIX') ) 		define ('FILE_CACHE_PREFIX', 'timthumb');				// What to put at the end of all files in the cache directory so we can identify them
if(! defined('FILE_CACHE_DIRECTORY') ) 		define ('FILE_CACHE_DIRECTORY', './cache');				// Directory where images are cached. Left blank it will use the system temporary directory (which is better for security)
if(! defined('MAX_FILE_SIZE') )				define ('MAX_FILE_SIZE', 10485760);						// 10 Megs is 10485760. This is the max internal or external file size that we'll process.  
if(! defined('CURL_TIMEOUT') )				define ('CURL_TIMEOUT', 20);							// Timeout duration for Curl. This only applies if you have Curl installed and aren't using PHP's default URL fetching mechanism.
if(! defined('WAIT_BETWEEN_FETCH_ERRORS') )	define ('WAIT_BETWEEN_FETCH_ERRORS', 3600);				//Time to wait between errors fetching remote file

//Browser caching
if(! defined('BROWSER_CACHE_MAX_AGE') ) 	define ('BROWSER_CACHE_MAX_AGE', 864000);				// Time to cache in the browser
if(! defined('BROWSER_CACHE_DISABLE') ) 	define ('BROWSER_CACHE_DISABLE', false);				// Use for testing if you want to disable all browser caching

//Image size and defaults
if(! defined('MAX_WIDTH') ) 			define ('MAX_WIDTH', 1500);									// Maximum image width
if(! defined('MAX_HEIGHT') ) 			define ('MAX_HEIGHT', 1500);								// Maximum image height
if(! defined('NOT_FOUND_IMAGE') )		define ('NOT_FOUND_IMAGE', '');								// Image to serve if any 404 occurs 
if(! defined('ERROR_IMAGE') )			define ('ERROR_IMAGE', '');									// Image to serve if an error occurs instead of showing error message 
if(! defined('DEFAULT_Q') )				define ('DEFAULT_Q', 90);									// Default image quality. Allows overrid in timthumb-config.php
if(! defined('DEFAULT_ZC') )			define ('DEFAULT_ZC', 1);									// Default zoom/crop setting. Allows overrid in timthumb-config.php
if(! defined('DEFAULT_F') )				define ('DEFAULT_F', '');									// Default image filters. Allows overrid in timthumb-config.php
if(! defined('DEFAULT_S') )				define ('DEFAULT_S', 0);									// Default sharpen value. Allows overrid in timthumb-config.php
if(! defined('DEFAULT_CC') )			define ('DEFAULT_CC', 'ffffff');							// Default canvas colour. Allows overrid in timthumb-config.php


//Image compression is enabled if either of these point to valid paths

//These are now disabled by default because the file sizes of PNGs (and GIFs) are much smaller than we used to generate. 
//They only work for PNGs. GIFs and JPEGs are not affected.
if(! defined('OPTIPNG_ENABLED') ) 		define ('OPTIPNG_ENABLED', false);  
if(! defined('OPTIPNG_PATH') ) 			define ('OPTIPNG_PATH', '/usr/bin/optipng'); //This will run first because it gives better compression than pngcrush. 
if(! defined('PNGCRUSH_ENABLED') ) 		define ('PNGCRUSH_ENABLED', false); 
if(! defined('PNGCRUSH_PATH') ) 		define ('PNGCRUSH_PATH', '/usr/bin/pngcrush'); //This will only run if OPTIPNG_PATH is not set or is not valid


// If ALLOW_EXTERNAL is true and ALLOW_ALL_EXTERNAL_SITES is false, then external images will only be fetched from these domains and their subdomains. 
if(! isset($ALLOWED_SITES)){
	$ALLOWED_SITES = array (
		'flickr.com',
		'staticflickr.com',
		'picasa.com',
		'img.youtube.com',
		'upload.wikimedia.org',
		'photobucket.com',
		'imgur.com',
		'imageshack.us',
		'tinypic.com',
	);
}
// -------------------------------------------------------------
// -------------- STOP EDITING CONFIGURATION HERE --------------
// -------------------------------------------------------------


class timthumb {
	protected $src = "";
	protected $is404 = false;
	protected $docRoot = "";
	protected $lastURLError = false;
	protected $localImage = "";
	protected $localImageMTime = 0;
	protected $url = false;
	protected $myHost = "";
	protected $isURL = false;
	protected $cachefile = '';
	protected $errors = array();
	protected $toDeletes = array();
	protected $cacheDirectory = '';
	protected $startTime = 0;
	protected $lastBenchTime = 0;
	protected $cropTop = false;
	protected $salt = "";
	protected $fileCacheVersion = 1; //Generally if timthumb.php is modifed (upgraded) then the salt changes and all cache files are recreated. This is a backup mechanism to force regen.
	protected $filePrependSecurityBlock = "<?php die('Execution denied!'); //"; //Designed to have three letter mime type, space, question mark and greater than symbol appended. 6 bytes total.
	protected static $curlDataWritten = 0;
	protected static $curlFH = false;
	public static function start($src){
		$tim = new timthumb($src);
		$tim->handleErrors();
		$tim->securityChecks();
		if($tim->tryBrowserCache()){
			exit(0);
		}
		$tim->handleErrors();
		if(FILE_CACHE_ENABLED && $tim->tryServerCache()){
			exit(0);
		}
		$tim->handleErrors();
		$tim->run();
		$tim->handleErrors();
		exit(0);
	}
	public function __construct($src){
		global $ALLOWED_SITES;
		
		$this->startTime = microtime(true);
		date_default_timezone_set('UTC');
		
		$this->calcDocRoot();
		//On windows systems I'm assuming fileinode returns an empty string or a number that doesn't change. Check this.
		$this->salt = @filemtime(__FILE__) . '-' . @fileinode(__FILE__);
		
		if(FILE_CACHE_DIRECTORY){
			if(! is_dir(FILE_CACHE_DIRECTORY)){
				@mkdir(FILE_CACHE_DIRECTORY);
				if(! is_dir(FILE_CACHE_DIRECTORY)){
					$this->error("Could not create the file cache directory.");
					return false;
				}
			}
			$this->cacheDirectory = FILE_CACHE_DIRECTORY;
			if (!touch($this->cacheDirectory . '/index.html')) {
				$this->error("Could note create the index.html file.");
			}
		} else {
			$this->cacheDirectory = sys_get_temp_dir();
		}
		//Clean the cache before we do anything because we don't want the first visitor after FILE_CACHE_TIME_BETWEEN_CLEANS expires to get a stale image. 
		$this->cleanCache();
		
		$this->myHost = preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);
		$this->src = $src;
		$this->url = parse_url($this->src);
		if(strlen($this->src) <= 3){
			$this->error("No image specified");
			return false;
		}
		if(BLOCK_EXTERNAL_LEECHERS && array_key_exists('HTTP_REFERER', $_SERVER) && (! preg_match('/^https?:\/\/(?:www\.)?' . $this->myHost . '(?:$|\/)/i', $_SERVER['HTTP_REFERER']))){
			// base64 encoded red image that says 'no hotlinkers'
			// nothing to worry about! :)
			$imgData = base64_decode("R0lGODlhUAAMAIAAAP8AAP///yH5BAAHAP8ALAAAAABQAAwAAAJpjI+py+0Po5y0OgAMjjv01YUZ\nOGplhWXfNa6JCLnWkXplrcBmW+spbwvaVr/cDyg7IoFC2KbYVC2NQ5MQ4ZNao9Ynzjl9ScNYpneb\nDULB3RP6JuPuaGfuuV4fumf8PuvqFyhYtjdoeFgAADs=");
			header('Content-Type: image/gif');
			header('Content-Length: ' . sizeof($imgData));
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			header("Pragma: no-cache");
			header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));
			echo $imgData;
			return false;
			exit(0);
		}
		if(preg_match('/https?:\/\/(?:www\.)?' . $this->myHost . '(?:$|\/)/i', $this->src)){
			$this->src = preg_replace('/https?:\/\/(?:www\.)?' . $this->myHost . '/i', '', $this->src);
		}
		if(preg_match('/^https?:\/\/[^\/]+/i', $this->src)){
			
			$this->isURL = true;
		} else {
			
		}
		if($this->isURL && (! ALLOW_EXTERNAL)){
			$this->error("You are not allowed to fetch images from an external website.");
			return false;
		}
		if($this->isURL){
			if(ALLOW_ALL_EXTERNAL_SITES){
				
			} else {
				
				$allowed = false;
				foreach($ALLOWED_SITES as $site){
					if ((strtolower(substr($this->url['host'],-strlen($site)-1)) === strtolower(".$site")) || (strtolower($this->url['host'])===strtolower($site))) {
						
						$allowed = true;
					}
				}
				if(! $allowed){
					return $this->error("You may not fetch images from that site. To enable this site in timthumb, you can either add it to \$ALLOWED_SITES and set ALLOW_EXTERNAL=true. Or you can set ALLOW_ALL_EXTERNAL_SITES=true, depending on your security needs.");
				}
			}
		}

		$cachePrefix = ($this->isURL ? '_ext_' : '_int_');
		if($this->isURL){
			$arr = explode('&', $_SERVER ['QUERY_STRING']);
			asort($arr);
			$this->cachefile = $this->cacheDirectory . '/' . FILE_CACHE_PREFIX . $cachePrefix . md5($this->salt . implode('', $arr) . $this->fileCacheVersion) . FILE_CACHE_SUFFIX;
		} else {
			$this->localImage = $this->getLocalImagePath($this->src);
			if(! $this->localImage){
				
				$this->error("Could not find the internal image you specified.");
				$this->set404();
				return false;
			}
			
			$this->localImageMTime = @filemtime($this->localImage);
			//We include the mtime of the local file in case in changes on disk.
			$this->cachefile = $this->cacheDirectory . '/' . FILE_CACHE_PREFIX . $cachePrefix . md5($this->salt . $this->localImageMTime . $_SERVER ['QUERY_STRING'] . $this->fileCacheVersion) . FILE_CACHE_SUFFIX;
		}
		

		return true;
	}
	public function __destruct(){
		foreach($this->toDeletes as $del){
			
			@unlink($del);
		}
	}
	public function run(){
		if($this->isURL){
			if(! ALLOW_EXTERNAL){
				
				$this->error("You are not allowed to fetch images from an external website.");
				return false;
			}
			
		
			
			$this->serveExternalImage();

			
		} else {
			
			$this->serveInternalImage();
		}
		return true;
	}
	protected function handleErrors(){
		if($this->haveErrors()){ 
			if(NOT_FOUND_IMAGE && $this->is404()){
				if($this->serveImg(NOT_FOUND_IMAGE)){
					exit(0);
				} else {
					$this->error("Additionally, the 404 image that is configured could not be found or there was an error serving it.");
				}
			}
			if(ERROR_IMAGE){
				if($this->serveImg(ERROR_IMAGE)){
					exit(0);
				} else {
					$this->error("Additionally, the error image that is configured could not be found or there was an error serving it.");
				}
			}
				
			$this->serveErrors(); 
			exit(0); 
		}
		return false;
	}
	protected function tryBrowserCache(){
		if(BROWSER_CACHE_DISABLE){  return false; }
		if(!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ){
			
			$mtime = false;
			//We've already checked if the real file exists in the constructor
			if(! is_file($this->cachefile)){
				//If we don't have something cached, regenerate the cached image.
				return false;
			}
			if($this->localImageMTime){
				$mtime = $this->localImageMTime;
				
			} else if(is_file($this->cachefile)){ //If it's not a local request then use the mtime of the cached file to determine the 304
				$mtime = @filemtime($this->cachefile);
				
			}
			if(! $mtime){ return false; }

			$iftime = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
			
			if($iftime < 1){
				
				return false;
			}
			if($iftime < $mtime){ //Real file or cache file has been modified since last request, so force refetch.
				
				return false;
			} else { //Otherwise serve a 304
				
				header ($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
				
				return true;
			}
		}
		return false;
	}
	protected function tryServerCache(){
		
		if(file_exists($this->cachefile)){
			
			if($this->isURL){
				
				if(filesize($this->cachefile) < 1){
					
					//Fetching error occured previously
					if(time() - @filemtime($this->cachefile) > WAIT_BETWEEN_FETCH_ERRORS){
						
						@unlink($this->cachefile);
						return false; //to indicate we didn't serve from cache and app should try and load
					} else {
						
						$this->set404();
						$this->error("An error occured fetching image.");
						return false; 
					}
				}
			} else {
				
			}
			if($this->serveCacheFile()){
				
				return true;
			} else {
				
				//Image serving failed. We can't retry at this point, but lets remove it from cache so the next request recreates it
				@unlink($this->cachefile);
				return true;
			}
		}
	}
	protected function error($err){
		
		$this->errors[] = $err;
		return false;

	}
	protected function haveErrors(){
		if(sizeof($this->errors) > 0){
			return true;
		}
		return false;
	}
	protected function serveErrors(){
		$html = '<ul>';
		foreach($this->errors as $err){
			$html .= '<li>' . htmlentities($err) . '</li>';
		}
		$html .= '</ul>';
		header ($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
		echo '<h1>A TimThumb error has occured</h1>The following error(s) occured:<br />' . $html . '<br />';
		echo '<br />Query String : ' . htmlentities ($_SERVER['QUERY_STRING']);
		echo '<br />TimThumb version : ' . VERSION . '</pre>';
	}
	protected function serveInternalImage(){
		
		if(! $this->localImage){
			$this->sanityFail("localImage not set after verifying it earlier in the code.");
			return false;
		}
		$fileSize = filesize($this->localImage);
		if($fileSize > MAX_FILE_SIZE){
			$this->error("The file you specified is greater than the maximum allowed file size.");
			return false;
		}
		if($fileSize <= 0){
			$this->error("The file you specified is <= 0 bytes.");
			return false;
		}
		
		if($this->processImageAndWriteToCache($this->localImage)){
			$this->serveCacheFile();
			return true;
		} else { 
			return false;
		}
	}
	protected function cleanCache(){
		if (FILE_CACHE_TIME_BETWEEN_CLEANS < 0) {
			return;
		}
		
		$lastCleanFile = $this->cacheDirectory . '/timthumb_cacheLastCleanTime.touch';
		
		//If this is a new timthumb installation we need to create the file
		if(! is_file($lastCleanFile)){
			
			if (!touch($lastCleanFile)) {
				$this->error("Could note create cache clean timestamp file.");
			}
			return;
		}
		if(@filemtime($lastCleanFile) < (time() - FILE_CACHE_TIME_BETWEEN_CLEANS) ){ //Cache was last cleaned more than 1 day ago
			
			// Very slight race condition here, but worst case we'll have 2 or 3 servers cleaning the cache simultaneously once a day.
			if (!touch($lastCleanFile)) {
				$this->error("Could note create cache clean timestamp file.");
			}
			$files = glob($this->cacheDirectory . '/*' . FILE_CACHE_SUFFIX);
			$timeAgo = time() - FILE_CACHE_MAX_FILE_AGE;
			foreach($files as $file){
				if(@filemtime($file) < $timeAgo){
					
					@unlink($file);
				}
			}
			return true;
		} else {
			
		}
		return false;
	}
	protected function processImageAndWriteToCache($localImage){
		$sData = getimagesize($localImage);
		$origType = $sData[2];
		$mimeType = $sData['mime'];

		
		if(! preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
			return $this->error("The image being resized is not a valid gif, jpg or png.");
		}

		if (!function_exists ('imagecreatetruecolor')) {
		    return $this->error('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library');
		}

		if (function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			$imageFilters = array (
				1 => array (IMG_FILTER_NEGATE, 0),
				2 => array (IMG_FILTER_GRAYSCALE, 0),
				3 => array (IMG_FILTER_BRIGHTNESS, 1),
				4 => array (IMG_FILTER_CONTRAST, 1),
				5 => array (IMG_FILTER_COLORIZE, 4),
				6 => array (IMG_FILTER_EDGEDETECT, 0),
				7 => array (IMG_FILTER_EMBOSS, 0),
				8 => array (IMG_FILTER_GAUSSIAN_BLUR, 0),
				9 => array (IMG_FILTER_SELECTIVE_BLUR, 0),
				10 => array (IMG_FILTER_MEAN_REMOVAL, 0),
				11 => array (IMG_FILTER_SMOOTH, 0),
			);
		}

		// get standard input properties		
		$new_width =  (int) abs ($this->param('w', 0));
		$new_height = (int) abs ($this->param('h', 0));
		$zoom_crop = (int) $this->param('zc', DEFAULT_ZC);
		$quality = (int) abs ($this->param('q', DEFAULT_Q));
		$align = $this->cropTop ? 't' : $this->param('a', 'c');
		$filters = $this->param('f', DEFAULT_F);
		$sharpen = (bool) $this->param('s', DEFAULT_S);
		$canvas_color = $this->param('cc', DEFAULT_CC);

		// set default width and height if neither are set already
		if ($new_width == 0 && $new_height == 0) {
		    $new_width = 100;
		    $new_height = 100;
		}

		// ensure size limits can not be abused
		$new_width = min ($new_width, MAX_WIDTH);
		$new_height = min ($new_height, MAX_HEIGHT);

		// set memory limit to be able to have enough space to resize larger images
		$this->setMemoryLimit();

		// open the existing image
		$image = $this->openImage ($mimeType, $localImage);
		if ($image === false) {
			return $this->error('Unable to open image.');
		}

		// Get original width and height
		$width = imagesx ($image);
		$height = imagesy ($image);
		$origin_x = 0;
		$origin_y = 0;

		// generate new w/h if not provided
		if ($new_width && !$new_height) {
			$new_height = floor ($height * ($new_width / $width));
		} else if ($new_height && !$new_width) {
			$new_width = floor ($width * ($new_height / $height));
		}

		// scale down and add borders
		if ($zoom_crop == 3) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {
				$new_width = $width * ($new_height / $height);
			} else {
				$new_height = $final_height;
			}

		}

		// create a new true color image
		$canvas = imagecreatetruecolor ($new_width, $new_height);
		imagealphablending ($canvas, false);

		if (strlen ($canvas_color) < 6) {
			$canvas_color = 'ffffff';
		}

		$canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
		$canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
		$canvas_color_B = hexdec (substr ($canvas_color, 2, 2));

		// Create a new transparent color for image
		$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);

		// Completely fill the background of the new image with allocated color.
		imagefill ($canvas, 0, 0, $color);

		// scale down and add borders
		if ($zoom_crop == 2) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {

				$origin_x = $new_width / 2;
				$new_width = $width * ($new_height / $height);
				$origin_x = round ($origin_x - ($new_width / 2));

			} else {

				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));

			}

		}

		// Restore transparency blending
		imagesavealpha ($canvas, true);

		if ($zoom_crop > 0) {

			$src_x = $src_y = 0;
			$src_w = $width;
			$src_h = $height;

			$cmp_x = $width / $new_width;
			$cmp_y = $height / $new_height;

			// calculate x or y coordinate and width or height of source
			if ($cmp_x > $cmp_y) {

				$src_w = round ($width / $cmp_x * $cmp_y);
				$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

			} else if ($cmp_y > $cmp_x) {

				$src_h = round ($height / $cmp_y * $cmp_x);
				$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

			}

			// positional cropping!
			if ($align) {
				if (strpos ($align, 't') !== false) {
					$src_y = 0;
				}
				if (strpos ($align, 'b') !== false) {
					$src_y = $height - $src_h;
				}
				if (strpos ($align, 'l') !== false) {
					$src_x = 0;
				}
				if (strpos ($align, 'r') !== false) {
					$src_x = $width - $src_w;
				}
			}

			imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

		} else {

			// copy and resize part of an image with resampling
			imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		}

		if ($filters != '' && function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			// apply filters to image
			$filterList = explode ('|', $filters);
			foreach ($filterList as $fl) {

				$filterSettings = explode (',', $fl);
				if (isset ($imageFilters[$filterSettings[0]])) {

					for ($i = 0; $i < 4; $i ++) {
						if (!isset ($filterSettings[$i])) {
							$filterSettings[$i] = null;
						} else {
							$filterSettings[$i] = (int) $filterSettings[$i];
						}
					}

					switch ($imageFilters[$filterSettings[0]][1]) {

						case 1:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1]);
							break;

						case 2:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2]);
							break;

						case 3:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3]);
							break;

						case 4:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3], $filterSettings[4]);
							break;

						default:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0]);
							break;

					}
				}
			}
		}

		// sharpen image
		if ($sharpen && function_exists ('imageconvolution')) {

			$sharpenMatrix = array (
					array (-1,-1,-1),
					array (-1,16,-1),
					array (-1,-1,-1),
					);

			$divisor = 8;
			$offset = 0;

			imageconvolution ($canvas, $sharpenMatrix, $divisor, $offset);

		}
		//Straight from Wordpress core code. Reduces filesize by up to 70% for PNG's
		if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
			imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
		}

		$imgType = "";
		$tempfile = tempnam($this->cacheDirectory, 'timthumb_tmpimg_');
		if(preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){ 
			$imgType = 'jpg';
			imagejpeg($canvas, $tempfile, $quality); 
		} else if(preg_match('/^image\/png$/i', $mimeType)){ 
			$imgType = 'png';
			imagepng($canvas, $tempfile, floor($quality * 0.09));
		} else if(preg_match('/^image\/gif$/i', $mimeType)){
			$imgType = 'gif';
			imagegif($canvas, $tempfile);
		} else {
			return $this->sanityFail("Could not match mime type after verifying it previously.");
		}

		if($imgType == 'png' && OPTIPNG_ENABLED && OPTIPNG_PATH && @is_file(OPTIPNG_PATH)){
			$exec = OPTIPNG_PATH;
			
			$presize = filesize($tempfile);
			$out = `$exec -o1 $tempfile`; //you can use up to -o7 but it really slows things down
			clearstatcache();
			$aftersize = filesize($tempfile);
			$sizeDrop = $presize - $aftersize;
			if($sizeDrop > 0){
				
			} else if($sizeDrop < 0){
				
			} else {
				
			}
		} else if($imgType == 'png' && PNGCRUSH_ENABLED && PNGCRUSH_PATH && @is_file(PNGCRUSH_PATH)){
			$exec = PNGCRUSH_PATH;
			$tempfile2 = tempnam($this->cacheDirectory, 'timthumb_tmpimg_');
			
			$out = `$exec $tempfile $tempfile2`;
			$todel = "";
			if(is_file($tempfile2)){
				$sizeDrop = filesize($tempfile) - filesize($tempfile2);
				if($sizeDrop > 0){
					
					$todel = $tempfile;
					$tempfile = $tempfile2;
				} else {
					
					$todel = $tempfile2;
				}
			} else {
				
				$todel = $tempfile2;
			}
			@unlink($todel);
		}

		
		$tempfile4 = tempnam($this->cacheDirectory, 'timthumb_tmpimg_');
		$context = stream_context_create ();
		$fp = fopen($tempfile,'r',0,$context);
		file_put_contents($tempfile4, $this->filePrependSecurityBlock . $imgType . ' ?' . '>'); //6 extra bytes, first 3 being image type 
		file_put_contents($tempfile4, $fp, FILE_APPEND);
		fclose($fp);
		@unlink($tempfile);
		
		$lockFile = $this->cachefile . '.lock';
		$fh = fopen($lockFile, 'w');
		if(! $fh){
			return $this->error("Could not open the lockfile for writing an image.");
		}
		if(flock($fh, LOCK_EX)){
			@unlink($this->cachefile); //rename generally overwrites, but doing this in case of platform specific quirks. File might not exist yet.
			rename($tempfile4, $this->cachefile);
			flock($fh, LOCK_UN);
			fclose($fh);
			@unlink($lockFile);
		} else {
			fclose($fh);
			@unlink($lockFile);
			@unlink($tempfile4);
			return $this->error("Could not get a lock for writing.");
		}
		
		imagedestroy($canvas);
		imagedestroy($image);
		return true;
	}
	protected function calcDocRoot(){
		$docRoot = @$_SERVER['DOCUMENT_ROOT'];
		if (defined('LOCAL_FILE_BASE_DIRECTORY')) {
			$docRoot = LOCAL_FILE_BASE_DIRECTORY;   
		}
		if(!isset($docRoot)){ 
			
			if(isset($_SERVER['SCRIPT_FILENAME'])){
				$docRoot = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
				
			} 
		}
		if(!isset($docRoot)){ 
			
			if(isset($_SERVER['PATH_TRANSLATED'])){
				$docRoot = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
				
			} 
		}
		if($docRoot && $_SERVER['DOCUMENT_ROOT'] != '/'){ $docRoot = preg_replace('/\/$/', '', $docRoot); }
		
		$this->docRoot = $docRoot . MEDIA_PATH;

	}
	protected function getLocalImagePath($src){
		$src = preg_replace('/^\//', '', $src); //strip off the leading '/'
		if(! $this->docRoot){
			
			//We don't support serving images outside the current dir if we don't have a doc root for security reasons.
			$file = preg_replace('/^.*?([^\/\\\\]+)$/', '$1', $src); //strip off any path info and just leave the filename.
			if(is_file($file)){
				return realpath($file);
			}
			return $this->error("Could not find your website document root and the file specified doesn't exist in timthumbs directory. We don't support serving files outside timthumb's directory without a document root for security reasons.");
		} //Do not go past this point without docRoot set

		//Try src under docRoot
		if(file_exists ($this->docRoot . '/' . $src)) {
			
			$real = realpath($this->docRoot . '/' . $src);
			if(stripos($real, $this->docRoot) == 0){
				return $real;
			} else {
				
				//allow search to continue
			}
		}
		//Check absolute paths and then verify the real path is under doc root
		$absolute = realpath('/' . $src);
		if($absolute){ //realpath does file_exists check, so can probably skip the exists check here
			
			if(! $this->docRoot){ $this->sanityFail("docRoot not set when checking absolute path."); }
			if(stripos($absolute, $this->docRoot) == 0){
				return $absolute;
			} else {
				
				//and continue search
			}
		}
		
		$base = $this->docRoot;
		
		// account for Windows directory structure
		//if (strstr($_SERVER['SCRIPT_FILENAME'],':')) {
			//$sub_directories = explode('\\', str_replace($this->docRoot, '', $_SERVER['SCRIPT_FILENAME']));
		//} else {
			$sub_directories = explode('/', str_replace($this->docRoot, '', $_SERVER['SCRIPT_FILENAME']));
		//}
		
		foreach ($sub_directories as $sub){
			$base .= $sub . '/';
			
			if(file_exists($base . $src)){
				
				$real = realpath($base . $src);
				if(stripos($real, $this->docRoot) == 0){ 
					return $real;
				} else {
					
					//And continue search
				}
			}
		}
		return false;
	}
	protected function toDelete($name){
		
		$this->toDeletes[] = $name;
	}
	
	protected function serveExternalImage(){
		if(! preg_match('/^https?:\/\/[a-zA-Z0-9\-\.]+/i', $this->src)){
			$this->error("Invalid URL supplied.");
			return false;
		}
		$tempfile = tempnam($this->cacheDirectory, 'timthumb');
		
		$this->toDelete($tempfile);
		#fetch file here
		if(! $this->getURL($this->src, $tempfile)){
			@unlink($this->cachefile);
			touch($this->cachefile);
			
			$this->error("Error reading the URL you specified from remote host." . $this->lastURLError);
			return false;
		}

		$mimeType = $this->getMimeType($tempfile);
		if(! preg_match("/^image\/(?:jpg|jpeg|gif|png)$/i", $mimeType)){
			
			@unlink($this->cachefile);
			touch($this->cachefile);
			$this->error("The remote file is not a valid image.");
			return false;
		}
		if($this->processImageAndWriteToCache($tempfile)){
			
			return $this->serveCacheFile();
		} else {
			return false;
		}
	}
	public static function curlWrite($h, $d){
		fwrite(self::$curlFH, $d);
		self::$curlDataWritten += strlen($d);
		if(self::$curlDataWritten > MAX_FILE_SIZE){
			return 0;
		} else {
			return strlen($d);
		}
	}
	protected function serveCacheFile(){
		
		if(! is_file($this->cachefile)){
			$this->error("serveCacheFile called in timthumb but we couldn't find the cached file.");
			return false;
		}
		$fp = fopen($this->cachefile, 'rb');
		if(! $fp){ return $this->error("Could not open cachefile."); }
		fseek($fp, strlen($this->filePrependSecurityBlock), SEEK_SET);
		$imgType = fread($fp, 3);
		fseek($fp, 3, SEEK_CUR);
		if(ftell($fp) != strlen($this->filePrependSecurityBlock) + 6){
			@unlink($this->cachefile);
			return $this->error("The cached image file seems to be corrupt.");
		}
		$imageDataSize = filesize($this->cachefile) - (strlen($this->filePrependSecurityBlock) + 6);
		$this->sendImageHeaders($imgType, $imageDataSize);
		$bytesSent = @fpassthru($fp);
		fclose($fp);
		if($bytesSent > 0){
			return true;
		}
		$content = file_get_contents ($this->cachefile);
		if ($content != FALSE) {
			$content = substr($content, strlen($this->filePrependSecurityBlock) + 6);
			echo $content;
			
			return true;
		} else {
			$this->error("Cache file could not be loaded.");
			return false;
		}
	}
	protected function sendImageHeaders($mimeType, $dataSize){
		if(! preg_match('/^image\//i', $mimeType)){
			$mimeType = 'image/' . $mimeType;
		}
		if(strtolower($mimeType) == 'image/jpg'){
			$mimeType = 'image/jpeg';
		}
		$gmdate_expires = gmdate ('D, d M Y H:i:s', strtotime ('now +10 days')) . ' GMT';
		$gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';
		// send content headers then display image
		header ('Content-Type: ' . $mimeType);
		header ('Accept-Ranges: none'); //Changed this because we don't accept range requests
		header ('Last-Modified: ' . $gmdate_modified);
		header ('Content-Length: ' . $dataSize);
		if(BROWSER_CACHE_DISABLE){
			
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			header("Pragma: no-cache");
			header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));
		} else {
			
			header('Cache-Control: max-age=' . BROWSER_CACHE_MAX_AGE . ', must-revalidate');
			header('Expires: ' . $gmdate_expires);
		}
		return true;
	}
	protected function securityChecks(){
	}
	protected function param($property, $default = ''){
		if (isset ($_GET[$property])) {
			return $_GET[$property];
		} else {
			return $default;
		}
	}
	protected function openImage($mimeType, $src){
		switch ($mimeType) {
			case 'image/jpg': //This isn't a valid mime type so we should probably remove it
			case 'image/jpeg':
				$image = imagecreatefromjpeg ($src);
				break;

			case 'image/png':
				$image = imagecreatefrompng ($src);
				break;

			case 'image/gif':
				$image = imagecreatefromgif ($src);
				break;
		}

		return $image;
	}
	protected function getIP(){
		$rem = @$_SERVER["REMOTE_ADDR"];
		$ff = @$_SERVER["HTTP_X_FORWARDED_FOR"];
		$ci = @$_SERVER["HTTP_CLIENT_IP"];
		if(preg_match('/^(?:192\.168|172\.16|10\.|127\.)/', $rem)){ 
			if($ff){ return $ff; }
			if($ci){ return $ci; }
			return $rem;
		} else {
			if($rem){ return $rem; }
			if($ff){ return $ff; }
			if($ci){ return $ci; }
			return "UNKNOWN";
		}
	}
	protected function debug($level, $msg){
		if(DEBUG_ON && $level <= DEBUG_LEVEL){
			$execTime = sprintf('%.6f', microtime(true) - $this->startTime);
			$tick = sprintf('%.6f', 0);
			if($this->lastBenchTime > 0){
				$tick = sprintf('%.6f', microtime(true) - $this->lastBenchTime);
			}
			$this->lastBenchTime = microtime(true);
			error_log("TimThumb Debug line " . __LINE__ . " [$execTime : $tick]: $msg");
		}
	}
	protected function sanityFail($msg){
		return $this->error("There is a problem in the timthumb code. Message: Please report this error at <a href='http://code.google.com/p/timthumb/issues/list'>timthumb's bug tracking page</a>: $msg");
	}
	protected function getMimeType($file){
		$info = getimagesize($file);
		if(is_array($info) && $info['mime']){
			return $info['mime'];
		}
		return '';
	}
	protected function setMemoryLimit(){
		$inimem = ini_get('memory_limit');
		$inibytes = timthumb::returnBytes($inimem);
		$ourbytes = timthumb::returnBytes(MEMORY_LIMIT);
		if($inibytes < $ourbytes){
			ini_set ('memory_limit', MEMORY_LIMIT);
			
		} else {
			
		}
	}
	protected static function returnBytes($size_str){
		switch (substr ($size_str, -1))
		{
			case 'M': case 'm': return (int)$size_str * 1048576;
			case 'K': case 'k': return (int)$size_str * 1024;
			case 'G': case 'g': return (int)$size_str * 1073741824;
			default: return $size_str;
		}
	}
	protected function getURL($url, $tempfile){
		$this->lastURLError = false;
		$url = preg_replace('/ /', '%20', $url);
		if(function_exists('curl_init')){
			
			self::$curlFH = fopen($tempfile, 'w');
			if(! self::$curlFH){
				$this->error("Could not open $tempfile for writing.");
				return false;
			}
			self::$curlDataWritten = 0;
			
			$curl = curl_init($url);
			curl_setopt ($curl, CURLOPT_TIMEOUT, CURL_TIMEOUT);
			curl_setopt ($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.122 Safari/534.30");
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt ($curl, CURLOPT_HEADER, 0);
			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt ($curl, CURLOPT_WRITEFUNCTION, 'timthumb::curlWrite');
			@curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt ($curl, CURLOPT_MAXREDIRS, 10);
			
			$curlResult = curl_exec($curl);
			fclose(self::$curlFH);
			$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if($httpStatus == 404){
				$this->set404();
			}
			if($curlResult){
				curl_close($curl);
				return true;
			} else {
				$this->lastURLError = curl_error($curl);
				curl_close($curl);
				return false;
			}
		} else {
			$img = @file_get_contents ($url);
			if($img === false){
				$err = error_get_last();
				if(is_array($err) && $err['message']){
					$this->lastURLError = $err['message'];
				} else {
					$this->lastURLError = $err;
				}
				if(preg_match('/404/', $this->lastURLError)){
					$this->set404();
				}

				return false;
			}
			if(! file_put_contents($tempfile, $img)){
				$this->error("Could not write to $tempfile.");
				return false;
			}
			return true;
		}

	}
	protected function serveImg($file){
		$s = getimagesize($file);
		if(! ($s && $s['mime'])){
			return false;
		}
		header ('Content-Type: ' . $s['mime']);
		header ('Content-Length: ' . filesize($file) );
		header ('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header ("Pragma: no-cache");
		$bytes = @readfile($file);
		if($bytes > 0){
			return true;
		}
		$content = @file_get_contents ($file);
		if ($content != FALSE){
			echo $content;
			return true;
		}
		return false;

	}
	protected function set404(){
		$this->is404 = true;
	}
	protected function is404(){
		return $this->is404;
	}
}