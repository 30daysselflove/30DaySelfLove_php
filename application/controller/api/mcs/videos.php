<?php

namespace controller\api\mcs;

use controller\ModelController;
use lib\UploadedFile;
use model\User;

use model\Video;
use model\VideoComment;

class videos extends ModelController
{
    public $modelClass = "\\model\\Video";

    protected function permitted()
    {
        return true;
    }

    protected function getList()
    {
        $listContext = @$_GET['videoListContext'];
        if($listContext == 0)
        {
            $this->findRecentByFollows();
            return;
        }

        $cursor = isset($_GET['cursor']) ? $_GET['cursor'] : 0;

        $this->respond(Video::findAll($cursor));
    }

    protected function get()
    {
        $resetNewCommentCount = isset($_GET['resetNewCommentCount']) ? $_GET['resetNewCommentCount'] : false;
                
        //Also return comments along with the video data
        $videoData = $this->model->serializableForm();
        $videoData['comments'] = VideoComment::findForVideo($this->model->id);
        if($resetNewCommentCount) $this->model->newcomments = 0;
        return $this->respond($videoData);
    }

    protected function getListForUser()
    {
        $userID = isset($_GET['userID']) ? $_GET['userID'] : $this->returnStatusCode(412, 'userID required');
        $cursor = isset($_GET['cursor']) ? $_GET['cursor'] : 0;
        $this->respond(Video::findByUser($userID, $cursor));
    }

    protected function findRecentByFollows()
    {
        $cursor = isset($_GET['cursor']) ? $_GET['cursor'] : 0;

        $user = $this->getCurrentUser();
        $this->respond(Video::findByUserFollows($user->id, $cursor));
    }

    protected function file($settableFields = null)
    {
        $settableFields = array(
            "thumbImageURL" => null,
            "mediaURL" => null
        );
        parent::file($settableFields);
    }

    protected function set()
    {
        $settableFields = array(
            "description" => null,
            "title" => null,
            "thumbImageURL" => null,
            "public" => null,
            "newcomments" => null
        );
        $fields = array_intersect_key($_POST, $settableFields);
        foreach($fields as &$field)
        {
            $field = urldecode($field);
        }

        $this->model->set($fields);
    }






}
