<?php

namespace controller;
use controller\SessionController;
use lib\Session;

abstract class ModelControllerNoSession extends controller
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

        $args = func_get_args();

	    $id = array_shift($args);
	    $method = array_shift($args);
	    if(!$method)
	    {
	        if(!is_numeric($id))
	        {

                if(empty($id)) $method = "getList";
	            else $method = $id; //Factory/search method provided, NOT an orderID
	        }
	        else
            {
                $this->modelID = $id;
                if($this->modelClass && $id) $this->model = new $this->modelClass($id);
                $method = "get";
            }
	    }else
	    {
	    	$this->modelID = $id;
	    	if($this->modelClass && $id) $this->model = new $this->modelClass($id);
	    }


        if(!$this->permitted()) $this->returnStatusCode("403", "Permission Denied");
	    call_user_func_array(array($this, $method), $args);
	   
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
                $missingArguments[] = $parameter->name; //Collect data on missing arguments to inform the caller
            }

        }


        if($missingArguments) $this->returnStatusCode(412, "Missing: " . implode(",", $missingArguments));
        $newModel = call_user_func_array(array($this->modelClass, "create"), $arguments);

        if(isset($_POST['name'])) $activityObjectName = $_POST['name'];
        else $activityObjectName = "";
        $classNameArray = explode("\\", $this->modelClass);
        $user->logActivity("Created " . end($classNameArray) . ": " . $activityObjectName, get_clas($this));
        $this->respond($newModel->id);
    }
	
	protected function delete()
	{
        $user = $this->getCurrentUser();
        $classNameArray = explode("\\", $this->modelClass);
        if(isset($this->model->name)) $activityObjectName = $this->model->name;
        elseif(isset($this->model->username)) $activityObjectName = $this->model->username;
        elseif(isset($this->model->message)) $activityObjectName = $this->model->message;
        else $activityObjectName = $this->model->id;
        $user->logActivity("Removed " . end($classNameArray) . ": <b>" . $activityObjectName . "</b>", get_class($this));
		$this->model->delete();
	}
	
	protected function get()
	{

		return $this->respond($this->model->serializableForm());
	}

}