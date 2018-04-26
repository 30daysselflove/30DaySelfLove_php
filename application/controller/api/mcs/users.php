<?php

namespace controller\api\mcs;

use controller\SessionController;
use model\User;


class users extends SessionController
{


    public function index()
    {

    }

    public function follow()
    {
        $userToFollowID = isset($_POST['userToFollowID']) ? $_POST['userToFollowID'] : $this->returnStatusCode(412, 'userToFollowID required');
        $user = $this->requireUser();
        $user->follow($userToFollowID);
        $this->respond(true);
    }

    public function set()
    {
        $user = $this->requireUser();
        $settableFields = array(
            "email" => null,
            "realName" => null,
            "username" => null,
            "type" => null,
            "profileHeader" => null
        );
        $fields = array_intersect_key($_POST, $settableFields);
        foreach($fields as &$field)
        {
           $field = urldecode($field);
        }
        $user->set($fields);
    }


}
