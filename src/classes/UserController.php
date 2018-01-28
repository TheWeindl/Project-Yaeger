<?php

include_once "User.php";
include_once "Database.php";

if(isset($_GET["getUserLayout"])){
    $user = new User($_SESSION["user"]["id"]);
    echo  json_encode(unserialize($user->layout));
}

if($_POST["function"] == "updateUserLayout"){
    $user = new User($_SESSION["user"]["id"]);
    $layout = serialize(json_decode($_POST["layout"]));
    try{
        $user->setFields(["layout" => $layout])->save();
    }catch (Exception $e){
        throw $e;
    }
}
