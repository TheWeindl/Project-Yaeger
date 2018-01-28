<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 28.01.2018
 * Time: 17:25
 */

abstract class BaseClass {

    public function __construct($id = null) {
        if (!is_null($id)) {
            $this->init($id);
        }
    }

    public function init($id) {
        $result = Database::getInstance()->getTableData(static::DATABASE_TABLE, "*", "id={$id}");
        if (empty($result)) {
            throw new Exception("Item with id {$id} does not exist");
        } else {
            foreach ($result[0] as $key => $value) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    /**
     * Useful if we already have the database data for this object, so that we don't have to query again
     * @return self
     */
    public function setFields(array $fields) {
        foreach ($this->_fields as $db_field => $characteristics) {
            if (array_key_exists($db_field, $fields)) {
                $this->set($db_field, $fields[$db_field], $characteristics['type']);
            }
        }
        return $this;
    }
    /**
     * Useful for returning the whole object's database properties at once (for example, when populating a form)
     */
    public function getFields() {
        $fields = array();
        foreach (array_keys($this->_fields) as $db_field) {
            $fields[$db_field] = $this->{$db_field};
        }
        return $fields;
    }
    public function getTypesOfFields() {
        return $this->_fields;
    }
    public function save() {
        if ($this->id && !$this->_must_persist) {		//if this is an existing entity and nothing's changed, do nothinge
            return $this;
        }
        $this->setLastUpdate(false);
        $db = Database::getInstance();
        $fields = $this->getFields();
        if (!$this->id) {
            $this->id = $db->insertTableData(static::DATABASE_TABLE, $fields);
        } else {
            $db->updateTableData(static::DATABASE_TABLE, $fields, "id={$this->id}");
            if (self::$use_cache) {
                Cache::getInstance()->deleteCache($this->_cache_prefix.$this->id);
            }
        }
        Cache::getInstance()->deleteCache(static::CACHE_KEY);	//this deletes any associated cache entries
        $this->_must_persist = false;
        return $this;
    }

}