<?php
Require_once('config.php');
Require_once('buildingsSpecs.php');

//Updates the resources a player has on his account based on the server time
//Calculation unit are minutes
function UpdateRessources(){

    global $woodFactoryProduction;
    global $stoneFactoryProduction;
    global $metalFactoryProduction;

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

        //If time delta is bigger than 1 minute the update resources
        if($differenceInMinutes >= 1){

            //Get the current resource values and set the new ones
            GetResources($oMysqli, $wood, $stone, $metal);
            SetResources($oMysqli,
                $wood + $woodFactoryProduction[(int)$levels["woodFactory"]] * $differenceInMinutes,
                $stone + $stoneFactoryProduction[(int)$levels["stoneFactory"]] * $differenceInMinutes,
                $metal + $metalFactoryProduction[(int)$levels["metalFactory"]] * $differenceInMinutes);

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
function GetResources($oMysqli, &$wood, &$stone, &$metal){

    //Get all resources of the user
    $Result = $oMysqli->query("SELECT * FROM ressources WHERE userID = {$_SESSION["userID"]}");

    //Check if the request was successful
    if($Result && $Result->num_rows > 0){
        $resArr = mysqli_fetch_array($Result);

        //Set resource values
        $wood = $resArr['wood'];
        $stone = $resArr['stone'];
        $metal = $resArr['metal'];
    }
    else{
        echo("Failed to get resources out of the database");
    }
}

//Sets the given resource values to the database given
function SetResources($oMysqli, $wood, $stone, $metal){

    //Set resources
    $oMysqli->query("UPDATE ressources SET wood = $wood, stone = $stone, metal = $metal WHERE userID = {$_SESSION["userID"]}");
}

function GetFactoryLevels($oMysqli){
    $sLevelQuery = "SELECT woodFactory, stoneFactory, metalFactory FROM buildings WHERE userID = {$_SESSION["userID"]}";
    $res = $oMysqli->query($sLevelQuery);

    return mysqli_fetch_array($res);
}

//Upgrade the building with the given name in the database
function UpdateBuilding($building){

    // open connection to db server and selcting the db
    if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    //Check if the given string was a valid building
    if($building == "headquarter" || $building == "woodFactory" || $building == "stoneFactory" || $building == "metalFactory"){

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

//Checks if the resources are available to upgradet the building to the given level
function CheckResources($oMysqli, $building, $level) {

    global $woodFactoryCost;
    global $stoneFactoryCost;
    global $metalFactoryCost;
    global $headquarterCost;

    $woodNeeded = 0;
    $stoneNeeded = 0;
    $metalNeeded = 0;

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


    //Get current resources
    $sSelectQuery = "SELECT wood,metal,stone FROM ressources WHERE userID = {$_SESSION['userID']};";
    $mResult = $oMysqli->query($sSelectQuery);

    $resArr = mysqli_fetch_assoc($mResult);

    //Check if resources are enought to upgrade the building
    if($resArr["wood"] >= $woodNeeded && $resArr["stone"] >= $stoneNeeded && $resArr["metal"] >= $metalNeeded){
        $newWood = $resArr['wood']-$woodNeeded;
        $newStone = $resArr['stone']-$stoneNeeded;
        $newMetal = $resArr['metal']-$metalNeeded;

        SetResources($oMysqli, $newWood, $newStone, $newMetal);
        return true;

    } else {

        //Error needs to be caught where the function gets called
        return false;
    }
}
?>