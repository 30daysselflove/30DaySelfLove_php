<?php

namespace controller\api;

use controller\SessionController;
use lib\Encryption;
use model\User;

use model\Video;

class invite extends SessionController
{

    const USER_ID_ENCRYPTION_KEY = "3jAmdnlfVdjsl1DjnfiS1%dn2((3";

    public function index($userID = null)
    {
        if($userID)
        {
            $encryption = new Encryption(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $userIDEncrypted = $encryption->encrypt($encryption, self::USER_ID_ENCRYPTION_KEY);
            $this->respond($userIDEncrypted);
        }
    }

}
