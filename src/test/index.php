<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 28.01.2018
 * Time: 17:31
 */

include "../classes/BaseClass.php";
include "../classes/InformationManager.php";
include "../classes/Building.php";
include "../classes/ProductionBuilding.php";
include "../classes/WoodProduction.php";
include "../classes/Database.php";
session_start();

$_SESSION["user"]["id"] = 1;
var_dump($_SESSION);

?>

<script src="../../frontend-test/assets/js/jquery-3.3.1.min.js"></script>
<script>
    $.ajax({
        url:"../classes/UserController.php",
        type:"GET",
        data:"getUserLayout",
        success: function(data){

        }
    });

    console.log("\n");

    $.ajax({
        url: "../classes/UserController.php",
        type: "POST",
        data: {"function": "updateUserLayout", "layout": JSON.stringify(["test"])},
        success: function(data) {
            console.log(data);
        }
    })
</script>
