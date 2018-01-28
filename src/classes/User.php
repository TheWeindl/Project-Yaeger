<?php

include_once "BaseClass.php";

class User extends BaseClass {

    const DATABASE_TABLE = "users";

    protected $_fields = [
        "id" => "",
        "name" => "",
        "password" => "",
        "email" => "",
        "layout" => "",
        "last_login" => "",
        "last_updated" => ""
    ];

    public $id;
    public $name;
    public $password;
    public $email;
    public $layout;
    public $last_login;
    public $last_updated;

    public static function login($username, $password) {
        $db = new Database();
        $nameResults = $db->getTableDataFlat(self::DATABASE_TABLE, "name", "name = " . $username);

        if(!empty($nameResults)) {

            $password = password_hash($password, PASSWORD_BCRYPT);

            $finalRes = $db->getTableDataFlat(self::DATABASE_TABLE,"id, password, layout", "name = ".$username." AND password = ".$password);

            if(!empty($finalRes)) {

                // login user

                $_SESSION["user"] = array(
                    "id" => $finalRes["id"],
                    "name" => $finalRes["name"],
                    "layout" => $finalRes["layout"],
                );
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

        $user = new User();
        $fields = array();
        $fields["name"] = $username;
        $fields["password"] = password_hash($password, PASSWORD_BCRYPT);
        $fields["email"] = $email;
        $fields["layout"] = LAYOUT;
        $user->setFields($fields)->save();

        $user->login($username, $password);


    }
}