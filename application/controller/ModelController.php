<?php

namespace controller;
use controller\SessionController;
use lib\Session;
use lib\UploadedFile;

abstract class ModelController extends SessionController
{
	public $modelClass = null;
	
	public $modelID;
	public $model;

    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $this->requireUser();

        $args = func_get_args();

	    $id = array_shift($args);
	    $method = array_shift($args);
	    if(!$method)
	    {
	        if(!is_numeric($id))
	        {

                if(empty($id))
                {
                    if($this->isPost()) $method = "create";
                    else $method = "getList";
                }
	            else $method = $id; //Factory/search method provided, NOT an orderID
	        }
	        else
            {
                $this->modelID = $id;
                if($this->modelClass && $id) $this->model = new $this->modelClass($id);
                if($this->isPost()) $method = "set";
                else $method = "get";
            }
	    }else
	    {
	    	$this->modelID = $id;
	    	if($this->modelClass && $id) $this->model = new $this->modelClass($id);
	    }

        if(!$this->permitted()) $this->returnStatusCode("403", "Permission Denied");
	    call_user_func_array(array($this, $method), $args);
	   
	}

    protected function file($settableFields = array())
    {
        $fields = array_intersect_key($_FILES, $settableFields);
        $responseArray = array();

        foreach($fields as $key => $value)
        {
            $file = new UploadedFile($key);
            $filePath = date("my") . "/" . uniqid();

            $file->saveAs("../media/$file->type/$filePath");
            $this->model->$key = "$file->type/$filePath" . ".". $file->extension;
            $responseArray[] = "$file->type/$filePath" . ".". $file->extension;
        }

        $this->respond($responseArray);
    }

    protected function permitted()
    {
        return true;
    }

    abstract protected function getList();
	abstract protected function set();
	
	protected function create()
    {
        $user = $this->getCurrentUser();
        $reflectedMethod = new \ReflectionMethod($this->modelClass, "create");
        $parameters = $reflectedMethod->getParameters();
        $reqCount = $reflectedMethod->getNumberOfRequiredParameters();

        $missingArguments = array();

        $i = 0;
        foreach ($parameters as $parameter)
        {
            if($i == $reqCount) break;
            $i++;
            if(array_key_exists($parameter->name, $_POST))
            {
                $arguments[] = $_POST[$parameter->name];
            }
            else
            {
                //Automatically use the current users id for create methods that have a userID parameter
                if($parameter->name == "userID" && $user)
                {
                    $arguments[] = $user->id;
                }
                else $missingArguments[] = $parameter->name; //Collect data on missing arguments to inform the caller
            }

        }


        if($missingArguments) $this->returnStatusCode(412, "Missing: " . implode(",", $missingArguments));
        $newModel = call_user_func_array(array($this->modelClass, "create"), $arguments);

        $this->respond($newModel->id);
    }
	
	protected function delete()
	{
        $user = $this->getCurrentUser();

		$this->model->delete();
	}
	
	protected function get()
	{

		return $this->respond($this->model->serializableForm());
	}

}