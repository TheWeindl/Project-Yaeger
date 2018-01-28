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
}