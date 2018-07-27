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
            <p>Hver kreds / gruppe skal stille med følgende:</p>
            <ul>
                <li>0-4 deltagere: Ingen postmandskab</li>
                <li>5-10 deltagere: 1 leder til postmandskab</li>
                <li>11-25 deltagere: 2 ledere til postmandskab</li>
                <li>26-40 deltagere: 3 ledere til postmandskab</li>
                <li>40+ deltagere: 4 ledere til postmandskab</li>
            </ul>

                <form class="form-horizontal" method="post" id="postmandskab1">
                    <div class="well">
                    <p class="postheader"><b>Postmandskab 1</b></p>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="team" class="control-label sr-only">Navn</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" placeholder="Navn">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="mobile" class="control-label sr-only">Mobil</label>
                                <div class="col-sm-12">
                                    <input type="tel" maxlength="8" name="mobile" class="form-control" placeholder="Mobil">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kreds / Gruppe</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="kreds" placeholder="Kreds / Gruppe">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kommentar</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="1" name="comment" placeholder="Kommentar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
                <form class="form-horizontal" method="post" id="postmandskab2">
                    <div class="well">
                    <p class="postheader"><b>Postmandskab 2</b></p>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="team" class="control-label sr-only">Navn</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" placeholder="Navn">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="mobile" class="control-label sr-only">Mobil</label>
                                <div class="col-sm-12">
                                    <input type="tel" maxlength="8" name="mobile" class="form-control" placeholder="Mobil">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kreds / Gruppe</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="kreds" placeholder="Kreds / Gruppe">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kommentar</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="1" name="comment" placeholder="Kommentar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
                <form class="form-horizontal" method="post" id="postmandskab3" style="display: none">
                    <div class="well">
                    <p class="postheader"><b>Postmandskab 3</b></p>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="team" class="control-label sr-only">Navn</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" placeholder="Navn">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="mobile" class="control-label sr-only">Mobil</label>
                                <div class="col-sm-12">
                                    <input type="tel" maxlength="8" name="mobile" class="form-control" placeholder="Mobil">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kreds / Gruppe</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="kreds" placeholder="Kreds / Gruppe">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kommentar</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="1" name="comment" placeholder="Kommentar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
                <form class="form-horizontal" method="post" id="postmandskab4" style="display: none">
                    <div class="well">
                    <p class="postheader"><b>Postmandskab 4</b></p>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="team" class="control-label sr-only">Navn</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" placeholder="Navn">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="mobile" class="control-label sr-only">Mobil</label>
                                <div class="col-sm-12">
                                    <input type="tel" maxlength="8" name="mobile" class="form-control" placeholder="Mobil">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kreds / Gruppe</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="kreds" placeholder="Kreds / Gruppe">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kommentar</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="1" name="comment" placeholder="Kommentar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
                <hr />
                <form class="form-horizontal" style="padding: 0 0 0 15px">
                    <p class="sikkerhedskode"><b>Sikkerhedskode</b></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="captcha" class="control-label sr-only">Sikkerhedskode</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Kode">
                                </div>
                                <div class="col-sm-4">
                                    <img src="<?php print($_SESSION['captcha']['image_src']); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-default" id="tilmeldknap">Tilmeld postmandskab</button>
                                </div>
                            </div>
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
    $("#tilmeldknap").on('click',function(){
        $('form').each(function () {
            var captcha = $("#captcha").prop('value');
            var formid = '#'+$(this).prop('id');
            if($(this).is(':visible')) {
                $.post('createcrewhandler.php', $(this).serialize()+"&captcha="+captcha, function (data) {
                    obj = JSON.parse(data);
                    if(!obj.status) {
                        $('input',formid).each(function () {
                             if(!$.trim(this.value).length) { // zero-length string AFTER a trim
                                 $(this).parent().parent().addClass('has-error');
                                 $('.postheader',formid).html("<span class='label label-danger'>Ikke tilmeldt, udfyld manglende info</span>");
                             }
                        });

                        if(obj.message == "sikkerhedskode") {
                            $("#captcha").parent().parent().addClass('has-error');
                            $('.sikkerhedskode').html("<span class='label label-danger'>Forkert sikkerhedskode</span>");
                            //$( ".modal-title" ).html( "Fejl" );
                            //$( ".modal-body" ).html( obj.message );
                            //$('#myModal').modal('show');
                        } else {
                            $('.well',formid).addClass('bg-danger');
                        }

                        return false;
                    }
                    else {
                        $('input',formid).each(function () {
                            var value = $(this).prop("value");
                            $(this).parent().html("<span style='padding-left: 5px'>"+value+"</span>");
                        });
                        $('textarea',formid).each(function () {
                            var value = $(this).prop("value");
                            $(this).parent().html("<span style='padding-left: 5px'>"+value+"</span>");
                        });
                        $('.postheader',formid).html("<span class='label label-success'>Tilmeldt</span>");
                        $('.well',formid).css("background-color","#dff0d8");

                        //$(formid).html( obj.message );
                    }
                });
            }

        });

    });



</script>
</body>

</html>