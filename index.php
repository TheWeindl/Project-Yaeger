<?php
session_start();

error_reporting(E_ALL);
require_once("config.php");
require_once("updateRessources.php");
require_once("registerUser.php");
require_once("page.php");
$maintenance = false;

if($maintenance == true)
{
    echo "This site is currently under maintenance.";
}
elseif($maintenance == false)
{
    getPage();
}
?>