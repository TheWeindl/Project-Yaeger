<?php
Require_once('config.php');
Require_once('buildingsSpecs.php');

//Updates the resources a player has on his account based on the server time
//Calculation unit are minutes
function UpdateRessources(){

    global $woodFactoryProduction;
    global $stoneFactoryProduction;
    global $metalFactoryProduction;
    global $farmProduction;

    $newResPerMin = 10;     //Resources gained every minute -> should later be read out of a table

    //Connect to the database
    if(!$oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)){
        echo("Could not connect to database");
    }

    $levels = GetFactoryLevels($oMysqli);

    //Get a timestamp from when the user last refreshed the page
    $Result = $oMysqli->query("SELECT lastrefresh FROM userinfo WHERE userID = {$_SESSION['userID']}");

    //If an database entry was returned, calculate the passed seconds from the timestamp till now
    if($Result && $Result->num_rows > 0){
        $lastRefresh = mysqli_fetch_assoc($Result);

        $currentRefresh = date("Y-m-d H:i:s");

        $timeFirst  = strtotime($currentRefresh);
        $timeSecond = strtotime($lastRefresh["lastrefresh"]);
        $differenceInMinutes = ($timeFirst - $timeSecond ) / 60;

        //If time delta is bigger than 1 minute and storage is available the update resources
        if($differenceInMinutes >= 1 && StorageAvailable($oMysqli)){

            //Get the current resource values and set the new ones
            GetResources($oMysqli, $wood, $stone, $metal, $people);
            SetResources($oMysqli,
                $wood + $woodFactoryProduction[(int)$levels["woodFactory"]] * $differenceInMinutes,
                $stone + $stoneFactoryProduction[(int)$levels["stoneFactory"]] * $differenceInMinutes,
                $metal + $metalFactoryProduction[(int)$levels["metalFactory"]] * $differenceInMinutes,
                $people + $farmProduction[(int)$levels["farm"]] * $differenceInMinutes);

            //Set resources to the current session
            SetResourcesToSession($wood + $woodFactoryProduction[(int)$levels["woodFactory"]] * $differenceInMinutes,
                $stone + $stoneFactoryProduction[(int)$levels["stoneFactory"]] * $differenceInMinutes,
                $metal + $metalFactoryProduction[(int)$levels["metalFactory"]] * $differenceInMinutes,
                $people + $farmProduction[(int)$levels["farm"]] * $differenceInMinutes);

            //Update the timestamp
            SetNewTimestamp($oMysqli, $currentRefresh);
        }

        mysqli_close($oMysqli);

        //Return the passed amount of time in minutes
        return '
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" id="progressBarMetal" role="progressbar" aria-valuenow="'. $differenceInMinutes*100 .'"
                 aria-valuemin="0" aria-valuemax="100" style="width:'.$differenceInMinutes*100 . '%">
                <span></span>
            </div>
        </div>';
    }
    else
    {
       echo("Database query went wrong");
    }

    mysqli_close($oMysqli);
}

//Sets the given timestamp as the last refreshed timestamp in the database
function SetNewTimestamp($oMysqli, $currentTimestamp){

    $oMysqli->query("UPDATE userinfo SET lastrefresh='$currentTimestamp' WHERE userID = {$_SESSION["userID"]}");
}

//Gets the resources of of the user
function GetResources($oMysqli, &$wood, &$stone, &$metal, &$people){

    //Get all resources of the user
    $Result = $oMysqli->query("SELECT * FROM ressources WHERE userID = {$_SESSION["userID"]}");

    //Check if the request was successful
    if($Result && $Result->num_rows > 0){
        $resArr = mysqli_fetch_array($Result);

        //Set resource values
        $wood = $resArr['wood'];
        $stone = $resArr['stone'];
        $metal = $resArr['metal'];
        $people = $resArr['people'];

        //Return the array if all the data is needed at once (fixes the progress bar bug)
        return $resArr;
    }
    else{
        echo("Failed to get resources out of the database");
    }
}

//Sets the given resource values to the database given
function SetResources($oMysqli, $wood, $stone, $metal, $people){
    //Set resources
    //echo ("wood: " . $wood . ", stone: " . (int)$stone . ", metal: " . $metal . ", people: " . $people);
    //var_dump($oMysqli->query("UPDATE ressources SET wood = {$wood}, stone = {$stone}, metal = {$metal}, people = {$people} WHERE userID = {$_SESSION["userID"]}"));
    $oMysqli->query("UPDATE ressources SET wood = {$wood}, stone = {$stone}, metal = {$metal}, people = {$people} WHERE userID = {$_SESSION["userID"]}");
}

//Returns an array with all the factory(producing buildings) levels
function GetFactoryLevels($oMysqli){
    $sLevelQuery = "SELECT woodFactory, stoneFactory, metalFactory, farm FROM buildings WHERE userID = {$_SESSION["userID"]}";
    $res = $oMysqli->query($sLevelQuery);

    return mysqli_fetch_array($res);
}

//Returns an array with all the building levels
function GetBuildingLevels($oMysqli){
    $sLevelQuery = "SELECT * FROM buildings WHERE userID = {$_SESSION["userID"]}";
    $lvl = $oMysqli->query($sLevelQuery);

    return mysqli_fetch_array($lvl);
}

//Upgrade the building with the given name in the database
function UpdateBuilding($building){

    // open connection to db server and selcting the db
    if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    //Check if the given string was a valid building
    if($building == "headquarter" || $building == "woodFactory" || $building == "stoneFactory" || $building == "metalFactory" || $building == "farm" || $building == "storage"){

        $query = "SELECT $building FROM buildings WHERE userID = '{$_SESSION['userID']}'";
        $Res = $oMySqli->query($query);

        $data = mysqli_fetch_array($Res);
        $newLevel = $data[$building] + 1;

        //Check if enough resources are available for the upgrade
        if(CheckResources($oMySqli, $building, $data[$building])){
            $query = "UPDATE buildings SET $building = $newLevel WHERE userID = '{$_SESSION['userID']}'";
            $Res = $oMySqli->query($query);
        }
        else{
            //TODO: Not enough resources at that point ... do something
            echo("Not enough resources ... you idiot");
        }
    }
    else{
        echo("Not a valid building name");
        return false;
    }
}

//Checks if the resources are available to upgraded the building to the given level
function CheckResources($oMysqli, $building, $level) {

    global $woodFactoryCost;
    global $stoneFactoryCost;
    global $metalFactoryCost;
    global $headquarterCost;
    global $farmCost;
    global $storageCost;

    //Needed until people are fully implemented
    $peopleNeeded = 0;

    //Set the costs for the building upgrade
    if($building == "woodFactory"){
        $woodNeeded = $woodFactoryCost[$level]["wood"];
        $stoneNeeded = $woodFactoryCost[$level]["stone"];
        $metalNeeded = $woodFactoryCost[$level]["metal"];
    }
    else if($building == "stoneFactory"){
        $woodNeeded = $stoneFactoryCost[$level]["wood"];
        $stoneNeeded = $stoneFactoryCost[$level]["stone"];
        $metalNeeded = $stoneFactoryCost[$level]["metal"];
    }
    else if($building == "metalFactory"){
        $woodNeeded = $metalFactoryCost[$level]["wood"];
        $stoneNeeded = $metalFactoryCost[$level]["stone"];
        $metalNeeded = $metalFactoryCost[$level]["metal"];
    }
    else if($building == "headquarter"){
        $woodNeeded = $headquarterCost[$level]["wood"];
        $stoneNeeded = $headquarterCost[$level]["stone"];
        $metalNeeded = $headquarterCost[$level]["metal"];
    }
    else if($building == "farm"){
        $woodNeeded = $farmCost[$level]["wood"];
        $stoneNeeded = $farmCost[$level]["stone"];
        $metalNeeded = $farmCost[$level]["metal"];
    }
    else if($building == "storage"){
        $woodNeeded = $storageCost[$level]["wood"];
        $stoneNeeded = $storageCost[$level]["stone"];
        $metalNeeded = $storageCost[$level]["metal"];
    }


    //Get current resources
    $sSelectQuery = "SELECT wood,metal,stone,people FROM ressources WHERE userID = {$_SESSION['userID']};";
    $mResult = $oMysqli->query($sSelectQuery);

    $resArr = mysqli_fetch_assoc($mResult);

    //Check if resources are enought to upgrade the building
    if($resArr["wood"] >= $woodNeeded && $resArr["stone"] >= $stoneNeeded && $resArr["metal"] >= $metalNeeded){
        $newWood = $resArr['wood']-$woodNeeded;
        $newStone = $resArr['stone']-$stoneNeeded;
        $newMetal = $resArr['metal']-$metalNeeded;

        SetResources($oMysqli, $newWood, $newStone, $newMetal, $peopleNeeded);
        return true;

    } else {

        //Error needs to be caught where the function gets called
        return false;
    }
}

//Returns the production of the given building as a string if it is a valid building
function GetProduction($building){

    global $woodFactoryProduction;
    global $stoneFactoryProduction;
    global $metalFactoryProduction;
    global $farmProduction;

    //Connect to the database
    if(! $oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    //Get the levels of all the factories
    $level = GetFactoryLevels($oMysqli);

    //Return the production value of the given building at the current level
    if($building == "woodFactory"){
        $production = $woodFactoryProduction["{$level[$building]}"];
    }
    else if($building == "stoneFactory"){
        $production = $stoneFactoryProduction["{$level[$building]}"];
    }
    else if($building == "metalFactory"){
        $production = $metalFactoryProduction["{$level[$building]}"];
    }
    else if($building == "farm"){
        $production = $farmProduction["{$level[$building]}"];
    }
    else{
        $production = 0;
    }

    return $production * 60;
}

//Returns an array of the resource cost needed to upgrade the given building
function GetUpgradeCosts($building){

    global $headquarterCost;
    global $woodFactoryCost;
    global $stoneFactoryCost;
    global $metalFactoryCost;
    global $farmCost;
    global $storageCost;

    //Connect to the database
    if(! $oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    //Get the levels of all the factories
    $level = GetBuildingLevels($oMysqli);

    if($building == "headquarter"){
        $costs = $headquarterCost[$level[$building]];
    }
    else if($building == "woodFactory"){
        $costs = $woodFactoryCost[$level[$building]];
    }
    else if($building == "stoneFactory"){
        $costs = $stoneFactoryCost[$level[$building]];
    }
    else if($building == "metalFactory"){
        $costs = $metalFactoryCost[$level[$building]];
    }
    else if($building == "farm"){
        $costs = $farmCost[$level[$building]];
    }
    else if($building == "storage"){
        $costs = $storageCost[$level[$building]];
    }

    return $costs;
}

//Sets the given resource values to the session resources
function SetResourcesToSession($wood, $stone, $metal, $people){
    $_SESSION["wood"] = $wood;
    $_SESSION["stone"] = $stone;
    $_SESSION["metal"] = $metal;
    $_SESSION["people"] = $people;
}

//Returns the current storage capacity and sets it to the session
function GetStorageCapacity(){

    global $storageCapacity;

    // open connection to db server and selcting the db
    if(! $oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    $levels = GetBuildingLevels($oMysqli);

    $storageCap = $storageCapacity[$levels['storage']];
    $_SESSION["storageCapacity"] = $storageCap;

    return $storageCap;
}

//Checks if there is storage available to store resources
function StorageAvailable($oMysqli){

    GetResources($oMysqli, $wood, $metal, $stone, $people);
    $storage = GetStorageCapacity();

    if($wood >= $storage || $stone >= $storage || $metal >= $storage){
        //Storage is full
        return false;
    }
    else{
        //Storage available
        return true;
    }
}
?>