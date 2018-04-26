<?php


namespace model;

use lib\UtilityFunctions;


use lib\standardObjects\GeoLocation;

use model\MySQLActiveRecord;

use model\interfaces\IMessagable;

use lib\exception\DatabaseException;

use lib\exception\ArgumentException;

use lib\exception\SecurityException;

use model\restaurant\restaurant;

use lib\oauth\OAuth2Session;

use Exception;

use lib\Session;


class LogRecord extends MySQLActiveRecord
{

    const TABLE_NAME = "log";
    const BASIC_FIELDS = "id, context, data, time";

    public static function create($context, $data)
    {
        $class = get_called_class();
        $data = json_encode($data);
        $time = self::getFormattedDateTime();
        $query = "  INSERT INTO " . $class::TABLE_NAME . "
                        (`context`, `data`, `time`)
                    VALUES
                        ('$context', '$data', '$time')";

        self::$database->db_query($query);
    }


}