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

        $results = $mysqli->query($sql);

        $mysqli->close();
        return $results;
    }

    public static function getInstance() {
        return new Database();
    }


    // assuption that everything is a string
    public function getTableData($fields, $table, $where = "", $group = "") {
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
}