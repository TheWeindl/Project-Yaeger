<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>project-yaeger</title>
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
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#" data-toggle="modal" data-target="#loginModal">Übersicht</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Something</a></li>

                            <li role="separator" class="divider"></li>
                            <li><a href="#">Settings</a></li>
                            <li><a href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>


<main>
    <div class="panel-group">
        <div class="panel panel-default panel-res">
            <div class="panel-heading" id="metal-head">Metal</div>
            <div class="panel-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" id="progressBarMetal" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span class="sr-only">70% Complete</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-res">
            <div class="panel-heading" id="wood-head">Wood</div>
            <div class="panel-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" id="progressBarWood" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span class="sr-only">70% Complete</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-res">
            <div class="panel-heading" id="stone-head">Stone</div>
            <div class="panel-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" id="progressBarStone" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span class="sr-only">70% Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">U</span>
                        <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" id="username">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon2">P</span>
                        <input type="text" class="form-control" placeholder="Password" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="sendButt">Send</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<footer>

</footer>


<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="code.js"></script>
</body>
</html>