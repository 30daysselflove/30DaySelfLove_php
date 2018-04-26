<?php
namespace controller;

use lib\Session;

abstract class SessionController extends controller
{
  
	protected $session;
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	protected function requireUser()
	{
		$user = $this->getCurrentUser();
		if(!$user) $this->notLoggedIn();
		else return $user;
	}
	
	public function logout()
	{
	    if($this->getCurrentUser()) $this->session->destroy();
	}
	
	protected function getCurrentUser()
	{
		if(!$this->session)
		{
			if(Session::check())
			{
			
				$this->startSession();
			}
			else return;
		}

		$user = $this->session->user;

		return $user;
	}
	
	protected function startSession($expiration = null)
	{
		$this->session = Session::getInstance($expiration);
	}
	
}
