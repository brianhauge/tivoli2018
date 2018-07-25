<?php
session_start();
include "vendor/abeautifulsite/simple-php-captcha/simple-php-captcha.php";
include "config.php";
$_SESSION['captcha'] = simple_php_captcha();
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
    <link rel="canonical" href="http://haugemedia.net/tivoli2018/">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <fieldset class="col-md-10 col-md-offset-1" id="contentelement">
            <div class="page-header">
                <h1>Opret hold <small>Indtast oplysninger herunder</small></h1>
            </div>
            <form class="form-horizontal" id="createteam" method="post">
                <div class="form-group">
                    <label for="team" class="col-sm-2 control-label">Patruljenavn</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Holdnavn">
                    </div>
                </div>
                <fieldset class="form-group">
                    <legend for="group" class="col-form-label col-sm-2 pt-0">Løbsgruppe</legend>
                    <div class="col-sm-10">
<?php
    if(GAME_TYPE == 'n') {
?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group" id="N" value="N" checked>
                            <label class="form-check-label" for="N">
                                Natløb
                            </label>
                        </div>
<?php
    }
    else if(GAME_TYPE == 'd') {
?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group" id="A" value="A" checked>
                            <label class="form-check-label" for="A">
                                0-4 klasse, (6-10 år) Følger proffesoren
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group" id="B" value="B" checked>
                            <label class="form-check-label" for="B">
                                5-8 klasse, (10-14 år) Følger det interdimensionelle politi (DIP)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group" id="C" value="C" checked>
                            <label class="form-check-label" for="C">
                                9 klasse - 18 år (14-18 år) Følger militæret
                            </label>
                        </div>
<?php
    }
    else {
?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group" id="Z" value="Z" checked>
                            <label class="form-check-label" for="Z">
                                Standard
                            </label>
                        </div>
<?php
    }
?>
                    </div>
                </fieldset>
                <hr>
                <div class="form-group">
                    <label for="numberofmembers" class="col-sm-2 control-label">Antal</label>
                    <div class="col-sm-10">
                        <input type="number" maxlength="2" class="form-control" name="numberofmembers" id="numberofmembers" placeholder="Antal patruljemedlemmer">
                    </div>
                </div>
                <div class="form-group">
                    <label for="leader" class="col-sm-2 control-label">Patruljeleder</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="leader" id="leader" placeholder="Patruljeleder">
                    </div>
                </div>
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
                    <label for="kreds" class="col-sm-2 control-label">Kreds / Gruppe</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="kreds" id="kreds" placeholder="Kreds / Gruppe">
                    </div>
                </div>
                <div class="form-group">
                    <label for="captcha" class="col-sm-2 control-label">Sikkerhedskode</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Kode">
                    </div>
                    <div class="col-sm-4">
                        <img src="<?php print($_SESSION['captcha']['image_src']); ?>" />
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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Info</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Luk</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $("#createteam").submit(function(){
        $.post('createteamhandler.php', $('#createteam').serialize(), function (data) {
            obj = JSON.parse(data);
            if(!obj.status) {
                $('input').each(function () {
                    if(!$.trim(this.value).length) { // zero-length string AFTER a trim
                        $(this).parent().parent().addClass('has-error');
                    }
                });
                $( ".modal-title" ).html( "Fejl" );
                $( ".modal-body" ).html( obj.message );
                $('#myModal').modal('show');
            }
            else {
                $("#contentelement").html( obj.message );
            }
        });
        return false;
    });


    $().ready(function() {
        $('#createteam input').blur(function() {
            if(!$.trim(this.value).length) { // zero-length string AFTER a trim
                $(this).addClass('warning');
            }
        });
    });
</script>
</body>

</html>