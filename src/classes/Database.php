<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 22.01.2018
 * Time: 20:59
 */

include_once("../config/config.php");

class Database
{
    public static function execute($sql) {
        $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE_NAME);

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $result = $mysqli->query($sql);
        $results = array();

        while ($row = $result->fetch_assoc()) {
            array_push($results, $row);
        }

        $mysqli->close();
        return $results;
    }

    public static function getInstance() {
        return new Database();
    }

    public function insertTableData($table, $fields) {
        if (sizeof($fields) < 1) {
            throw new Exception("No data defined");
        }
        $thisQuery = microtime(true);
        isset($fields['id']) ? $customId = $fields['id'] : $customId = 0;
        array_walk($fields, create_function('&$v, $k', 'if (is_null($v)) $v = "null"; else if ($v === false) $v = 0; else if ($v === true) $v = 1; else $v = "\'".$v."\'"; '));
        $sql = "insert into $table (".implode(",", array_keys($fields)).") values (".implode(",", ($fields)).")";
        $result = $this->db->Execute($sql);
        if ($result) {
            if (!$customId) {
                // $id = $this->db->Insert_ID();
            } else {
                $id = $customId;
            }
            if ($id == 0) {
                return true;
            } else {
                return $id;
            }
        } else {
            return false;
        }
    }

    public function updateTableData($table, $fields, $where = "") {
        if (sizeof($fields) < 1) {
            throw new Exception("No data defined");
        }
        $thisQuery = microtime(true);
        $fields = $this->addSlashes($fields);
        //array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; $v=$k."=".$v;'));
        array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; else if ($v === false) $v = 0; else if ($v === true) $v = 1; $v=$k."=".$v;'));
        $sql    = "update $table set ".implode(",", $fields);
        if ($where) {
            $sql.=" where ".$where;
        }
        $result = Database::execute($sql);
        return $result;
    }
    public function deleteTableData($table, $where="") {
        $thisQuery = microtime(true);
        $sql = "DELETE FROM ".$table;
        if ($where != "") {
            $sql .= " WHERE ".$where;
        }
        $result = Database::execute($sql);
        return $result;
    }

    public function addSlashes($param, $checkGpc = true) {
        if (is_array($param)) {
            array_walk_recursive($param, function (&$v, $k) {
                is_string($v) ? $v = addslashes($v) : null;
            });   //We put the check here because addslashes returns string, thus destroying the real data type
            return $param;
        } else {
            return addslashes($param);
        }
    }


    // assuption that everything is a string
    public function getTableData($table, $fields = "*", $where = "", $group = null) {
        $array = array();
        if(gettype($fields) === gettype($array)) {
            // case array

        } else {
            // case string
        }

        $sql = "SELECT ".$fields." FROM ".$table." WHERE ".$where;
        if(isset($group)) {
            $sql .= " GROUP BY ". $group;
        }

        return self::execute($sql);
    }

    public function getTableDataFlat($table, $fields = "*", $where = "", $group = "") {
        return self::getTableData($table, $fields, $where, $group)[0];
    }
}