<?php
Require_once('config.php');

function UpdateRessources(){
	
	if(!$oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)){
		echo("Could not connect to database");
	}

	$Result = $oMysqli->query("SELECT lastrefresh FROM userinfo WHERE userID = 2");

	if($Result && $Result->num_rows > 0){
		$lastRefresh = mysqli_fetch_assoc($Result);
		
		$currentRefresh = date("Y-m-d H:i:s");
		
		echo("last   refresh: " . $lastRefresh["lastrefresh"]);
		echo("curent refresh: " . $currentRefresh);
		
		// $TimeDelta = $currentRefresh - $lastRefresh;

        // $interval = date_diff($currentRefresh, $lastRefresh["lastrefresh"]);

        // echo $interval->format("%i");

        $timeFirst  = strtotime($currentRefresh);
        $timeSecond = strtotime($lastRefresh["lastrefresh"]);
        $differenceInSeconds = ($timeFirst - $timeSecond ) / 60;


		echo("Timedelta: $differenceInSeconds");
		return $differenceInSeconds;
	}
}
?>