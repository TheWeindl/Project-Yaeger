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


var_dump(Database::execute("SELECT * FROM users"));