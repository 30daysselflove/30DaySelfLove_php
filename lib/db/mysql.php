<?php
namespace lib\db;

class mysql implements db {
	
	protected $connection;
	private static $instance;
	private static $dbserver;
	private static $dbuser;
	private static $dbpw;
	
	
	private function __construct(){
		return $this->connection = mysql_connect(self::$dbserver, self::$dbuser, self::$dbpw);
	}
	
	public static function start($dbserver, $dbuser, $dbpw) 
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
			self::$dbserver = $dbserver;
			self::$dbuser = $dbuser;
			self::$dbpw = $dbpw;
            self::$instance = new $c;
        }

        return self::$instance;
    }
	
	public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
	
	public function  db_select($dbname){
		return mysql_select_db($dbname, $this->connection);
	}
	
	public function db_query($query){
		return mysql_query($query,$this->connection);
	}
	
	public function  db_close(){
		return mysql_close($this->connection);
	}
	
	public function db_fetch_assoc($result){
		return mysql_fetch_assoc($result);
	}
	
	public function db_num_rows($result){
		return mysql_num_rows($result);
	}
	
	public function db_real_escape_string($query){
	
	}
	
}
