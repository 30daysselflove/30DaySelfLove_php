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

use Instagram\Auth;
use Instagram\Instagram as InstagramLib;




class instagram extends SessionController
{

    public function __construct()
    {
        require('../core/classes/Instagram/_SplClassLoader.php' );
        $loader = new \SplClassLoader( 'Instagram', dirname( __DIR__ )  );
        $loader->register();

        parent::__construct();
    }

    public function index()
    {

        $this->startSession();
        $auth_config = array(
            'client_id'         => INSTAGRAM_CLIENT_ID,
            'client_secret'     => INSTAGRAM_CLIENT_SECRET,
            'redirect_uri'      => $this->protocol . $this->url() . "/callback",
            'scope'             => array( 'likes', 'comments', 'relationships' )
        );

        /* Build TwitterOAuth object with client credentials. */
        $auth = new Auth( $auth_config );

        $auth->authorize();

    }

    public function callback()
    {
        $this->startSession();

        $auth_config = array(
            'client_id'         => INSTAGRAM_CLIENT_ID,
            'client_secret'     => INSTAGRAM_CLIENT_SECRET,
            'redirect_uri'      => $this->protocol . $this->url() . "/callback",
            'scope'             => array( 'likes', 'comments', 'relationships' )
        );

        /* Build TwitterOAuth object with client credentials. */
        $auth = new Auth( $auth_config );

        $tokenData =  $auth->getAccessToken( $_GET['code'] );

        $instagram = new InstagramLib;
        $instagram->setAccessToken( $tokenData );

        $current_user = $instagram->getCurrentUser();

        $account = SocialAccount::create($current_user->username, "instagram", $tokenData, $current_user->counts->follows);

        echo "<script>";
        echo "window.close();";
        echo "window.opener.loginComplete(" . json_encode($account->serializableForm()) . ");";
        die("</script>");
    }
}