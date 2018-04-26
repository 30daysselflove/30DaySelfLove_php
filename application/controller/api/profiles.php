<?php

namespace controller\api;

use controller\SessionController;
use model\User;

use model\Video;

class profiles extends SessionController
{

    public function index($userID)
    {
        $me = $this->getCurrentUser();
        $user = new User($userID);
        $userData = $user->serializableForm();
        $totalFound = 0;
        $userData['videos'] = Video::findByUser($userID, 0,30,false, $totalFound);
        if($me)
        {
            $userData['following'] = $me->isFollowing($userID);
        }
        $userData['totalVideos'] = $totalFound;
        $this->respond($userData);
    }

    public function followOrUnfollow($userID)
    {
        $user = $this->requireUser();
        if($user->isFollowing($userID))
        {
            $user->unfollow($userID);
            $this->respond(array("following" => false));
        }
        else
        {
            $user->follow($userID);
            $this->respond(array("following" => true));

        }
    }

}
