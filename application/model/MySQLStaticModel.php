<?php
namespace model;

use lib\db\mysqli;


use lib\exception\ArgumentException;

use lib\exception\DatabaseException;


abstract class MySQLStaticModel extends Model
{
	const SANITIZE_NUMERIC 	= 1;
	const SANITIZE_STRING 	= 2;
	
	/**
	 * @var core\db\mysqli
	 */
	protected static $database;

	public static function init()
	{
		if(self::$database) return;
		self::$database = mysqli::start(DB_SERVER, DB_USER, DB_PW);
		self::$database->db_select(DB_NAME);
	}
	
	/**
	 * Get the current date/time in a commonly supported format suitable by MySQL DATETIME fields
	 * @return string
	 */
	
	public static function getFormattedDateTime($timeStamp = null)
	{
		$timeStamp = $timeStamp ? $timeStamp : time();
		return date("Y-m-d H:i:s", $timeStamp);
	}
	
	
	/**
	 * Quick shortcut function for getting single result expected by the caller from with the provided query
	 * 
	 * @param string $query
	 * @param bool $emptyAllowed Do not throw an expeception of there's no records
	 * @throws DatabaseException
	 * @throws ArgumentException
	 * @return NULL|array
	 */
	protected static function getSingleResult($query, $emptyAllowed = true)
	{
		$result = self::$database->db_query($query);
		
		if(!$result) 
		{
			 throw new DatabaseException(self::$database->db_error());
		}
	
		$rows = self::$database->db_num_rows($result);
		
		if($rows < 1)
		{
			if(!$emptyAllowed) throw new ArgumentException("Object Not Found");
			return null;
		}
		
		
		
		$row = self::$database->db_fetch_assoc($result);
		
		
		return $row;
	}
	
	/**
	 * Quick shortcut function for getting an array of records with a query
	 * 
	 * @param string $query
	 * @param bool $emptyAllowed Do not throw an expeception of there's no records
	 * @throws DatabaseException
	 * @throws ArgumentException
	 * @return array
	 */
	
	protected static function getResult($query, $emptyAllowed = true)
	{	
		$result = self::$database->db_query($query);
		
		if(!$result) 
		{
			throw new DatabaseException(self::$database->db_error($result),0);
		}
		
		$rows = self::$database->db_num_rows($result);
		
		if($rows < 1)
		{
			if(!$emptyAllowed) throw new ArgumentException("Object Not Found");
			return array();
		}
		
		$resultArray = array();
		while($row = self::$database->db_fetch_assoc($result))
		{	
			$resultArray[] = $row;
		}
		
		return $resultArray;
	}
	
	/**
	 * Quick shortcut function for getting an associative array of records with a query. 
	 * Uses the values from a a specified column as the primary key in the array e.g. a users query where you want the id's to be the keys 
	 * 
	 * @param string $query
	 * @param string $keyColumn the column whose value to use as the key 
	 * @param bool $emptyAllowed
	 * @throws DatabaseException
	 * @throws ArgumentException
	 * @returns array
	 */
	
	protected static function getAssociativeResult($query, $keyColumn, $emptyAllowed = true)
	{
		$result = self::$database->db_query($query);
	
		if(!$result)
		{
			throw new DatabaseException(self::$database->db_error($result),0);
		}
	
		$rows = self::$database->db_num_rows($result);
	
		if($rows < 1)
		{
			if(!$emptyAllowed) throw new ArgumentException("Object Not Found");
			return array();
		}
	
		$resultArray = array();
		while($row = self::$database->db_fetch_assoc($result))
		{
			$resultArray[$row[$keyColumn]] = $row;
		}
	
		return $resultArray;
	}
	
	
	protected static function checkFields($keyValueArray, $disallowedkeys)
	{
		if($disallowedkeys == null) return true;
		foreach($disallowedkeys as $disKey)
		{
			if(isset($keyValueArray[$disKey])) return $disKey;
		}
		
		return true;
	}
	
	/**
	 * Shortcut function for generating update SQL from a key-value array
	 * 
	 * @param array $keyValueArray
	 * @param array $disallowedkeys An array of keys that should not be allowed in the update, Simple and quick security feature
	 * @throws ArgumentException
	 * @returns string
	 */
	
	protected static function createUpdateSQLFromKeyValue($keyValueArray, $disallowedkeys = null)
	{
		$disKey = self::checkFields($keyValueArray, $disallowedkeys);
		if($disKey !== true) throw new ArgumentException("You cannot manually set the field: " . $disKey);
		$updateSQL = "";
		$i = 0;
		$sepString = "";
		
		foreach ($keyValueArray as $key => $value)
		{ 


			$boolCheck = strtolower($value);
			if($boolCheck == 'true' || $boolCheck == 'false') $value = $boolCheck == 'true' ? 1 : 0;
			else $value = is_numeric($value) ? $value : "'" .  self::$database->db_real_escape_string($value) . "'";

			$updateSQL .= $sepString . "`" . self::$database->db_real_escape_string($key) . "`" . "=" . $value;
			$sepString = ",";
			$i++;
		}
		
		return $updateSQL;
	}
	
	/**
	 * Shortcut function for generating insert SQL from a key-value array
	 *
	 * @param array $keyValueArray
	 * @param array $disallowedkeys An array of keys that should not be allowed in the insert, Simple and quick security feature
	 * @throws ArgumentException
	 * @returns string
	 */
	
	protected static function createInsertSQLFromKeyValue($keyValueArray, $disallowedkeys = null)
	{
		
		$disKey = self::checkFields($keyValueArray, $disallowedkeys);
		if($disKey !== true) throw new ArgumentException("You cannot manually set the field: " . $disKey);
		$keys = "";
		$values = "";
		
		$sepString = "";
		foreach ($keyValueArray as $key => $value)
		{ 
			$boolCheck = strtolower($value);
			if($boolCheck == 'true' || $boolCheck == 'false') $value = $boolCheck == 'true' ? 1 : 0;
			else $value = is_numeric($value) ? $value : "'" .  self::$database->db_real_escape_string($value) . "'";
			$keys .= $sepString .  self::$database->db_real_escape_string($key);
			$values .= $sepString . $value;
			$sepString = ",";
		}
		
		$sql = "($keys) VALUES ($values)";
		return $sql;
	}
	
	protected static function createSQLFromCommaString()
	{
		$argsArray = func_get_args();		
		$argsArraysArray = array();
		$count = 0;
		foreach ($argsArray as $args)
		{
			$argArray = explode(",", $args);
			$argArraysArray[] = $argArray;
			$curCount = count($argArray);
			if($count < $curCount) $count = $curCount;
		}
		
		$valueString = "";
		$outerSepString = "";
		
		for ($i = 0; $i < $count; $i++) {
		
			$valueString .= "$outerSepString(";
			$sepString = "";

			foreach ($argArraysArray as $argValues)
			{
				if(count($argValues) < $count) $value = $argValues[0];
				else $value = $argValues[i];
				if(is_numeric($value)) $valueString .= "$sepString$value";
				else $valueString .= "$sepString'$value'";
				$sepString = ",";
			}
			
			$valueString .= ")";
			$outerSepString  = ",";
		}
		
		return $valueString;
	}
	
	/**
	 * Takes a single referenced var or an array of referenced vars for database sanitation (i.e. mysql_real_escape_string)
	 * 
	 * @param mixed $args a single var or an array of referenced vars (ARRAYS MUST BE: array(&$var1, &$var2))
	 * @param int $sanitzer If SANITIZE_NUMERIC, any arg value that is not an number will result in an exception for security purposes
	 * @throws ArgumentException
	 * @returns bool
	 */
	
	public static function sanitizeArguments(&$args, $sanitzer = self::SANITIZE_NUMERIC)
	{
		$db = self::$database;
		if(is_array($args))
		{
			foreach($args as &$arg)
			{
				if($sanitzer == 1)
				{
					if(!is_numeric($arg)) throw new ArgumentException("The arguments you provided were not applicable in this function");
				}
				else
				{
					$arg = $db->db_real_escape_string($arg);
				}
			}
		}
		else
		{
			if($sanitzer == 1)
			{
				if(!is_numeric($args)) throw new ArgumentException("The arguments you provided were not applicable in this function");
			}
			else
			{
				$args = $db->db_real_escape_string($args);
			
			}
		}
		
		return true;
	}
	
}
MySQLStaticModel::init();