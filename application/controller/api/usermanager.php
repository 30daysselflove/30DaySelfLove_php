<?php

namespace controller\api;

use lib\exception\ArgumentException;
use lib\exception\SecurityException;
use model\Campaign;
use model\RetailerGroup;
use model\User;


use controller\SessionController;

use components\Emailer;

use components\EmailTemplate;


class usermanager extends SessionController
{

    public static function sendActivationEmail($user, $regenCode = true)
    {
        if($regenCode)
        {
            $user->securityCode = User::generateSecureCode();
            $user->securityCodeExpiration = time() + User::SECURITY_CODE_EXPIRATION_DEFAULT;
        }

        $activationCode = $user->securityCode;
        $emailer = new Emailer();
        $email = $user->email;
        $userID = $user->id;

        $emailTemplate = new EmailTemplate("email-new.tpl");
        $emailTemplate->link = "http://" . BASE_DOMAIN ."/activate?userID=$userID&activationCode=$activationCode";


        $emailer->email($email, "An user account has been created for you on Phantomtweets.com",$emailTemplate->render());

    }

    public static function sendResetEmail($user, $regenCode = true)
    {
        if($regenCode)
        {
            $user->securityCode = User::generateSecureCode();
            $user->securityCodeExpiration = time() + User::SECURITY_CODE_EXPIRATION_DEFAULT;
        }

        $activationCode = $user->securityCode;
        $emailer = new Emailer();
        $email = $user->email;
        $userID = $user->id;

        $emailTemplate = new EmailTemplate("email-reset.tpl");
        $emailTemplate->link = "http://join.30daysselflove.com/reset?userID=$userID&activationCode=$activationCode";
        $emailTemplate->user = $user;
        $emailer->email($email, "Reset the password for user: \"$user->username\"", $emailTemplate->render());
    }

    public function forgot()
    {
        $email = isset($_POST['email']) ? $_POST['email'] : $this->returnStatusCode(412, 'email required');

        $user = User::fetchByEmail($email);
        if(!$user) $this->respondAndExit("Could not find an account with that email");
        self::sendResetEmail($user);
        $this->respond();
    }

	public function create()
    {
        $this->requireHTTPPOST();
        $username = isset($_POST['username']) ? $_POST['username'] : $this->returnStatusCode(412, 'username required');

        $email = isset($_POST['email']) ? $_POST['email'] : $this->returnStatusCode(412, 'email required');
        $realName = isset($_POST['realName']) ? $_POST['realName'] : "";
        /*if(isset($_POST['autoEnable']) && $_POST['autoEnable'] == "38fj3Adjm3kdMlqIDj") $autoEnable = true;
        else $autoEnable = false;*/

        $password = isset($_POST['password']) ? $_POST['password'] : uniqid();

        $user = User::create($username, $email, $realName, $password);
        $this->startSession();
        $this->session->login($user);
        $this->respond($user);

        //self::sendActivationEmail($user,false);
    }

    public function resetPassword($userID)
    {
        $user = new user($userID);
        self::sendResetEmail($user);
    }

    public function get($userID)
    {
        $campaignStats = @$_GET['campaignStats'];
        $user = new user($userID);
        $userData = $user->serializableForm();
        if($campaignStats && $campaignStats > 0)
        {
            $stats = $user->campaignStats($campaignStats);
            $userData['postCount'] = $stats['postCount'];
            $userData['branded'] = $stats['branded'];
            $userData['sponsored'] = $stats['sponsored'];
            $userData['nonbranded'] = $stats['nonbranded'];
            $userData['expectedPostCount'] = $stats['expectedPostCount'];
            $userData['percentage'] = round($stats['postCount'] / $stats['expectedPostCount'] * 100);
        }
        $this->respond($userData);
    }



    public function delete($userID)
    {
        $activeUser = $this->requireUser();
        if($activeUser->type != "root") $this->returnStatusCode("403", "Permission Denied");
        $user = new user($userID);


        $user->delete();
    }



    public function getList()
    {
        $users = User::findAll();
        $this->respond($users);
    }

    public function set($userID)
    {
        $activeUser = $this->requireUser();
        if($activeUser->type != "root") $this->returnStatusCode("403", "Permission Denied");
        $user = new user($userID);

        $settableFields = array(
            "email" => null,
            "realName" => null,
            "username" => null,
            "type" => null,
            "profileHeader" => null
        );
        $fields = array_intersect_key($_POST, $settableFields);

        $user->set($fields);
    }



	

}
