<!DOCTYPE html>
<?php
require_once("config.php");
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


<main>
    <div class="container login">
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">U</span>
                    <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" id="username">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon2">P</span>
                    <input type="text" class="form-control" placeholder="Password" aria-describedby="basic-addon1">
                </div>
                <br/>
                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-primary" id="sendButt">Login</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container register">
        <div class="panel panel-default">
            <div class="panel-heading">Register</div>
            <div class="panel-body">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon0">E</span>
                    <input type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon1" id="email">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">U</span>
                    <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" id="username">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon2">P</span>
                    <input type="text" class="form-control" placeholder="Password" aria-describedby="basic-addon1">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon2">P</span>
                    <input type="text" class="form-control" placeholder="Password again" aria-describedby="basic-addon1">
                </div>
                <br/>
                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-primary" id="sendButt">Register</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container testing">
        <p>testing</p>
        <?php
        if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
            die("Database connection could not be established!");
        } else {
            echo "<p>Connection established</p>";
        }

        $sSelectQuery = "SELECT * FROM user WHERE userID='1';";
        $mResult = $oMysqli->query($sSelectQuery);
        echo ($mResult);
        ?>
    </div>

</main>

<footer>
    <a href="#">Impressum</a>
</footer>


<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="code.js"></script>
</body>
</html>