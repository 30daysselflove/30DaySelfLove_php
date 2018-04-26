<?php

namespace controller\join;

use model\User;

use controller\SmartyController;

use controller\controller;
use model\WelcomePage;


class reset extends SmartyController
{

    public function index()
    {
        $userID = isset($_GET['userID']) ? $_GET['userID'] : $this->returnStatusCode(412, "User ID Required");
        $activationCode = isset($_GET['activationCode']) ? $_GET['activationCode'] : $this->returnStatusCode(412, "Activation Code Required");

        $user = new User($userID);
        $this->templateData->userID = $userID;
        if($user->validateSecurityCode($activationCode))
        {
            $this->startSession();
            $this->session->login($user);
            $this->template = "reset.tpl";
        }
        else
        {
            $this->template = "reset-invalid.tpl";
        }

        $this->display();
    }

    public function createPassword()
    {
        $password = isset($_POST['password']) ? $_POST['password'] : $this->returnStatusCode(412, 'password required');
        $user = $this->requireUser();
        $user->setPassword($password);
        $user->enabled = true;
        $user->securityCode = "";
        $this->respond();

    }

    public function resendEmail()
    {
        $userID = isset($_GET['userID']) ? $_GET['userID'] : $this->returnStatusCode(412, "User ID Required");
        $user = new User($userID);
        forgot::sendResetEmail($user);
        $this->template = "reset-sent.tpl";
        $this->display();
    }



	

}
