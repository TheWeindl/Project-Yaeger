<?php
Require_once('config.php');


//Upadtes the ressources a player has on his account based on the server time
//Calculation unit are minutes
function UpdateRessources(){
    $newResPerMin = 10;     //Resources gained every minute -> should later be read out of a table

    //Connect to the database
    if(!$oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)){
        echo("Could not connect to database");
    }

    //Get a timestamp from when the user last refreshed the page
    $Result = $oMysqli->query("SELECT lastrefresh FROM userinfo WHERE userID = 2");


    //If an database entry was returned, calculate the passed seconds from the timestamp till now
    if($Result && $Result->num_rows > 0){
        $lastRefresh = mysqli_fetch_assoc($Result);

        $currentRefresh = date("Y-m-d H:i:s");

        $timeFirst  = strtotime($currentRefresh);
        $timeSecond = strtotime($lastRefresh["lastrefresh"]);
        $differenceInMinutes = ($timeFirst - $timeSecond ) / 60;

        //If time delta is bigger than 1 minute the update resources
        if($differenceInMinutes >= 1){
            echo("More than one minute passed since last refresh\n");

            //Get the current resource values and set the new ones
            GetResources($oMysqli, $wood, $stone, $metal);
            SetResources($oMysqli, $wood + $newResPerMin * $differenceInMinutes, $stone + $newResPerMin * $differenceInMinutes, $metal + $newResPerMin * $differenceInMinutes);

            //echo("<br> Wood: $wood <br> Metal: $metal <br> Stone: $stone <br>");

            //Update the timestamp
            SetNewTimestamp($oMysqli, $currentRefresh);
        }

        mysqli_close($oMysqli);

        //Return the passed amount of time in minutes
        return '
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" id="progressBarMetal" role="progressbar" aria-valuenow="'. $differenceInMinutes*100 .'"
                 aria-valuemin="0" aria-valuemax="100" style="width:'.$differenceInMinutes*100 . '%">
                <span class="sr-only">'. $differenceInMinutes*100 .'% Complete</span>
            </div>
        </div>';
    }

    mysqli_close($oMysqli);
}

//Sets the given timestamp as the last refreshed timestamp in the database
function SetNewTimestamp($oMysqli, $currentTimestamp){

    $oMysqli->query("UPDATE userinfo SET lastrefresh='$currentTimestamp' WHERE userID = 2");
}

//Gets the resources of of the user
function GetResources($oMysqli, &$wood, &$stone, &$metal){

    //Get all resources of the user
    $Result = $oMysqli->query("SELECT * FROM ressources WHERE userID = 2");

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
    $oMysqli->query("UPDATE ressources SET wood = $wood, stone = $stone, metal = $metal WHERE userID = 2");
}
?>