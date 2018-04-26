<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adam Dougherty
 * Date: 6/13/13
 * Time: 7:03 PM
 * To change this template use File | Settings | File Templates.
 */

namespace controller\hq\serviceLogin;

use controller\SessionController;
use model\SocialAccount;
use core\Session;

class facebook extends SessionController
{

    public function __construct()
    {
        require_once('../core/classes/facebook/facebook.php');
        Session::$sessionName = "blizzfullRestaurant";
        parent::__construct();
    }

    public function reauth()
    {
        $this->startSession();
        $facebook = new \Facebook(array('appId' => BLIZZFULL_FACEBOOK_APP_ID, 'secret' => BLIZZFULL_FACEBOOK_APP_SECRET));
        $loginUrl = $facebook->getLoginUrl();

    }

    public function index()
    {
        $this->startSession();
        $facebook = new \Facebook(array('appId' => BLIZZFULL_FACEBOOK_APP_ID, 'secret' => BLIZZFULL_FACEBOOK_APP_SECRET));
        $protocol = $this->protocol;
        $loginUrl = $facebook->getLoginUrl(array("auth_type" => "reauthenticate", 'scope' => 'photo_upload,user_status,publish_stream,user_photos,manage_pages', "display" => "popup", "redirect_uri" => $protocol . "hq.blizzfull.com/serviceLogin/facebook/callback"));
        header('Location: ' . $loginUrl);
    }

    public function callback()
    {
        $this->startSession();
        $user = $this->getCurrentUser();
        $facebook = new \Facebook(array('appId' => BLIZZFULL_FACEBOOK_APP_ID, 'secret' => BLIZZFULL_FACEBOOK_APP_SECRET));
        $facebook->setExtendedAccessToken();
        $access_token = $facebook->getAccessToken();
        $tokenData = array("access_token" => $access_token);

        $userInfo = $facebook->api("/me", "GET");
        $pages = $facebook->api("/me/accounts", "GET");
        
        $username = isset($userInfo['username']) ? $userInfo['username'] : $userInfo['name'];

        $account = SocialAccount::create($user->restaurantID, $user->locationID, $username, "facebook", json_encode($tokenData), 0);
        echo "<script>";
        echo "window.close();";
        echo "window.opener.loginComplete(" . json_encode($account->serializableForm()) . "," . json_encode($pages) .")";
        die("</script>");
    }
}