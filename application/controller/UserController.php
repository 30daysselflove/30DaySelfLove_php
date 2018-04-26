<?php

namespace controller;

use controller\SmartyController;

class UserController extends SmartyController
{
    public $user;
    public $loggedIn = false;
	public function __construct()
	{
		parent::__construct();
		
		$this->user = $this->requireUser();
		
		$this->templateData->user = $this->user;
		$this->templateData->itemsCount = $this->user->newItemsCount();
		$this->templateData->userName = $this->user->firstName;
	}
}