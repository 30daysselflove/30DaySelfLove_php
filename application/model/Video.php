<?php


namespace model;

use lib\UtilityFunctions;
use model\MySQLActiveRecord;
use lib\exception\DatabaseException;
use lib\exception\ArgumentException;
use lib\Session;


class Video extends MySQLActiveRecord
{
	const BASIC_FIELDS = "videos.id, videos.userID, title, newcomments, description, mediaURL, public, thumbImageURL, supports, uploadDate, reports";
    const TABLE_NAME = "videos";

    protected static $PREPENDS = array(
        "mediaURL" => MEDIA_URL,
        "thumbImageURL" => MEDIA_URL
    );

    protected function get()
    {
        if(!$this->id) throw new ArgumentException("A model ID is required for all basic getter/setter functions");
        $class = get_called_class();
        $query = "	SELECT " . $class::BASIC_FIELDS . ",
                        users.username,
                        users.realName
					FROM " . $class::TABLE_NAME . "
					LEFT JOIN users ON
					    users.id = videos.userID
					WHERE videos.id = $this->id";

        $result = self::getSingleResult($query);
        $class = get_called_class();
        if($class::$JSON_COLUMNS)
        {
            $jsonColumns = $class::$JSON_COLUMNS;
            foreach($jsonColumns as $jsonColumn)
            {
                $result[$jsonColumn] = json_decode($result[$jsonColumn]);
            }
        }

        if($class::$PREPENDS)
        {
            foreach($class::$PREPENDS as $pColumn => $pVal)
            {
                if(isset($result[$pColumn]))
                {
                    $result[$pColumn] = $pVal . $result[$pColumn];
                }
            }
        }

        return $result;
    }

    public static function findAll($cursor = 0, $limit = 10, $objectForm = false)
    {
        if($cursor) $cursorSQL = "AND videos.id < $cursor";
        else $cursorSQL = "";
        $query = " SELECT " .
                    self::BASIC_FIELDS . ",
                    users.username,
                    users.realName
                    FROM " . self::TABLE_NAME . "
                    JOIN users ON users.id = videos.userID
                    WHERE public = 1 AND mediaURL <> '' $cursorSQL
                    ORDER BY videos.id DESC
                    LIMIT $limit";


        return self::mapObject($query, $objectForm, true, null, null, null, self::$PREPENDS);
    }

    public static function findByUserFollows($userID, $cursor = 0, $limit = 35, $objectForm = false)
    {
        if($cursor) $cursorSQL = "AND videos.id < $cursor";
        else $cursorSQL = "";

        $query = "  SELECT " .
                        self::BASIC_FIELDS . ",
                        users.username,
                        users.realName
                    FROM " . self::TABLE_NAME . "
                    JOIN follows
                        ON followingID = videos.userID
                    JOIN users
                        ON followingID = users.id
                    WHERE followerID = $userID AND mediaURL <> '' $cursorSQL
                    ORDER BY videos.id DESC
                    LIMIT $limit
                    ";

        return self::mapObject($query, $objectForm, true, null, null, null, self::$PREPENDS);
    }

    public static function findByUser($userID, $cursor = 0, $limit = 35, $objectForm = false, &$totalFound = 0)
    {
        if($cursor) $cursorSQL = "AND videos.id < $cursor";
        else $cursorSQL = "";
        self::sanitizeArguments($userID);
        $query = "  SELECT " .
                    self::BASIC_FIELDS . "
                    FROM " . self::TABLE_NAME . "
                    WHERE userID = $userID AND mediaURL <> '' $cursorSQL
                    ORDER BY id DESC
                    LIMIT $limit";

        $result = self::mapObject($query, $objectForm, true, null, null, null, self::$PREPENDS);
        return $result;
    }

	public static function create($title, $description, $userID, $public, $mediaURL = null, $uploadDate = null)
	{

        if(!$uploadDate) $uploadDate = self::getFormattedDateTime(); //Now
        $title = urldecode($title);
        $description = urldecode($description);
        $kv = array("title" => $title, "description" => $description, "public" => $public, "userID" => $userID, "mediaURL" => $mediaURL, "uploadDate" => $uploadDate);
		$record = self::createRecord($kv);
        $user = new User($userID);
        if($user->startDate == "0000-00-00") $user->startDate = self::getFormattedDateTime();
        $user->videoCount++;
        return $record;
	}

    public function delete()
    {
        $class = get_called_class();
        $userID = $this->userID;
        if(!$this->id) throw new ArgumentException("Model ID required to delete");
        $query = "	DELETE FROM " . $class::TABLE_NAME . "
					WHERE id = $this->id";

        self::$database->db_query_error_check($query);
        $user = new User($userID);
        $user->videoCount--;
        return true;
    }


}