<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 12/08/16
 * Time: 20:04
 */

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Brian Hauge Hansen">
    <meta name="description" content="FDF og spejderne indtager Tivoli">
    <title>FDF og spejderne indtager Tivoli - Score</title>
    <link rel="canonical" href="http://haugemedia.net/tivoli2016/">
    <link rel="stylesheet" href="dist/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="dist/css/bootstrap-theme.min.css" type="text/css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="page-header">
                <h1>Opret hold <small>Indtast oplysninger herunder</small></h1>
            </div>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="team" class="col-sm-2 control-label">Holdnavn</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="team" id="team" placeholder="Holdnavn">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Gruppe</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label><input type="radio" name="group" id="1" value="1" checked>Gruppe 1</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="group" id="2" value="2">Gruppe 2</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="mobile" class="col-sm-2 control-label">Mobil</label>
                    <div class="col-sm-10">
                        <input type="tel" maxlength="8" name="mobile" class="form-control" id="mobile" placeholder="Mobil">
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Opret hold</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>