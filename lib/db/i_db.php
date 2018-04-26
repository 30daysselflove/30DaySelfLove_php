<?php
namespace lib\db;
interface i_db
{
    public static function start($dbserver, $dbuser, $dbpw);
    public function db_query($query);
    public function db_close();
} 