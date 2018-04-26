<?php
namespace lib;
use model\LogRecord;

class Logger {

    const LOG_TYPE_DB_RECORD = 1;
    const LOG_TYPE_FILE = 2;
    public static $logName = "log";
    public static $logType = self::LOG_TYPE_DB_RECORD;

    public static function log($context, $data)
    {
        if(self::$logType == self::LOG_TYPE_DB_RECORD)
        {
            if(self::$logName == "log")
            {
                $logClass = "model\\LogRecord";
            }
            else
            {
                $logClass = "model\\LogRecord" . ucfirst(self::$logName);
            }

            $log = $logClass::create($context, $data);
        }
        else
        {
            //TODO: File log code
        }

    }
}