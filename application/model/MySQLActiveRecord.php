<?php
/**
 * MySQLActiveRecord
 * 
 * Base class for all Active Record objects
 * 
 * @author Adam Dougherty
 *
 */

namespace model;
use lib\exception\ArgumentException;

use lib\exception\DatabaseException;

use model\interfaces\ISerializable;

use model\MySQLStaticModel;

use lib\InvalidationData;

use model\MySQLModel;

abstract class MySQLActiveRecord extends MySQLStaticModel implements ISerializable, \JsonSerializable
{
	const PARENT_TYPE_SINGLE 	= 1;
	const PARENT_TYPE_MULTI 	= 2;
	const PARENT_TYPE_LINKED 	= 3;


    static $JSON_COLUMNS = null;
	protected static $dataObjectPool = array();
	protected $cache;
	protected $dataObject = array();
	protected $changedKeys;

	public $id;
	public $owner;
	protected $dataObjectFilled = false;
	//protected $TABLE_NAME;
	//protected $BASIC_FIELDS;
	
	private static $reflectedConstants = array();
	

	public function myNamespace()
	{
		
	}

    protected static function createRecord($keyValueArray)
    {

        $disallowedFields = array("id");
        $class = get_called_class();

        $query = "  INSERT INTO " . $class::TABLE_NAME . " " .
            self::createInsertSQLFromKeyValue($keyValueArray, $disallowedFields);

        $result = self::$database->db_query($query);

        if(!$result) throw new DatabaseException(self::$database->db_error(), 0, $query);
        $id = self::$database->last_insert_id();
        return new $class($id, $keyValueArray);
    }
	
	/**
	 * Converts a query's result into a customized object
	 * 
	 * @param string $query The database query to be executed whose result wil be mapped
	 * @param bool $objectForm Return the result as the calling PHP class object or as an array
	 * @param bool $singleElementAsArray Indicates whether or not to return a single element wrapped in an array
	 * @param array $concatGroupsColumns An array of named GROUP_CONCATS to be converted into arrays, expects the seperator to be a pipe: |
	 * @param array $concatGroupKeys An 2-dimensional array, with the first dimension definining the named group (from the preceeding argument) and the second dimension providng an array of keys
	 * @param array $decodeColumns An array of columns to json_decode
	 * @return mixed mapped object
	 */
	
	public static function mapObject($query, $objectForm = true, $singleElementAsArray = false, $concatGroupsColumns = null, $concatGroupKeys = null, $decodeColumns = null, $prepends = null)
	{
		$db = self::$database;
	
		$class = get_called_class();
		$objectArray = array();
		$result = $db->db_query_error_check($query);
		
		$concatGroupCount = count($concatGroupsColumns);
		$decodeColumnCount = count($decodeColumns);
		
		while($row = $db->db_fetch_assoc($result))
		{
			$dataObject = $row;
			
			//Concat group processing (i.e. GROUP_CONCAT(id, , "|",  email, "|", firstName)
			for ($i = 0; $i < $concatGroupCount; $i++) {
				$concatGroup = $concatGroupsColumns[$i];
				if(isset($row[$concatGroup]))
				{
					$groupData = array();		
					$outerArray = explode(",", $row[$concatGroup]);
					//Iterate over the outer array for inner array (using the "|" pipe) splitting and processing
					foreach ($outerArray as $outerElement)
					{
						//Check for and apply concatGroup keys
						$innerArray = explode("|", $outerElement);
						if(count($innerArray) > 1 && isset($concatGroupKeys[$concatGroup]))
						{
							$innerArray = array_combine($concatGroupKeys[$concatGroup], $innerArray);
						}
					
						$groupData[] = $innerArray;
					}
					
					$dataObject[$concatGroup] = $groupData;
				}
			}

            if($prepends)
            {
                foreach($prepends as $pColumn => $pVal)
                {
                    if(isset($dataObject[$pColumn]))
                    {
                        $dataObject[$pColumn] = $pVal . $dataObject[$pColumn];
                    }
                }
            }
			
			//Decode Column Processing
			for ($i = 0; $i < $decodeColumnCount; $i++) {
				$decodeColumn = $decodeColumns[$i];
				if(isset($row[$decodeColumn]))
				{
                    $decodedData = json_decode($row[$decodeColumn]);
                    if(!$decodedData) $decodedData = $row[$decodeColumn];
					$dataObject[$decodeColumn] = $decodedData;
				}
			}

			if($objectForm)
			{
				unset($dataObject['id']);
				$objectArray[] = new $class($row['id'], $dataObject);
			}
			else
			{
				$objectArray[] = $dataObject;
			}
				
		}

		if(count($objectArray) == 1 && !$singleElementAsArray) return $objectArray[0];
		return $objectArray;
	}
	
	protected static function convertSearchDataToSQL($searchData)
	{
	
		$sql = "";
		$sqlSep = "";
		foreach($searchData as $searchDataKey => $searchDataParam)
		{
			
			$sql .= $sqlSep .  $searchDataKey . "=$searchDataParam";
			$sqlSep = " AND ";
		}
		
		return $sql;
	}
	
	public function __construct($id = null, $injectableDataObject = null)
	{

		//parent::__construct();
		
		//$this->owner = $owner;
		
		//if(!$this->BASIC_FIELDS || !$this->TABLE_NAME) throw new \Exception("BASIC_FIELDS AND TABLE_NAME are abstract properties that must be set by a AR subclass");

        if($id)
        {

            $this->id = self::$database->db_real_escape_string($id);

            if(!$injectableDataObject)
            {

                $dataObjectKey = $this->getClassName() . "_" . $this->id;
                if(isset(self::$dataObjectPool[$dataObjectKey]) && !empty(self::$dataObjectPool[$dataObjectKey]))
                {

                    $this->injectDataObject(self::$dataObjectPool[$dataObjectKey]);
                }
            }else $this->injectDataObject($injectableDataObject);

        }
	}
	
	public function generateCacheKey($id, $method, $args = null)
	{
		$argString = "";
		if(is_array($id)) $id = implode("-", $id);
		if($args)
		{
			foreach($args as $arg)
			{
				
				if(is_array($arg)) $argString .=  "_" . implode("-", $arg);
				else $argString .= "_" . $arg;
			}
		}
		return $this->getClassName() . "_" . $method . "_" . $id . $argString;
	}
	
	public function injectDataObject($dataObj)
	{
		$this->dataObjectFilled = true;
		$this->dataObject = $dataObj;
	}
	
	public function injectSet($key, $value)
	{
		$this->dataObject[$key] = $value;
	}

    /**
     * Resyncs the entire object with its backing data store
     */
    public function resync($lazy = false)
    {
        $this->dataObjectFilled = false;
        $this->dataObject = array();
        //If its a lazy resync, we dont actually fill the object until a property request is made
        if(!$lazy) $this->fillDataObject();
    }

	public function __get($key)
	{
		if(!isset($this->dataObject[$key])) $this->fillDataObject();
		return $this->dataObject[$key];
	}
	
	protected function fillDataObject()
	{
		if($this->dataObjectFilled) return;
		$newObj = $this->get();
        if(!$newObj) throw new ArgumentException("Object with provided ID does not exist");

		$this->dataObjectFilled = true;
		if($newObj)
		{
			foreach($newObj as $propKey => $propVal)
			{
				if(!isset($this->dataObject[$propKey])) $this->dataObject[$propKey] = $propVal;
			}
		}
		$dataObjectKey = $this->getClassName() . "_" . $this->id;
		
		
		self::$dataObjectPool[$dataObjectKey] = &$this->dataObject;
		
	}
	
	public function __set($key, $value)
	{

		//if(isset($this->dataObject[$key]) && $this->dataObject[$key] != $value) return;
		if(!$this->changedKeys) $this->changedKeys = array();
		
		$this->changedKeys[$key] = $value;
		$this->dataObject[$key] = $value;
		
	
	}
	protected function clearChangedKeys()
	{
		 $this->changedKeys = null;
	}

	public function __isset($key)
	{
		$this->fillDataObject();
		
		return isset($this->dataObject[$key]);
	}
	
	public function getClassName()
	{
		$class = get_class($this);
		$classParts = explode("\\", $class);
		return end($classParts);
	}
	
	protected function invalidateMe()
	{
		if(!$this->cache) $this->cache = CacheCentralStation::getCacheByConnectionID();
		$namespace = "r_" . $this->owner[0];
		$this->cache->invalidate($namespace);
	}
	
	public function __destruct()
	{
		$this->save();
	}
	
	public function save()
	{
		if(isset($this->changedKeys) && count($this->changedKeys)) $this->set($this->changedKeys);
		$this->changedKeys = null;
	}
	
	
	public function __sleep()
	{
		if(!$this->dataObject) $this->fillDataObject();
		return array("id", "owner", "dataObject", "changedKeys", "dataObjectFilled");
	}
	
	public function __wakeup()
	{
		$dataObjectKey = $this->getClassName() . "_" . $this->id;
		
		if(!isset(self::$dataObjectPool[$dataObjectKey])) self::$dataObjectPool[$dataObjectKey] = &$this->dataObject;
		
	}
	public function jsonSerialize()
	{
		return $this->serializableForm();
	}
	
	public function serializableForm()
	{
		$this->fillDataObject();
		$serializableArray = $this->dataObject;
		$serializableArray["id"] = $this->id;
		return $serializableArray;
	}
	
	
	protected function get()
	{
		if(!$this->id) throw new ArgumentException("A model ID is required for all basic getter/setter functions");
		$class = get_called_class();
		$query = "	SELECT " . $class::BASIC_FIELDS . "
					FROM " . $class::TABLE_NAME . "
					WHERE id = $this->id";
		
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
	
	public function set($keyValueArray, $disallowedFields = null)
	{

        foreach($keyValueArray as $key => &$value)
        {
            if(method_exists($this, "_set_$key"))
            {
                $r = call_user_func_array(array($this, "_set_$key"), array(&$value, &$keyValueArray));

            }
        }

		if(!$this->id) throw new ArgumentException("A model ID is required for all basic getter/setter functions");
		$disallowedFields = !$disallowedFields ? array("id") : $disallowedFields;
		$class = get_called_class();
		$query = "	UPDATE " . $class::TABLE_NAME . "
					SET " . self::createUpdateSQLFromKeyValue($keyValueArray, $disallowedFields) . "
					WHERE id=$this->id";
		
		$result = self::$database->db_query($query);
		if(!$result) throw new DatabaseException(self::$database->db_error());
		
		return $result;
	}
	
	public function delete()
	{
		$class = get_called_class();

		if(!$this->id) throw new ArgumentException("Model ID required to delete");
		$query = "	DELETE FROM " . $class::TABLE_NAME . "
					WHERE id = $this->id";

		self::$database->db_query_error_check($query);
	}

	
}