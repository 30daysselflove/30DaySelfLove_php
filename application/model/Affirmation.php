<?php


namespace model;

use lib\UtilityFunctions;
use model\MySQLActiveRecord;
use lib\exception\DatabaseException;
use lib\exception\ArgumentException;
use lib\Session;


class Affirmation extends MySQLActiveRecord
{
	const BASIC_FIELDS = "id, backgroundImageURL, affirmation";
    const TABLE_NAME = "affirmations";
    const AFFIRMATION_TWITTER_SOURCE = "30daysselflove1";

    public static function updateFromTwitter()
    {
        $query = " SELECT last_update, highestID
                   FROM affirmations_last_twitter_update";
        $result = self::getSingleResult($query);
        $today = strtotime("today");
        //Run the twitter feed update once a day, so check if the last update is before the start of today
        if(!$result || strtotime($result['last_update']) < $today)
        {
            $newTweets = array();
            require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/twitter/twitteroauth/twitteroauth.php');
            $twitterApi = new \TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_KEY_SECRET);
            $params = array("screen_name" => self::AFFIRMATION_TWITTER_SOURCE);
            if($result['highestID']) $params['since_id'] = $result['highestID'];
            $res = $twitterApi->oAuthRequest("statuses/user_timeline", "GET", $params);

            $tweetData = json_decode($res);


            if($tweetData && !isset($tweetData->errors))
            {
                $statuses = $tweetData;
                $c = count($statuses) - 1;
                for($i = $c; $i >= 0; $i--)
                {
                    $newTweets[] = $statuses[$i];
                }

            }

            $highestNewTweetID = 0;
            if(count($newTweets))
            {
                foreach($newTweets as $newTweet)
                {
                    if($newTweet->id_str > $highestNewTweetID)
                    {
                        $highestNewTweetID = $newTweet->id_str;
                    }
                    $mediaURL = "";
                    $entities = $newTweet->entities;
                    if(isset($entities->media) && $entities->media && count($entities->media))
                    {
                        //Should be a photo, not sure whhy there would be more than 1 media, but lets not worry about that
                        $firstMedia = $entities->media[0];
                        $mediaURL = $firstMedia->media_url;
                    }
                    self::create($mediaURL, $newTweet->text);
                }
            }

            if($result)
            {
                $latestTweetTimeFormatted = self::getFormattedDateTime();
                $setString = "last_update = '$latestTweetTimeFormatted'";
                if($highestNewTweetID) $setString .= ", highestID=$highestNewTweetID";
                $query = "UPDATE affirmations_last_twitter_update
                                SET $setString";
                $result = self::$database->db_query_error_check($query);
            }
        }
    }

    public static function findAll($filter = null, $limit = 20, $offset = 0, $objectForm = false)
    {
            $query = "  SELECT " .
                    self::BASIC_FIELDS . "
                    FROM " . self::TABLE_NAME . "
                    ORDER BY id DESC
                    LIMIT $offset, $limit";


        return self::mapObject($query, $objectForm, true, null, null);
    }

	public static function create($backgroundImageURL, $affirmation)
	{
        $kv = array("backgroundImageURL" => $backgroundImageURL, "affirmation" => $affirmation);
		return self::createRecord($kv);
	}


}