<?php


namespace model;

use lib\UtilityFunctions;
use model\MySQLActiveRecord;
use lib\exception\DatabaseException;
use lib\exception\ArgumentException;
use lib\Session;


class VideoComment extends MySQLActiveRecord
{
	const BASIC_FIELDS = "video_comments.id, videoID, video_comments.userID, comment, postDate";
    const TABLE_NAME = "video_comments";

    protected static $PREPENDS = array(
    );


    public static function findAll($filter = null, $limit = 200, $offset = 0, $objectForm = false)
    {
        $query = " SELECT " .
                    self::BASIC_FIELDS . "
                   FROM " . self::TABLE_NAME . "
                    LIMIT $offset, $limit";

        return self::mapObject($query, $objectForm, true, null, null);
    }

    public static function findForVideo($videoID, $limit = 75, $offset = 0, $objectForm = false)
    {
        self::sanitizeArguments($videoID);
        $query = "  SELECT " .
                    self::BASIC_FIELDS . ",
                    users.username,
                    users.realName
                    FROM " . self::TABLE_NAME . "
                    JOIN users on video_comments.userID = users.id
                    WHERE videoID = $videoID
                    ORDER BY id ASC
                    LIMIT $offset, $limit";

        return self::mapObject($query, $objectForm, true, null, null);
    }

    public static function findByUser($userID, $limit = 30, $offset = 0, $objectForm = false)
    {
        self::sanitizeArguments($userID);
        $query = "  SELECT " .
                    self::BASIC_FIELDS . "
                    FROM " . self::TABLE_NAME . "
                    WHERE userID = $userID
                    ORDER BY id DESC
                    LIMIT $offset, $limit";

        return self::mapObject($query, $objectForm, true, null, null);
    }

	public static function create($videoID, $user, $comment, $postDate = null)
	{
        if(!$postDate) $postDate = self::getFormattedDateTime(); //Now

        $kv = array("videoID" => $videoID, "userID" => $user->id, "comment" => $comment, "postDate" => $postDate);
		$result = self::createRecord($kv);
        $result->injectSet("realName", $user->realName);
        $result->injectSet("username", $user->username);
        return $result;
	}


}