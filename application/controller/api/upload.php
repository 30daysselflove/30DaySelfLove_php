<?php
/**
 * This file is part of the PhpTus package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace controller\api;

use controller\controller;
use controller\SessionController;
use lib\exception\ArgumentException;
use lib\exception\BadHeader;

use lib\exception\BadHeaderException;
use lib\exception\RequestException;

use lib\BufferedResponse as Response;
use lib\Predis\Client as PredisClient;
use model\Video;

class upload extends SessionController
{

    public $extension = ".mp4";
    const TIMEOUT = 30;

    const POST      = 'POST';
    const HEAD      = 'HEAD';
    const PATCH     = 'PATCH';
    const OPTIONS   = 'OPTIONS';
    const GET       = 'GET';

    private $uuid       = null;
    private $directory  = null;
    private $path       = null;

    private $response   = null;

    private $redis          = null;
    private $redis_options  = array(
        'prefix'    => 'php-tus-',
        'scheme'    => 'tcp',
        'host'      => '127.0.0.1',
        'port'      => '6379',
    );

    /**
     * Constructor
     *
     * @param   string      $directory      The directory to use for save the file
     * @param   string      $path           The path to use in the URI
     * @param   null|array  $redis_options  Override the default Redis options
     * @access  public
     */
    public function __construct()
    {
        parent::__construct();

        $this->directory = realpath($_SERVER['DOCUMENT_ROOT'] . "/../media/videos/tmp") . "/";


    }

    public function index()
    {

        $this->path = "/upload";
        $this->process();
        $this->response->send();
    }

    public function process($send = false)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];

            if ($method === self::OPTIONS) {
                $this->uuid = null;
            } elseif ($method === self::POST) {
                $this->buildUuid();
            } else {
                $this->getUserUuid();
            }

            switch ($method) {
                case self::POST:
                    $this->processPost();
                    break;

                case self::HEAD:
                    $this->processHead();
                    break;

                case self::PATCH:
                    $this->processPatch();
                    break;

                case self::OPTIONS:
                    $this->processOptions();
                    break;

                case self::GET:
                    $this->processGet($send);
                    break;

                default:
                    throw new ArgumentException('The requested method '.$method.' is not allowed', 405);
            }

            $this->addCommonHeader();

            if ($send === false) {
                return $this->response;
            }
        } catch (BadHeaderException $e) {
            if ($send === false) {
                throw $e;
            }

            $this->response = new Response(null, 400);
            $this->addCommonHeader();
        } catch (RequestException $e) {

            if ($send === false) {
                throw $e;
            }

            $this->response = new Response($e->getMessage(), $e->getCode());
            $this->addCommonHeader();
        } catch (\Exception $e) {
            if ($send === false) {
                throw $e;
            }

            $this->response = new Response(null, 500);
            $this->addCommonHeader();
        }

        $this->response->sendHeaders();

        // The process must only sent the HTTP headers : kill request after send
        exit;
    }


    /**
     * Build a new UUID (use in the POST request)
     *
     * @throws  \DomainException    If the path isn't define
     * @access  private
     */
    private function buildUuid()
    {
        if ($this->path === null) {
            throw new \DomainException('Path can\'t be null when call '.__METHOD__);
        }

        $this->uuid = $this->path ."/" .hash('sha256', uniqid(mt_rand().php_uname(), true));
    }

    /**
     * Get the UUID of the request (use for HEAD and PATCH request)
     *
     * @return  string                      The UUID of the request
     * @throws  \InvalidArgumentException   If the UUID doesn't match with the path
     * @access  private
     */
    private function getUserUuid()
    {

        if ($this->uuid === null) {
            $requestURI = $this->requestURI();
            $queryStringPos = strpos($requestURI,"?");
            if($queryStringPos)
            {
                $uuid = substr($requestURI, 0, $queryStringPos);
            }
            else
            {
                $uuid = substr($requestURI, 0);
            }

            if (strpos($uuid, $this->path) !== 0) {
                throw new \InvalidArgumentException('The uuid and the path doesn\'t match : '.$uuid.' - '.$this->path);
            }

            $this->uuid = $uuid;
        }

        return $this->uuid;
    }


    private function processPost()
    {
        if ($this->existsInRedis($this->uuid) === true) {
            throw new \Exception('The UUID already exists');
        }

        $headers = $this->extractHeaders(array('Entity-Length'));

        if (is_numeric($headers['Entity-Length']) === false || $headers['Entity-Length'] < 0) {
            throw new BadHeaderException('Entity-Length must be a positive integer');
        }

        $final_length = (int)$headers['Entity-Length'];

        $file = $this->directory.$this->getFilename();

        if (file_exists($file) === true) {
            throw new \Exception('File already exists : '.$file);
        }

        if (touch($file) === false) {
            throw new \Exception('Impossible to touch '.$file);
        }

        $this->setInRedis($this->uuid, 'Entity-Length', $final_length);
        $this->setInRedis($this->uuid, 'Offset', 0);

        $this->response = new Response(null, 201, array(
            'Location' => $this->protocol.$this->host . $this->uuid,
        ));
    }

    /**
     * Process the HEAD request
     *
     * @throws  \Exception      If the uuid isn't know
     * @access  private
     */
    private function processHead()
    {

        if ($this->existsInRedis($this->uuid) === false) {
            throw new \Exception("The UUID : $this->uuid  doesn\'t exist");
        }


        $offset = $this->getInRedis($this->uuid, 'Offset');

        $this->response = new Response(null, 200, array(
            'Offset' => $offset,
        ));
    }

    private function processPatch()
    {
        // Check the uuid
        if ($this->existsInRedis($this->uuid) === false) {
            throw new \Exception('The UUID doesn\'t exists');
        }

        // Check HTTP headers
        $headers = $this->extractHeaders(array('Offset', 'Content-Length', 'Content-Type'));

        if (is_numeric($headers['Offset']) === false || $headers['Offset'] < 0) {
            throw new BadHeaderException('Offset must be a positive integer');
        }

        if (is_numeric($headers['Content-Length']) === false || $headers['Content-Length'] < 0) {
            throw new BadHeaderException('Content-Length must be a positive integer');
        }

        if (is_string($headers['Content-Type']) === false || $headers['Content-Type'] !== 'application/offset+octet-stream') {
            throw new BadHeaderException('Content-Type must be "application/offset+octet-stream"');
        }

        // Initialize vars
        $offset_header = (int)$headers['Offset'];
        $offset_redis = $this->getInRedis($this->uuid, 'Offset');
        $max_length = $this->getInRedis($this->uuid, 'Entity-Length');
        $content_length = (int)$headers['Content-Length'];

        // Check consistency (user vars vs database vars)
        if ($offset_redis === null || (int)$offset_redis !== $offset_header) {
            throw new BadHeaderException('Offset header isn\'t the same as in Redis');
        }
        if ($max_length === null || (int)$offset_redis > (int)$max_length) {
            throw new ArgumentException('Entity-Length is required and must be greather than Offset');
        }

        // Check if the file isn't already entirely write
        if ((int)$offset_redis === (int)$max_length) {
            $this->response = new Response(null, 200);
            return;
        }

        // Read / Write data
        $handle_input = fopen('php://input', 'rb');
        if ($handle_input === false) {
            $this->response = new Response(null, 404);
        }

        $file = $this->directory.$this->getFilename();
        $handle_output = fopen($file, 'ab');
        if ($handle_output === false) {
            $this->response = new Response(null, 404);
        }

        if (fseek($handle_output, (int)$offset_redis) === false) {
            $this->response = new Response('Impossible to move pointer in the good position', 500);
        }

        ignore_user_abort(true);

        $current_size = (int)$offset_redis;
        $total_write = 0;

        while (true) {
            set_time_limit(self::TIMEOUT);

            // Manage user abort
            if(connection_status() != CONNECTION_NORMAL) {
                fclose($handle_input);
                fclose($handle_output);
                $this->response = new Response(null, 100);
                return;
            }

            $data = fread($handle_input, 8192);
            if ($data === false) {
                fclose($handle_input);
                fclose($handle_output);
                $this->response = new Response(null, 500);
                return;
            }

            $size_read = strlen($data);

            // If user sent more datas than expected (by POST Entity-Length), abort
            if ($size_read + $current_size > $max_length) {
                fclose($handle_input);
                fclose($handle_output);
                $this->response = new Response(null, 400);
                return;
            }

            // If user sent more datas than expected (by PATCH Content-Length), abort
            if ($size_read + $total_write > $content_length) {
                fclose($handle_input);
                fclose($handle_output);
                $this->response = new Response(null, 400);
                return;
            }

            // Write datas
            $size_write = fwrite($handle_output, $data);
            if ($size_write === false) {
                fclose($handle_input);
                fclose($handle_output);
                $this->response = new Response(null, 500);
                return;
            }

            $current_size += $size_write;
            $total_write += $size_write;
            $this->setInRedis($this->uuid, 'Offset', $current_size);

            if ($total_write === $content_length) {
                fclose($handle_input);
                fclose($handle_output);
                $monthYear = date("my");
                $relPath =  "videos/" . $monthYear;
                $newDir = realpath($_SERVER['DOCUMENT_ROOT']) . "/" . $relPath;
                @mkdir($newDir, 0777, true);
                $fileName = $this->getFilename() . $this->extension;
                rename($file, $newDir . $fileName);
                $headers = $this->extractHeaders(array('videoID'));
                $videoID = $headers['videoID'];
                $video = new Video($videoID);
                $video->mediaURL = $relPath . $fileName;
                $this->response = new Response($monthYear . $fileName, 200);
                return;
            }
        }
        $this->response = new Response(null, 200);
    }


    /**
     * Process the OPTIONS request
     *
     * @access  private
     */
    private function processOptions()
    {
        $this->response = new Response(null, 200);
    }


    /**
     * Process the GET request
     *
     * @access  private
     */
    private function processGet($send)
    {
        $file = $this->directory.$this->getFilename();

        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RequestException('The file '.$this->uuid.' doesn\'t exist', 404);
        }

        $this->response = new Response(null, 200);
        $this->addCommonHeader();

        $this->response->headers['Content-Type'] = 'application/force-download';
        $this->response->headers['Content-disposition'] = 'attachment; filename="'.str_replace('"', '', basename($this->uuid)).'"';
        $this->response->headers['Content-Transfer-Encoding'] = 'application/octet-stream';
        $this->response->headers['Pragma'] = 'no-cache';
        $this->response->headers['Cache-Control'] = 'must-revalidate, post-check=0, pre-check=0, public';
        $this->response->headers['Expires'] = true;
        $this->response->headers['Content-Length'] = filesize($file);

        if ($send === true) {
            $this->response->sendHeaders();

            readfile($file);
            exit;
        }
    }

    /**
     * Add the commons headers to the HTTP response
     *
     * @access  private
     */
    private function addCommonHeader()
    {
        $this->response->headers['Allow'] = 'OPTIONS,GET,HEAD,POST,PATCH';
        $this->response->headers['Access-Control-Allow-Methods']='OPTIONS,GET,HEAD,POST,PATCH';
        $this->response->headers['Access-Control-Allow-Origin']='*';
        $this->response->headers['Access-Control-Allow-Headers']= 'Origin, X-Requested-With, Content-Type, Accept, Entity-Length, Offset';
        $this->response->headers['Access-Control-Expose-Headers']= 'Location, Range, Content-Disposition, Offset';
    }

    private function extractHeaders($desiredHeaders)
    {
        if (is_array($desiredHeaders) === false) {
            throw new \InvalidArgumentException('Headers must be an array');
        }

        $allHeaders = getallheaders();
        $desiredHeaderKeys = array_fill_keys($desiredHeaders, 1);


        return array_intersect_key($allHeaders, $desiredHeaderKeys);
    }

    /**
     * Get the Redis connection
     *
     * @return  Predis\Client       The Predis client to use for manipulate Redis database
     * @access  private
     */
    private function getRedis()
    {
        if ($this->redis === null) {
            $this->redis = new PredisClient($this->redis_options);
        }

        return $this->redis;
    }


    /**
     * Set a value in the Redis database
     *
     * @param   string      $id     The id to use to set the value (an id can have multiple key)
     * @param   string      $key    The key for wich you want set the value
     * @param   mixed       $value  The value for the id-key to save
     * @access  private
     */
    private function setInRedis($id, $key, $value)
    {
        if (is_array($value) === true) {
            $this->getRedis()->hmset($this->redis_options['prefix'].$id, $key, $value);
        } else {
            $this->getRedis()->hset($this->redis_options['prefix'].$id, $key, $value);
        }
    }


    /**
     * Get a value in the Redis database
     *
     * @param   string      $id     The id to use to get the value (an id can have multiple key)
     * @param   string      $key    The key for wich you want value
     * @return  mixed               The value for the id-key
     * @access  private
     */
    private function getInRedis($id, $key)
    {
        return $this->getRedis()->hget($this->redis_options['prefix'].$id, $key);
    }


    /**
     * Check if an id exists in the Redis database
     *
     * @param   string      $id     The id to test
     * @return  bool                True if the id exists, false else
     * @access  private
     */
    private function existsInRedis($id)
    {
        return $this->getRedis()->exists($this->redis_options['prefix'].$id);
    }


    /**
     * Get the filename to use when save the uploaded file
     *
     * @return  string              The filename to use
     * @throws  \DomainException    If the path isn't define
     * @throws  \DomainException    If the uuid isn't define
     * @access  private
     */
    private function getFilename()
    {
        if ($this->path === null) {
            throw new \DomainException('Path can\'t be null when call '.__METHOD__);
        }

        if ($this->uuid === null) {
            throw new \DomainException('Uuid can\'t be null when call '.__METHOD__);
        }

        return str_replace($this->path, '', $this->uuid);
    }

    private function getResponse()
    {
        if ($this->response === null) {
            $this->response = new Response();
        }

        return $this->response;
    }
}