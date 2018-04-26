<?php

namespace lib\db;

use lib\exception\DatabaseException;


class mysqli implements i_db {

	protected $connection;
	protected $db;
	private static $instance;

	
	
	
	private function __construct($dbServer, $dbUser, $dbPw){

		$this->connection = mysqli_init();
        $result = mysqli_real_connect($this->connection, $dbServer,$dbUser,$dbPw);
        mysqli_set_charset($this->connection, "utf8");
		return $result;

	}
	
	/*
    * @return __CLASS__
    */
	public static function start($dbserver, $dbuser, $dbpw) 
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c($dbserver, $dbuser, $dbpw);
        }
		
        return self::$instance;
    }
	
	public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
	
	public function  db_select($dbname){
		if($this->db == $dbname)return true;
		if(mysqli_select_db($this->connection, $dbname)){
			$this->db = $dbname;
			return true;
		}else{
			return false;
		}
	}
	
	public function db_query($query){
		/*if(isset(ModelObject::$memcache))
		{
			ModelObject::$memcache->cacheDebug("<font color='ff0000'><b>Database call</b></font>", 1);
			ModelObject::$memcache->cacheDebug("", 2);
		}*/
		
		return mysqli_query($this->connection, $query);
	}
	
	public function db_query_error_check($query)
	{
		$result = mysqli_query($this->connection, $query);
		if(!$result) throw new DatabaseException($this->db_error());
		return $result;
	}
	
	public function db_multi_query($query)
	{
		return mysqli_multi_query($this->connection, $query);
	}
	
	public function db_store_result()
	{
		return mysqli_store_result($this->connection);
	}
	
	public function db_more_results()
	{
		return mysqli_more_results($this->connection);
	}
	
	public function db_next_result()
	{
		return mysqli_next_result($this->connection);
	}
	
	public function  db_close(){
		return mysqli_close($this->connection);
	}
	
	public function db_fetch_assoc($result){
		return mysqli_fetch_assoc($result);
	}
	
	public function db_fetch_object($result){
		return mysqli_fetch_object($result);
	}
	
	public function db_fetch_row($result){
		return mysqli_fetch_row($result);
	}
	
	public function db_fetch_field($result)
	{
		return mysqli_fetch_field($result);
	}
	public function db_fetch_array($result){
		return mysqli_fetch_array($result);
	}
	
	public function db_num_rows($result){
		return mysqli_num_rows($result);
	}
	
	public function db_real_escape_string($string){
		return mysqli_real_escape_string($this->connection, $string);
	}
	
	public function db_error(){
		return mysqli_error($this->connection);
	}
	
	public function db_errno(){
		return mysqli_errno($this->connection);
	}
	
	public function db_get_warnings(){
		return mysqli_get_warnings($this->connection);
	}
	
	public function db_warning_count(){
		return mysqli_warning_count($this->connection);
	}
	
	public function db_free_result($result){
		return mysqli_free_result($result);
	}
	
	public function db_data_seek($result, $offset){
		return mysqli_data_seek($result, $offset);
	}
	
	public function db_affected_rows()
	{
		return mysqli_affected_rows($this->connection);
	}
	
	public function last_insert_id(){
		return mysqli_insert_id($this->connection);
	}
	
	public function prepare($query)
	{
		return mysqli_prepare($this->connection, $query);
	}
	
	public function more_results()
	{
	    return mysqli_more_results($this->connection);
	}
	
	
}