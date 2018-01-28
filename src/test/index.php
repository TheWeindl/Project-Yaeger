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

echo "test";

?>

<script src="../../frontend-test/assets/js/jquery-3.3.1.min.js"></script>
<script>
    $.ajax({
        url:"../classes/UserController.php",
        type:"GET",
        data:"getUserLayout",
        success: function(data){
            console.log(data);
        }
    });
</script>
