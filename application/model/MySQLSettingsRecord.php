<?php

/**
 * MySQLSettingsRecord
 * Parent class for settings records - that is, tables that hole a single row of settings fields.
 */

namespace model;

use lib\UtilityFunctions;
use lib\Session;

use model\interfaces\ISerializable;


class MySQLSettingsRecord extends MySQLStaticModel implements ISerializable
{

    const BASIC_FIELDS = "";

    const TABLE_NAME = "";

    protected $_info;
    private $_changedData = array();

    protected function loadInfo()
    {
        if($this->_info) return;
        $class = get_called_class();
        $query = "SELECT " . $class::BASIC_FIELDS . "
											FROM " . $class::TABLE_NAME;

        $result = self::getSingleResult($query);

        $this->_info = $result;
    }

    public function __get($key)
    {
        $this->loadInfo();
        if(!isset($this->_info[$key])) return null;
        return $this->_info[$key];
    }

    public function __isset($key)
    {
        $this->loadInfo();
        return isset($this->_info[$key]);
    }

    public function serializableForm()
    {
        $this->loadInfo();
        return $this->_info;
    }

    public function __set($key, $value)
    {
        $this->loadInfo();
        $this->_info[$key] = $value;
        $this->_changedData[$key] = $value;

    }

    public function save()
    {
        if(!$this->_changedData) return;
        $class = get_called_class();
        $query = "	UPDATE " . $class::TABLE_NAME . "
					SET " . self::createUpdateSQLFromKeyValue($this->_changedData, array());

        self::$database->db_query_error_check($query);
    }


}