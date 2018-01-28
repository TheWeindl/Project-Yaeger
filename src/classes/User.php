<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 28.01.2018
 * Time: 16:26
 */

class User extends BaseClass {

    const DATABASE_TABLE = "users";

    public $fields = [
        "id" => "",
        "name" => "",
        "password" => "",
        "email" => "",
        "layout" => "",
        "last_login" => ""
    ];

    public $id;
    public $name;
    public $password;
    public $layout;

    public static function login($username, $password) {
        $db = new Database();
        $nameResults = $db->getTableDataFlat(self::DATABASE_TABLE, "name", "name = " . $username);

        if(!empty($nameResults)) {

            $password = password_hash($password, PASSWORD_BCRYPT);

            $finalRes = $db->getTableDataFlat(self::DATABASE_TABLE,"id, password", "name = ".$username." AND password = ".$password);

            if(!empty($finalRes)) {

                // login user

                $_SESSION["user"] = new User($finalRes["id"]);
                return true;

            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public static function signup($username, $password, $email) {
        $db = new Database();
        $results = $db->getTableData(User::DATABASE_TABLE, "*", "name = " . $username);

        if(!empty($results)) {
            throw new Exception("Username already exists! Please try a different one.");
        }

        $results = $db->getTableData(User::DATABASE_TABLE, "*", "email = " . $email);

        if(!empty($results)) {
            throw new Exception("Email already exists! Try logging in.");
        }



    }
}