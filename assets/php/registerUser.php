<?php
Require_Once('config.php');
Require_Once('userStartConfig.php');

function register($sUsername, $sPassword1, $sEmail){

    // open connection to db server and selcting the db
    if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    //Set the new user into the database
    $sInsert = "INSERT INTO user (username, userpw, useremail) 
                VALUES ('".$oMySqli->real_escape_string($sUsername)."',
                '". password_hash($sPassword1, PASSWORD_DEFAULT) ."',
                '". $oMySqli->real_escape_string($sEmail)."');";
    $oMySqli->query($sInsert);

    //Set userID to the session
    $_SESSION["userID"] = GetUserID($sUsername);

    //Set the starting values in the tabeles
    SetStartResources($oMySqli);
    SetUserInfo($oMySqli);
    SetBuildings($oMySqli);

    // close connection to db server
    if(!$oMySqli->close()) {
        echo("Database connection could not be closed");
    }
}

//Prepares the needed fields in the userinfo table
function SetUserInfo($oMysqli){

    $sSetTimeStamp = "INSERT INTO userinfo (userID, lastrefresh, coordX, coordY) 
                      VALUES ({$_SESSION['userID']},{new date(\"Y-m-d H:i:s\")}, 1, 1 )";
    $oMysqli->query($sSetTimeStamp);
}

//Prepares the needed fields in the resources table
function SetStartResources($oMysqli){

    global $startResources;

    $sSetResources = "INSERT INTO ressources (userID, wood, metal, stone, people) 
                      VALUES ({$_SESSION['userID']},{$startResources['wood']}, {$startResources['metal']}, {$startResources['stone']}, {$startResources['people']} )";
    $oMysqli->query($sSetResources);
}

//Prepares the needed fields in the buildings table
function SetBuildings($oMysqli){

    $sSetBuildings = "INSERT INTO buildings (userID, headquarter, woodFactory, stoneFactory, metalFactory) 
                      VALUES ({$_SESSION['userID']},1,1,1,1)";
    $oMysqli->query($sSetBuildings);
}

//Gets the userID from the given username out of the database
function GetUserID($username){

    // open connection to db server and selcting the db
    if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    $query = "SELECT userID FROM user WHERE username = '{$username}'";
    $Res = $oMySqli->query($query);

    mysqli_close($oMySqli);

    $userID = mysqli_fetch_assoc($Res);

    return (int)$userID["userID"];
}

?>