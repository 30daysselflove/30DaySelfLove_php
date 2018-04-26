<?php

namespace controller\api\mcs;

use controller\ModelController;
use model\User;

use model\Video;
use model\VideoComment;

class video_comments extends ModelController
{
    public $modelClass = "\\model\\VideoComment";

    protected function permitted()
    {
        $user = $this->getCurrentUser();

        return !$this->model || $this->model->userID == $user->id;
    }

    protected function getList()
    {

        $this->respond(VideoComment::findAll());
    }

    protected function create()
    {
        $user = $this->requireUser();
        $videoID = isset($_POST['videoID']) ? $_POST['videoID'] : $this->returnStatusCode(412, 'videoID required');
        $comment = isset($_POST['comment']) ? $_POST['comment'] : $this->returnStatusCode(412, 'comment required');

        $comment = urldecode($comment);
        $model = VideoComment::create($videoID, $user, $comment);
        $video = new Video($videoID);
        if($user->id != $video->userID) $video->newcomments++;
        $this->respond($model);
    }

    protected function getForVideo()
    {
        $videoID = isset($_GET['videoID']) ? $_GET['videoID'] : $this->returnStatusCode(412, 'videoID required');
        $this->respond(VideoComment::findForVideo($videoID));
    }

    protected function set()
    {
        $settableFields = array(
            "comment" => null
        );
        $fields = array_intersect_key($_POST, $settableFields);
        foreach($fields as &$field)
        {
            $field = urldecode($field);
        }
        $this->model->set($fields);
    }

    protected function delete()
    {
        $user = $this->getCurrentUser();

        //Check if owner of comment or video
        if($user->id != $this->model->userID)
        {
            $video = new Video($this->model->videoID);
            if($video->userID != $user->id) $this->returnStatusCode(401, "You are not the owner of this comment or video");
        }
        $this->model->delete();
        $this->respond($this->model);
    }






}
