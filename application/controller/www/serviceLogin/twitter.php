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

class twitter extends SessionController
{
    const TYPE_AUTH = "";
    const TYPE_REAUTH = "reauth";
    public function __construct()
    {
        require_once('../core/classes/twitter/twitteroauth/twitteroauth.php');
        Session::$sessionName = "blizzfullRestaurant";
        parent::__construct();
    }

    public function index($type = self::TYPE_AUTH)
    {
        $this->startSession();
        /* Build TwitterOAuth object with client credentials. */
        $connection = new \TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_KEY_SECRET);

        $accountID = isset($_GET['accountID']) ? $_GET['accountID'] : null;
        $accountIDString = $accountID ? "accountID=" . $accountID : "";
        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken("http://hq.blizzfull.com/serviceLogin/twitter/callback/$type?$accountIDString");

        /* Save temporary credentials to session. */
        $this->session->oauth_token = $token = $request_token['oauth_token'];
        $this->session->oauth_token_secret = $request_token['oauth_token_secret'];



        /* If last connection failed don't display authorization link. */
        switch ($connection->http_code) {
            case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $authenticate = $type == self::TYPE_REAUTH ? false : true;

                $url = $connection->getAuthorizeURL($token, $authenticate, true);
                header('Location: ' . $url);
                break;
            default:
                /* Show notification if something went wrong. */
                echo 'Could not connect to Twitter. Refresh the page or try again later.';
        }

    }

    public function callback($type = self::TYPE_AUTH)
    {
        $this->startSession();

        $user = $this->getCurrentUser();

        $connection = new \TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_KEY_SECRET, $this->session->oauth_token, $this->session->oauth_token_secret);

        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        $twitterApi = new \TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_KEY_SECRET, $access_token["oauth_token"], $access_token['oauth_token_secret']);
        $response = $twitterApi->oAuthRequest("users/show", "GET", array("user_id" => $access_token['user_id'], "screen_name" => $access_token['screen_name']));
        $userInfo = json_decode($response);

        if($type == self::TYPE_REAUTH)
        {
            $accountID = isset($_GET['accountID']) ? $_GET['accountID'] : null;
            $account = new SocialAccount($accountID);
            $account->expired = 0;
            $account->tokenData = json_encode($access_token);
        }
        else $account = SocialAccount::create($user->restaurantID, $user->locationID, $access_token['screen_name'], "twitter", json_encode($access_token), $userInfo->followers_count);

        echo "<script>";
        echo "window.close();";
        echo "window.opener.loginComplete(" . json_encode($account->serializableForm()) . ");";
        die("</script>");
    }
}