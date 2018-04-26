<?php
/**
 * Created by PhpStorm.
 * User: Adam Dougherty
 * Date: 12/15/14
 * Time: 2:03 PM
 */

namespace controller\join;

use controller\SmartyController;
use model\User;
use model\Video;

class prime extends SmartyController
{
    const HASH_SECRET = "30bjd8891bb!j38abMiiC";
    public function user($id, $hash)
    {
        $testHash = md5("$id-". self::HASH_SECRET);
        if($testHash != $hash)
        {
            //$this->returnStatusCode(401);
        }

        $user = new User($id);
        $this->templateData->user = $user;
        $this->templateData->redirect = "";
        require $_SERVER['DOCUMENT_ROOT'] . "/../lib/mobiledetect/Mobile_Detect.php";
        $detect = new \Mobile_Detect();
        if($detect->isIOS())
        {
            $this->templateData->redirect = "thirtydays://actions/follow/$id";
        }
        //Render a simple info page
        $this->template = "user.tpl";
        $this->display();

    }

    public function video($id, $hash)
    {
        $testHash = md5("$id-". self::HASH_SECRET);
        if($testHash != $hash)
        {
          //  $this->returnStatusCode(401);
        }

        $video = new Video($id);
        $this->templateData->video = $video;
        $user = new User($video->userID);
        $this->templateData->user = $user;

        $this->templateData->redirect = "";
        require $_SERVER['DOCUMENT_ROOT'] . "/../lib/mobiledetect/Mobile_Detect.php";
        $detect = new \Mobile_Detect();

        if($detect->isIOS())
        {
            $this->templateData->redirect = "thirtydays://actions/watch/$id/follow/" . $video->userID;
        }
        $this->template = "video.tpl";
        $this->display();

    }
}