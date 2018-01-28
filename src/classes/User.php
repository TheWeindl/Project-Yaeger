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
        $nameResults = $db->getTableData("name", self::DATABASE_TABLE, "name = " . $username);

        if(!empty($nameResults)) {

            $password = password_hash($password, PASSWORD_BCRYPT);

            $finalRes = $db->getTableData("password", self::DATABASE_TABLE, "name = ".$username." AND password = ".$password);

            if(!empty($finalRes)) {

                // login user

                // $_SESSION["user"] = new User()

            } else {
                return false;
            }

        } else {
            return false;
        }
    }
}