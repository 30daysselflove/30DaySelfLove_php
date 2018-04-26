<?php


namespace controller;
class ActiveViewController extends SmartyController
{
	public $user;
    
    public function __construct()
	{
		parent::__construct();
		$this->init();
	}

	protected function init()
	{
		
		$this->user = $this->requireUser();
		$this->templateData->user = $this->user;
		$this->templateData->itemsCount = $this->user->newItemsCount();
		$this->templateData->userName = $this->user->firstName;
	}

	public function activate()
	{
		
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			if(!isset($_POST['action'])) $this->returnStatusCode(412, "Active view controllers require an action in a POST context");
			
			$action = $_POST['action'];
			if(!is_callable(array($this, $action))) $this->returnStatusCode(412, "Action doesn't exist for this controller");
			
			$this->$action();
			
		}
		else
		{
			$this->index();
		}
	}
	
	
	
}