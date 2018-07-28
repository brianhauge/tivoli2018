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
            <div id="antalcontainer">
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
                <div class="form-horizontal" method="post" id="antal">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="team" class="control-label sr-only">Antal deltagere</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-chevron-right"></i></span>
                                            <input type="text" class="form-control" name="antal" id="antalinput" placeholder="Antal deltagere fra jeres kreds / gruppe">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-default" id="antalknap">Beregn antal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <hr>
                </div>
            </div>
            <hr />
            <div class="form-horizontal" style="display: none;" id="sikkerhedsform">
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
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-default" id="tilmeldknap">Tilmeld postmandskab</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js" integrity="sha384-zvMQfjRbXhrBMD2UJZW5K0txqcbUBEZygTi8PPJ3NFWE978a5zAisEiCnLjH2wmM" crossorigin="anonymous"></script>
<script type="text/javascript">
    var i = 1;
    $("#antalknap").on('click',function(){
        var antal = $("#antalinput").prop('value');
        var number = 0;
        if (antal < 5) {
            $( "#antalcontainer" ).append( '<div class="well">Da i er mindre end 5, behøver i ikke tilmelde nogen postmandskaber.</div>' );
        } else if (antal < 11) {
            number = 2;
            $("#sikkerhedsform").show();
        } else if (antal < 26) {
            number = 3;
            $("#sikkerhedsform").show();
        } else if (antal < 41) {
            number = 4;
            $("#sikkerhedsform").show();
        } else {
            number = 5;
            $("#sikkerhedsform").show();
        }

        while (i < number) {
            $.get("postmandskabsform.php", {mandskabnum: i}, function (data) {
                $("#antalcontainer").append(data);
            });
            i++;
        }
    });

    $("#tilmeldknap").on('click',function(){
        $("#tilmeldknap").prop("disabled","disabled").html("Tjekker...");
        var warning = 0;
        $('form.tilmeld').each(function () {
                var captcha = $("#captcha").prop('value');
                var formid = '#'+$(this).prop('id');
                $.post('createcrewhandler.php', $(this).serialize()+"&captcha="+captcha, function (data) {
                    obj = JSON.parse(data);
                    if(obj.sikkerhedskode) {
                        $("#captcha").parent().parent().addClass('has-success').removeClass('has-error');
                        $("#captcha").prop("disabled","disabled");
                        $('.sikkerhedskode').html("<span class='label label-success'>Korrekt sikkerhedskode</span>");
                    } else {
                        $("#captcha").parent().parent().addClass('has-error').removeClass('has-success');
                        $('.sikkerhedskode').html("<span class='label label-danger'>Forkert sikkerhedskode</span>");
                    }
                    if(!obj.status) {
                        warning = 1;
                        $('input',formid).each(function () {
                             if(!$.trim(this.value).length) { // zero-length string AFTER a trim
                                 $(this).parent().parent().addClass('has-error');
                                 $('.postheader',formid).html("<span class='label label-danger'>Ikke tilmeldt, udfyld manglende info</span>");
                             }
                        });
                        $('.well',formid).addClass('bg-danger');
                        return false;
                    }
                    else {
                            $('input', formid).each(function () {
                                var value = $(this).prop("value");
                                $(this).parent().html("<span style='padding-left: 5px'>" + value + "</span>");
                            });
                            $('textarea', formid).each(function () {
                                var value = $(this).prop("value");
                                $(this).parent().html("<span style='padding-left: 5px'>" + value + "</span>");
                            });
                            $('.postheader', formid).html("<span class='label label-success'>Tilmeldt</span>");
                            $(formid).removeClass('tilmeld');
                    }
                });
        });
        $("#tilmeldknap").html("Tilmelder...");
        setTimeout(function(){
            if(!warning) {
                $("#antalknap").prop("disabled","disabled");
                $("#antalinput").prop("disabled","disabled");
                $("#sikkerhedsform").html("<p>Tak for det. Jeres postmandskab er nu tilmeldt.</p><p><a href='opretpostmandskab.php'>Klik her hvis i ønsker at tilmelde flere postmandskaber</a></p>")

            } else {
                $("#tilmeldknap").prop("disabled",false).html("Tjek fejl ovenfor og klik her igen");
            }
        }, 1000);
    });
</script>
</body>

</html>