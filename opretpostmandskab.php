<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 01/09/16
 * Time: 10:58
 */

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
    <title>FDF og spejderne indtager Tivoli - Tilmeld natpostmandskab</title>
    <link rel="canonical" href="http://haugemedia.net/tivoli2018/">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1" id="contentelement">
            <div class="page-header">
                <h1>Postmandskabstilmelding <small><?php
                            if (GAME_TYPE == 'd') print("Dagsløb");
                            else if (GAME_TYPE == 'n') print("Natløb");
                            else print("Løb");
                        ?></small></h1>
            </div>

            <form class="form-inline" id="createteam" method="post">
                <div class="form-group">
                    <label for="team" class="control-label">Navn</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Navn">
                </div>
                <div class="form-group">
                    <label for="mobile" class="control-label">Mobil</label>
                    <input type="tel" maxlength="8" name="mobile" class="form-control" id="mobile" placeholder="Mobil">
                </div>
                <div class="form-group">
                    <label for="kreds" class="control-label">Kreds / Gruppe</label>
                    <input type="text" class="form-control" name="kreds" id="kreds" placeholder="Kreds / Gruppe">
                </div>
                <div class="form-group">
                    <label for="kreds" class="control-label">Kommentar</label>
                    <textarea class="form-control" rows="3" name="comment" id="comment"></textarea>
                </div>
                <div class="form-group">
                    <label for="captcha" class="control-label">Sikkerhedskode</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Kode">
                    </div>
                    <div class="col-sm-4">
                        <img src="<?php print($_SESSION['captcha']['image_src']); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Send</button>
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
        $.post('createcrewhandler.php', $('#createteam').serialize(), function (data) {
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