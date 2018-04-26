<?php

namespace controller\api\mcs;

use controller\ModelController;
use lib\UploadedFile;
use model\Affirmation;
use model\User;

use model\Video;
use model\VideoComment;

class affirmations extends ModelController
{
    public $modelClass = "\\model\\Affirmation";

    protected function getList()
    {
        Affirmation::updateFromTwitter();

        $this->respond(Affirmation::findAll());
    }

    protected function set()
    {
        $settableFields = array(

        );
        $fields = array_intersect_key($_POST, $settableFields);
        $this->model->set($fields);
    }






}
