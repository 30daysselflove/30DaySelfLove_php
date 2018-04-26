<?php
namespace model;

class CronInfo extends MySQLStaticModel {
	public static $CRON_INFO_TABLE = "cron_info";
	
	protected $_cronInfo = null;
	
	protected function loadCronInfo()
	{
		if($this->_cronInfo) return;
		$result = self::getSingleResult("	SELECT info
											FROM " . self::$CRON_INFO_TABLE);
		
		$this->_cronInfo = (array) json_decode($result['info']); 
	}
	
	public function __get($key)
	{
		$this->loadCronInfo();
		if(!isset($this->_cronInfo[$key])) return null;
		return $this->_cronInfo[$key];
	}
		
	public function __isset($key)
	{
		$this->loadCronInfo();
		return isset($this->_cronInfo[$key]);
	}
	
	public function __set($key, $value)
	{
		$this->loadCronInfo();
		$this->_cronInfo[$key] = $value;
	}
	
	public function save()
	{
		if(!$this->_cronInfo) return;
		$query = "	UPDATE " . self::$CRON_INFO_TABLE . "
					SET info='" . json_encode($this->_cronInfo) . "'";
		self::$database->db_query_error_check($query);
	}
	
}

