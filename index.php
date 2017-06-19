<!DOCTYPE html>
<?php
error_reporting(E_ALL);
require_once("config.php");
require_once("updateRessources.php");
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>project-yaeger</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
// $sCorrectUsername="kwm15001";
// $sCorrectPassword="kwm15001";
$aError = [];
//$_SESSION["username"] = "test";

if(!isset($_SESSION["username"])){
    //case not logged in
    //case wants to log in
    if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "login"){
        if(loginDataCorrect($_REQUEST["username"], $_REQUEST["password"])){
            //true
            //login in Session
            $_SESSION["username"] = $_REQUEST["username"];
            //show content
            showContent();
            //showLogoutForm();
        }
        else{
            //false
            showHeaderForStart();
            showLoginForm();
            showRegistrationForm();
        }
    }
    //wants to register
    else if(isset($_REQUEST["action"])&& $_REQUEST["action"] == "register"){
        //check registration
        if(registrationDataCorrect()){
            //data ok
            //TODO: save data
            register($_REQUEST["username"], $_REQUEST["password1"], $_REQUEST["email"]);
            //login
            $_SESSION["username"] = $_REQUEST["username"];
            showContent();
            showLogoutForm();

        }
        else{
            //data not ok
            echo("Something went wrong, your data is incorrect");
            showHeaderForStart();
            showLoginForm();
            showRegistrationForm();
        }
    }
    //case wants to see the page
    else{
        showHeaderForStart();
        showLoginForm();
        showRegistrationForm();
    }

}

else{

    //case logged in
    //case wants to log out
    if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "logout"){
        unset($_SESSION["username"]);
        showHeaderForStart();
        showLoginForm();
        showRegistrationForm();

    } else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "upgradeHQ") {
        upgradeHQ();
    }
    //case wants to see the page
    else{
        //echo("Logged in! Welcome ".$_SESSION["username"]."<br/>");
        //showLogoutForm();
        showContent();
    }
}


function loginDataCorrect($sUsername, $sPassword){
    global $sCorrectUsername;
    global $sCorrectPassword;
    /*if($sUsername == $sCorrectUsername && $sPassword == $sCorrectPassword){
        return true;
    }
    else{
        return false;
    }*/
    if(!$oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        echo("Could not connect to database");
    }

    // $sSelectQuery = "SELECT username, userpw FROM user WHERE username = '".$oMysqli->real_escape_string($sUsername)."' AND userpw = '".$sPassword."';";
    $sSelectQuery = "SELECT username, userpw FROM user WHERE username = '".$oMysqli->real_escape_string($sUsername)."';";
    $mResult = $oMysqli->query($sSelectQuery);

    if($mResult && $mResult->num_rows > 0){

        $aResult = mysqli_fetch_assoc($mResult);
        //var_dump($aResult);
        if(password_verify($sPassword, $aResult["userpw"])){
            return true;
        }
        else return false;
    }
    else return false;
}

function register($sUsername, $sPassword1, $sEmail){
    echo("User is going to be registered soon<br/>");
    // open connection to db server and selcting the db
    if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    // TODO SQL statement
    $sInsert = "INSERT INTO user (username, userpw, useremail) VALUES ('".$oMySqli->real_escape_string($sUsername)."', '". password_hash($sPassword1, PASSWORD_DEFAULT) ."', '". $oMySqli->real_escape_string($sEmail)."');";
    $sResult = $oMySqli->query($sInsert);

    // close connection to db server
    if(!$oMySqli->close()) {
        echo("Database connection could not be closed");
    }

    // echo($sResult);
}

function registrationDataCorrect(){
    global$aError;
    //username must be unique
    //username must be a string of at least 8 characters
    //password must be a string of at least 12 characters
    //passwords must be the same
    //email hast do be a valid email adress
    if(!isset($_REQUEST["username"]) ||
        !checkLength($_REQUEST["username"] ,4)) {
        $aError["username"] = "Username is not correct, please try again. It has to have 4 characters.";
    }

    if(!isUnique($_REQUEST["username"])) {
        $aError["username"] = "Username <i>".$_REQUEST["username"]."</i> is not available.";
    }

    if(!isset($_REQUEST["password1"]) ||
        !isset($_REQUEST["password2"]) ||
        $_REQUEST["password1"]!=$_REQUEST["password2"] ||
        !checkLength($_REQUEST["password1"] ,4)
    ){
        $aError["password"] = "Password is not correct, please try again. 
			It has to have 4 characters and the passwords must match";
    }
    if(!isset($_REQUEST["email"]) ||
        !checkEmail($_REQUEST["email"])
    ){
        $aError["email"] = "Email is not correct, please try again. It has to be a valid E-Mail Adress";
    }
    if(count($aError)>0){
        return false;
    }
    else{
        return true;
    }
}

function checkEmail($sEmail){
    if(filter_var($sEmail, FILTER_VALIDATE_EMAIL) !==false){
        return true;
    }
    else{
        return false;
    }
}

function checkLength($sString, $iLength){
    return(strlen($sString) >= $iLength);
}

function isUnique($sUsername){
    global $aError;
    //TODO check in database
    if(! $oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        echo("Could not connect to the databas!");
    }

    $sSelectQuery = "SELECT username FROM user WHERE username = '".$oMysqli->real_escape_string($sUsername)."';";
    $mResult = $oMysqli->query($sSelectQuery);

    $oMysqli->close();

    if($mResult && $mResult->num_rows > 0) {

        return false;
    }
    return true;
}


function showContent(){
    showHeaderForLoggedIn();
    ?>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Your Town</div>
            <div class="panel-body">
                <?php
                renderVillage();
                echo ("<p> Minutes since last refresh: " . UpdateRessources() . "</p>");
                ?>
            </div>
        </div>
    </div>
    <?php
}

function renderVillage() {

    if(! $oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        echo("Could not connect to the databas!");
    }

    $sSelectQuery0 = "SELECT headquarter FROM buildings WHERE userID = 2";
    $mResult1 = $oMysqli->query($sSelectQuery0);


    $sSelectQuery = "SELECT wood,metal,stone FROM ressources WHERE userID = 2;";
    $mResult = $oMysqli->query($sSelectQuery);



    $aRow = mysqli_fetch_assoc($mResult);
    $aRow1 = mysqli_fetch_assoc($mResult1);
    echo ("<p>Headquarters: ". $aRow1["headquarter"] ."</p>");
    echo("<a href='index.php?action=upgradeHQ'>upgrade HQ</a>");
    echo ("<p>Woodproduction: ". $aRow["wood"] ."</p>");
    echo ("<p>Stoneproduction: ". $aRow["stone"] ."</p>");
    echo ("<p>Metalproduction: ". $aRow["metal"] ."</p>");

    $oMysqli->close();
}

function upgradeHQ() {
    $woodNeeded = 1000;
    $stoneNeeded = 1000;

    if(! $oMysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        echo("Could not connect to the databas!");
    }

    $sSelectQuery0 = "SELECT headquarter FROM buildings WHERE userID = 2";
    $mResult1 = $oMysqli->query($sSelectQuery0);
    //
    $sSelectQuery = "SELECT wood,metal,stone FROM ressources WHERE userID = 2;";
    $mResult = $oMysqli->query($sSelectQuery);

    $aRow = mysqli_fetch_assoc($mResult);
    $aRow1 = mysqli_fetch_assoc($mResult1);
    if($aRow["wood"] >= $woodNeeded && $aRow["stone"] >= $stoneNeeded){
        //TODO upgrade HQ
        $oMysqli->query("UPDATE ressources SET wood = " . $aRow['wood']-$woodNeeded . ", stone = " . $aRow['stone']-$stoneNeeded . ",  WHERE userID = 2");
        $oMysqli->query("UPDATE buildings SET headquarter = ". $aRow1["headquarter"]+1 .",  WHERE userID = 2");
    } else {
        echo("not enough resources");
    }

    $oMysqli->close();
}

function showLoginForm(){
    ?>
    <div class="container login">
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <form action="index.php" method="post" name="login">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">U</span>
                        <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" name="username" id="username" /><p></p>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">P</span>
                        <input type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1" name="password" id="password" /><p></p>
                    </div>
                    <br/>
                    <div class="btn-group" role="group">
                        <input type="hidden" name="action" value="login" />
                        <input type="submit" class="btn btn-primary" value="Submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

function showLogoutForm(){
    ?>
    <div class="btn-group" role="group">
        <form action="index.php" method="post" name="logout">
            <input type="hidden" name="action" value="logout" />
            <input type="submit" class="btn btn-error" value="Logout." />
        </form>
    </div>
    <?php
}

function showHeaderForLoggedIn() {
    ?>
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">project-yaeger</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" data-toggle="modal" data-target="#loginModal">Übersicht</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">About us</a></li>
                            </ul>
                        </li>
                        <li><a href="#" data-toggle="modal" data-target="#logoutModal">Logout</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </header>
    <div id="logoutModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you really want to log out?</h4>
                </div>
                <div class="modal-body">
                    <div class="btn-group" role="group">
                        <!--<form action="index.php" method="get" name="logout">
                            <input type="hidden" name="action" value="logout" />
                            <input type="submit" class="btn btn-error" value="Logout." />
                        </form>-->
                        <p>Placeholder for ads</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form action="index.php" method="get" name="logout">
                        <input type="hidden" name="action" value="logout" />
                        <input type="submit" class="btn btn-error" value="Logout" />
                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php
}

function showHeaderForStart() {
    ?>
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">project-yaeger</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" data-toggle="modal" data-target="#loginModal">Übersicht</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">About us</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </header>
    <?php
}

function showRegistrationForm(){
    global $aError;
    ?>
    <div class="container logout">
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <form action="index.php" method="post" name="register">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">U</span>
                        <?php
                        if(isset($aError["username"])) {
                            ?>
                            <input type="text" class="form-control error" placeholder="Username" aria-describedby="basic-addon1" name="username" id="username"/><?php echo $aError["username"]; ?></br>
                            <?php
                        }
                        else {
                            ?>
                            <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" name="username" id="username" /><br/>
                            <?php
                        }
                        ?>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">P1</span>
                        <input type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1" name="password1" id="passwor1" /><br/>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">P2</span>
                        <input type="password" class="form-control" placeholder="Password again" aria-describedby="basic-addon1" name="password2" id="password2" /><br/>
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">@</span>
                        <input type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon1" name="email" id="email" /><br/>
                    </div>
                    <br/>
                    <div class="btn-group" role="group">
                        <input type="hidden" name="action" value="register" />
                        <input type="submit" class="btn btn-primary" value="Register" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}


?>

<footer>
    <a href="#">Impressum</a>
</footer>


<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="code.js"></script>
<script>setInterval(function () {
        document.write('<?php updateResources(); ?>');
    },600)</script>
</body>
</html>