<?php

namespace controller\api;


use controller\SessionController;
use core\Session;
use lib\Encryption;
use model\User;
use core\LightOpenID;
use core\Cookie;
use RNCryptor\Autoloader;

class login extends SessionController
{
	const STANDARD_POPUP = 1;
	const APPLICATION_DIALOG = 2;
    const REDIRECT = 3;
    const AUTH_LEVEL_STANDARD = 1;
    const AUTH_LEVEL_ESCALATED = 2;


	protected $uriPath = "/login";

    const AC_ENCRYPTION_KEY = "j2ndB2928fj#n92LqiENdWn83NMS83)@mnsDNbus@";

	public function __construct()
	{
		parent::__construct();
		if(!IN_DEV) $this->requireHTTPS();
	}


    public function facebookLogin()
    {

        $this->requireHTTPPOST();
        //$this->requireHTTPS();

        Autoloader::register();

        //An encrypted userID sent by the app. Even though though this is sent over SSL, the pre-encryption done within the app
        //adds an extra layer of security


        $userIDEncrypted = isset($_POST['d']) ? urldecode($_POST['d']) : $this->returnStatusCode(412);
        $userDataEncrypted = isset($_POST['userData']) ? urldecode($_POST['userData']) : $this->returnStatusCode(412);

        $baseDecoded = base64_decode($userIDEncrypted);

        $cryptor = new \RNCryptor\Decryptor();
        $userIDDecrypted = $cryptor->decrypt($userIDEncrypted, self::AC_ENCRYPTION_KEY);
       // $userIDDecrypted = $encryption->decrypt($baseDecoded, self::AC_ENCRYPTION_KEY);

        if(!$userIDDecrypted || !is_numeric($userIDDecrypted)) $this->returnStatusCode(412, "Bad id");

        $userDataDecrypted = $cryptor->decrypt($userDataEncrypted, self::AC_ENCRYPTION_KEY);
        $userData = json_decode($userDataDecrypted);
        $timestamp = $userData->ts;

        //SECURITY: Check the timestamp sent from the client and check if the difference is greater than 16 hours. if it is, we do not proceed with login
        if(abs(time() - $timestamp) > 57600) $this->returnStatusCode(403);
        $usernameOrID = isset($userData->username) ? $userData->username : $userIDDecrypted;
        $fbEmail = isset($userData->email) ? $userData->email : $usernameOrID . "@facebook.com";

        $attributeArray = array("email" => $fbEmail, "firstName" => $userData->first_name, "lastName" => $userData->last_name, "name" => $userData->name);
        $userModel = User::fetchByExternalID("fb:$userIDDecrypted" , User::IDENT_TYPE_OAUTH, 'facebook', $attributeArray);
        $loginData = $this->completeLogin($userModel, self::AUTH_LEVEL_ESCALATED);
        $this->session->loginProvider = "facebook";
        $responseArray = &$loginData;
        $responseArray["user"] = $userModel->serializableForm();
        $responseArray["authInfo"] = array("loginProvider" => $this->session->loginProvider);
        $this->respond($responseArray);
    }

	public function index()
	{

		$this->authorize();
	}

	public function authorize()
	{
	    $this->requireHTTPPOST();
	    //$this->requireHTTPS();

	    $email =  @$_POST['email'];
	    $password =  @$_POST['password'];

	    if(!$email) $this->returnStatusCode(412, "email required");

        $email = urldecode($email);
        $password = urldecode($password);
	    $user = User::fetchByEmail($email);
        $reauth = isset($_POST['reauth']) ? $_POST['reauth'] : false;

	    if($user && $user->enabled && ($user->verifyPassword($password)))
	    {
            $this->startSession();
            //Simple Reauthentication handler for standard login (non-external)
            if($reauth)
            {
                $this->session->authLevel = self::AUTH_LEVEL_ESCALATED;
                $this->session->authTime = time();

                $lastAction = $this->session->lastAction;
                $reauthData = array("success" => true);

                $this->respond($reauthData);
                return;
            }

            $this->session->loginProvider = "blizzfull";
	    	$loginData = $this->completeLogin($user, self::AUTH_LEVEL_ESCALATED);
            $responseArray = &$loginData;
	    	$responseArray["user"] = $user->serializableForm();
	    	$responseArray["authInfo"] = array("loginProvider" => $this->session->loginProvider);

	    	$this->respond($responseArray);
	    }
	    else
	    {
            if($user && !empty($user->id) && empty($user->password)) $this->respondAndExit("Externally Registered");
            else $this->respond("Incorrect email/password");
	    }
	}

	public function redirect()
	{
	    $this->startSession();
		$lastAction = $this->session->lastAction;

		if($lastAction)
		{
			header("Location: $lastAction");
		}
		else
		{
			header("Location: " . $this->protocol . $this->host);
		}
	}

	protected function loginOAuth()
	{
		$display = "popup";

		if(isset($_REQUEST['type']))
		{
			$responseType = "?type=" . $_REQUEST['type'];
			if($_REQUEST['type'] == self::APPLICATION_DIALOG)
			{
				$display = "touch";
			}
		}
	    else $responseType = "";

			$app_id = BLIZZFULL_FACEBOOK_APP_ID;
	   		$app_secret = BLIZZFULL_FACEBOOK_APP_SECRET;

		   	$my_url = $this->protocol . $this->host . "$this->uriPath/externalLoginCallback/oauth" . $responseType;

		  	$this->session->loginProvider = "facebook";
		   	$scope = "email,user_about_me,user_likes,user_birthday";

		   	$code = @$_REQUEST["code"];
		   	$authLevel = isset($_REQUEST['authLevel']) ? $_REQUEST['authLevel'] : self::AUTH_LEVEL_STANDARD;

			$this->startSession();
		   	if(empty($code)) {
	     		$this->session->state = md5(uniqid(rand(), TRUE)); //CSRF protection
				$dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
				       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&scope=" . $scope . "&display=$display&state="
				       . $this->session->state . "";

				if(isset($this->session->requestedAuthLevel) && $this->session->requestedAuthLevel)
				{
				    $dialog_url .= "&auth_type=reauthenticate";
				}

				header('Location: ' . $dialog_url);
				//die($dialog_url);
				//echo("<script> top.location.href='" . $dialog_url . "'</script>");
			}

	}

	public function externalReauth()
	{
	    $this->startSession();

	    //We must store authentication type stuff in the session, and NOT in a redirect URL, since those can be spoofed or manipulated by malicious users
	  	$this->session->requestedAuthLevel = 2;
	  	$this->session->reauth = true;
	    $this->externalLogin();
	}

    public function externalLogin()
    {
        $this->startSession();
        $this->loginOAuth();

    }

	public function externalLoginCallback($type)
	{
		$responseType = @$_REQUEST['type'];
		$responseType = $responseType ? $responseType : 1;

		$this->startSession();
		$reauth = isset($this->session->reauth) ? $this->session->reauth : false;
		$provider = isset($this->session->loginProvider) ? $this->session->loginProvider : null;
		$authLevel = isset($this->session->requestedAuthLevel) ? $this->session->requestedAuthLevel : 1;
        $customRedirect = isset($this->session->customRedirect) ? $this->session->customRedirect : null;
        unset($this->session->customRedirect);

		if(!$provider) $this->returnStatusCode(412, "Unknown login failure"); //A provider has to exist otherwise something went terrible wrong

		//Clear the temporary session vars
		unset($this->session->reauth);
		unset($this->session->requestedAuthLevel);

		if($type == "openid")
		{
			$openid = new LightOpenID($this->host);

		 	if($openid->mode == 'cancel') {
		 		if($reauth) $this->completeReauth(false, $responseType, $customRedirect);
		        else
                {
                    $this->respondWithBadLogin("Login Canceled", $responseType, $customRedirect);
                }
		    } else {

		        //echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
				if($openid->validate())
				{
					$rcvdAttributes = $openid->getAttributes();
					$firstName = "";
					$lastName = "";
					if(isset($rcvdAttributes['namePerson/first'])) //Most providers support namePerson. However, some (i.e. google) only support namePerson/first, namePerson/last
					{
						$attributes = array("email" => $rcvdAttributes['contact/email'], "firstName" => $rcvdAttributes['namePerson/first'], "lastName" => $rcvdAttributes['namePerson/last']);
					}
					else
					{
						//Break apart the name into first and last
						if(isset($rcvdAttributes['namePerson']))
						{
							$nameArray = explode(" ", $rcvdAttributes['namePerson']);

							$firstName = trim($nameArray[0]);
							$lastName = trim($nameArray[1]);
						}

						$attributes = array("email" => @$rcvdAttributes['contact/email'], "firstName" => $firstName, "lastName" => $lastName);
					}

                    $userModel = User::fetchByExternalID($openid->identity , User::IDENT_TYPE_OPENID, $provider, $attributes);
					if($reauth)
					{
                        $loggedInUser = $this->getCurrentUser();
                        //Make sure the account they are putting the password in for is actually
                        //the account they originally logged in with.
                        if(!$userModel || $userModel->id != $loggedInUser->id)  $this->completeReauth("The user you logged in with does not match the user you provided the password for.", $responseType);
                        else $this->completeReauth(true, $responseType, $customRedirect);
					}
					else
					{
					    $loginData = $this->completeLogin($userModel, $authLevel, $customRedirect);
					}


				}
		    }

		}
		else if($type == "oauth")
		{
			$typeSuffix = $responseType > 1 ? "?type=$responseType" : "";

			if($_REQUEST['state'] == $this->session->state)
	   		{
	   		    unset($this->session->state);
	   			$app_id = "161034957339897";
		   		$app_secret = "a72ceb00532fdd33a0fbc4dc5ee4301e";
			   	$my_url = $this->protocol . $this->host . "$this->uriPath/externalLoginCallback/oauth$typeSuffix";

			   	$scope = "email,user_about_me,user_likes,user_birthday";

				$code = @$_REQUEST["code"];
		    	$token_url = "https://graph.facebook.com/oauth/access_token?"
			    	. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       				. "&client_secret=" . $app_secret . "&code=" . $code;

                $tries = 0;
                $chError = null;
                do
                {
                    $tries++;
                    $ch = curl_init($token_url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    $response = curl_exec($ch);
                    if(empty($response)) $chError = curl_error($ch);
                    curl_close($ch);
                }while($tries <= 4 && empty($response));

		     	$params = null;

		     	parse_str($response, $params);

		     	if(empty($response))
		     	{
                    Log::getLog("webErrors", Log::LOG_TYPE_DB_RECORD)->write(array("type" => "exception", "message" => $chError, "code" => "login", "trace" => __CLASS__."::".__FUNCTION__."(" . __LINE__ . ")", "request" => $this->host . $_SERVER['REQUEST_URI'] . "?" . $_SERVER['QUERY_STRING']), __CLASS__."::".__FUNCTION__."(" . __LINE__ . ")");
		     	    if($reauth)
		     	    {
		     	        $this->completeReauth(false, $responseType, $customRedirect);
		     	    }
		     		else $this->respondWithBadLogin("Unexpected Login Issue. Please review your account and App settings on Facebook.", $responseType, $customRedirect);
		     		return;
		     	}
                if(!isset($params['access_token']))
                {
                    $this->respondWithBadLogin("Unexpected Login Issue. Something is blocking us from acquiring the necessary information from Facebook. You may have denied us permission. Please review your account and App settings on Facebook.", $responseType, $customRedirect);
                }
		     	$graph_url = "https://graph.facebook.com/me?access_token="
			    	. $params['access_token'];

                $chError = null;
                $tries = 0;
                do
                {
                    $tries++;
                    $ch = curl_init($graph_url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    $graphResponse = curl_exec($ch);
                    if(empty($response)) $chError = curl_error($ch);
                    curl_close($ch);
                }while($tries <= 4 && empty($graphResponse));


                if(empty($graphResponse))
                {
                    Log::getLog("webErrors", Log::LOG_TYPE_DB_RECORD)->write(array("type" => "exception", "message" => $chError, "code" => "login", "trace" => __CLASS__."::".__FUNCTION__."(" . __LINE__ . ")", "request" => $this->host . $_SERVER['REQUEST_URI'] . "?" . $_SERVER['QUERY_STRING'], "requestVars" => $_REQUEST), __CLASS__."::".__FUNCTION__."(" . __LINE__ . ")");
                }
		     	$user = json_decode($graphResponse);

                //Quick and simple fix for users whos email is unretreivable for some reason (even when directly permitted)
                $usernameOrID = isset($user->username) ? $user->username : $user->id;
                $fbEmail = isset($user->email) ? $user->email : $usernameOrID . "@facebook.com";

		     	$attributeArray = array("email" => $fbEmail, "firstName" => $user->first_name, "lastName" => $user->last_name);

		     	if(isset($user->location))
		     	{
		     		$locationArray = explode(",", $user->location->name);
		     		$attributeArray["city"] = trim($locationArray[0]);
		     		$attributeArray["state"] = trim($locationArray[1]);
		     	}

		     	if($user->birthday)
		     	{
		     		$dobTime = strtotime($user->birthday);
		     		$dob = date("Y-m-d", $dobTime);
		     		$attributeArray["dob"] = $dob;
		     	}

                $userModel = User::fetchByExternalID("fb:" . $user->id , User::IDENT_TYPE_OAUTH, 'facebook', $attributeArray);

                if($reauth)
		     	{
                    $loggedInUser = $this->getCurrentUser();
                    if(!$userModel || $userModel->id != $loggedInUser->id)  $this->completeReauth("The user you logged in with does not match the user you provided the password for.", $responseType);
		     	    else $this->completeReauth(true, $responseType, $customRedirect);
		     	    return;
		     	}

				$loginData = $this->completeLogin($userModel, $authLevel, $customRedirect);

	   		}
	   		else
	   		{
	   		    if($reauth)
	   		    {
	   		        $this->completeReauth(false, $responseType, $customRedirect);
	   		    }
	     		else $this->respondWithBadLogin("The state does not match. You may be a victim of CSRF.", $responseType, $customRedirect);
	   		}
		}

		if($userModel->id)
   		{
   			$this->respondWithGoodLogin($loginData, $responseType, $customRedirect);
   		}
	}

	protected function completeLogin($userModel, $authLevel)
	{
        $this->startSession();
	    $loginData = array("user" => $userModel);
        $this->session->authLevel = $authLevel;
        $this->session->authTime = time();
        $this->session->login($userModel,0);

	   	return $loginData;
	}

	protected function completeReauth($success, $type, $customRedirect = null)
	{
        $reauthData = array();

        $lastAction = $this->session->lastAction;
        if($lastAction)
        {
            $match = preg_match("~^([a-zA-Z]*):(.*)~", $lastAction, $actionArgs);
            if(isset($actionArgs[2])) $reauthData["url"] = $actionArgs[2];
        }

	    if($success !== false)
	    {
	        $this->session->authTime = time();
	        $this->session->authLevel = self::AUTH_LEVEL_ESCALATED;

            if($customRedirect)
            {
                header("Location: $customRedirect");
                exit();
            }

	    	if($type == self::STANDARD_POPUP)
			{
				echo "<script>";
                echo "window.close();";
				echo "window.opener.reauthSuccess(" . json_encode($reauthData) . ");";
				die("</script>");
			}
	   		else die("<script>function getReauthResponse(){ return " . json_encode($reauthData) . ";};</script>");
	    }
	    else
	    {
            if($customRedirect)
            {
                //Get the base and append the url path to the end
                $customRedirect = substr($customRedirect, 0, strpos($customRedirect, ".com") + 4);
                header("Location: $customRedirect/reauth");
                exit();
            }

	        if($type == self::STANDARD_POPUP)
	        {
	            echo "<script>";
                echo "window.close();";
	            echo "window.opener.reauthFailure(" . json_encode($reauthData) . ");";
	            die( "</script>");
	        }
	        else die("<script>function getReauthResponse(){ return " . json_encode($reauthData) . ";};</script>");
        }
	}

	protected function respondWithBadLogin($info, $type = self::STANDARD_POPUP, $customRedirect = null)
	{
        //Have to use a hack for the whole mobile login flow because of iOS Chrome, which will not breaks with popups from an iframe

        if($customRedirect) {
            $customRedirect = substr($customRedirect, 0, strpos($customRedirect, ".com") + 4);
            header("Location: $customRedirect/login?success=0");
            exit();
        }

        if($type == self::STANDARD_POPUP)
		{
			echo "<script>";
            echo "window.close();";
            echo "window.opener.loginFailure('$info');";
			die( "</script>");
		}
		else die("<script>function getErrorMessage(){ return '$info';};</script>");
	}

	protected function respondWithGoodLogin($loginData, $type = self::STANDARD_POPUP, $customRedirect = null)
	{
	    $responseArray = &$loginData;
        if($customRedirect) {
            header("Location: $customRedirect");
            exit();
        }

        $userModel = $loginData["user"];
        $responseArray["user"] = $userModel->serializableForm();
        $responseArray["authInfo"] = array("loginProvider" => $this->session->loginProvider, "authLevel" => $this->getAuthLevel());
        $addresses = $userModel->addresses();

        if($addresses) $responseArray["user"]["addresses"] = $addresses;

		if($type == self::STANDARD_POPUP)
		{
			echo "<script>";
			echo "window.close();";
			echo "window.opener.loginSuccess(" . json_encode($responseArray) . ");";
			die("</script>");
		}
   		else
   		{
   			echo "<script>";
   			echo "function getLoginData(){";

   			echo "var loginData = '" . json_encode($responseArray) . "';";
   			echo "return loginData;";
   			echo "};";

   			echo "</script>";
   		}
	}
}