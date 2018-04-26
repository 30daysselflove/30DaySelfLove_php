<?php

namespace controller\www;

use controller\SessionController;
use lib\Encryption;
use model\User;

use model\Video;

class invite extends SessionController
{

    public function index($userIDToken = null)
    {
        if($userIDToken)
        {
            $encryption = new Encryption(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $userID = $encryption->decrypt($userIDToken, self::USER_ID_ENCRYPTION_KEY);

        }
    }

}
