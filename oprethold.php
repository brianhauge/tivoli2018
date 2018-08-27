<?php
session_start();
include "vendor/abeautifulsite/simple-php-captcha/simple-php-captcha.php";
include "config.php";
$_SESSION['captcha'] = simple_php_captcha();
$gametype = GAME_TYPE;
$gametypelong = GAME_TYPE_LONG;
if(isset($_GET['gametype'])) {
    $gametype = $_GET['gametype'];
    if($gametype === "n") {
        $gametypelong = "Natløb";
    } elseif ($gametype === "d") {
        $gametypelong = "Dagsløb";
    }
}

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Brian Hauge Hansen">
    <meta name="description" content="FDF og spejderne indtager Tivoli">
    <title>FDF og spejderne indtager Tivoli - Tilmeld Hold <?php print($gametypelong); ?></title>
    <link rel="canonical" href="<?php print(BASEURL); ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style media="all" type="text/css">
        .popover {
            max-width: 366px;
            padding: 10px;
            background-color: #fff;
        }

        .popover-title {
            padding: 8px 14px;
            margin: 0;
            font-size: 14px;
            background-color: #f7f7f7;
            border-bottom: 1px solid #ebebeb;
            border-radius: 5px 5px 0 0
        }

        .popover-content {
            padding: 9px 14px
        }
    </style>
</head>
<body>
<?php if(DRYRUN) {
?>
    <div class="alert alert-danger">
        <strong>Dryrun.</strong> Mails sendes ikke, database opdateres ikke.
    </div>
<?php
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1" id="contentelement">
            <div class="page-header">
                <h1>Tilmeld Hold <?php print($gametypelong); ?><small>Indtast oplysninger herunder</small></h1>
            </div>
            <form class="form-horizontal" id="createteam" method="post">
                <div class="form-group">
                    <label for="team" class="col-sm-2 control-label">Patruljenavn</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Holdnavn">
                    </div>
                </div>
                <div class="form-group">

<?php
    if($gametype == 'n') {
?>
                    <input type="hidden" name="group" id="N" value="N" />
<?php
    }
    else if($gametype == 'd') {
?>
                    <label for="group" class="col-sm-2 control-label">Løbsgruppe</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label><input type="radio" name="group" id="A" value="A">0. - 4. klasse</label>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="radio">
                            <label><input type="radio" name="group" id="B" value="B">5. - 8. klasse</label>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="radio">
                            <label><input type="radio" name="group" id="C" value="C">9. klasse - 18 år</label>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="radio">
                            <label><input type="radio" name="group" id="V" value="V">Kun voksne (ikke del af konkurrencen)</label>
                        </div>
                    </div>
<?php
    }
    else {
?>
                    <label for="group" class="col-sm-2 control-label">Løbsgruppe</label>
                    <div class="col-sm-10">
                        <div class="radio">
                            <label><input type="radio" name="group" id="Z" value="Z" checked>Standard</label>
                        </div>
                    </div>
<?php
    }
?>
                </div>
                <hr>
                <div class="form-group">
                    <label for="numberofmembers" class="col-sm-2 control-label">Antal</label>
                    <div class="col-sm-10">
                        <input type="number" maxlength="2" class="form-control" name="numberofmembers" id="numberofmembers" placeholder="Antal">
                        <!-- <input type="number" maxlength="2" class="form-control" name="numberofmembers" id="numberofmembers" tabindex="0" data-placement="top" data-toggle="popover" data-animation="false" data-trigger="focus" title="Postmandskab" data-html="true" data-content="<p>Hver kreds / gruppe skal stille med følgende:</p><ul><li>0-4 deltagere: Ingen postmandskab</li><li>5-10 deltagere: 1 leder til postmandskab</li><li>11-25 deltagere: 2 ledere til postmandskab</li><li>26-40 deltagere: 3 ledere til postmandskab</li><li>40+ deltagere: 4 ledere til postmandskab</li></ul><p>Tilmelding af postmandskab sker efter tilmelding af hold.</p>"> -->
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
                        <button type="submit" id="addTeamSubmit" class="btn btn-default">Tilmeld hold</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script type="text/javascript">


    $("#createteam").submit(function(){
        $("#addTeamSubmit").html('Tilmelder hold...').prop("disabled","disabled");
        $.post('createteamhandler.php', $('#createteam').serialize(), function (data) {
            obj = JSON.parse(data);
            if(!obj.status) {
                $('input').each(function () {
                    if(!$.trim(this.value).length) { // zero-length string AFTER a trim
                        $(this).parent().parent().addClass('has-error');
                    }
                });
                $("#addTeamSubmit").html('Tilmeld hold').prop("disabled",false);
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

        //$('#numberofmembers').on('focus', function () {
        //    $( this ).popover('show');
        //});


    });
</script>
</body>

</html>