<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 11/08/18
 * Time: 14:41
 */

session_start();
$_SESSION['smscode'] = rand(1000, 9999);
include "config.php";
setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
$checkIn = new PostCheckInController();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Giv Point</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <link rel="canonical" href="<?php print(BASEURL); ?>">
</head>

<body>

<!-- Start Tjekin -->
<div data-role="page" id="tjekind">
    <div data-role="header">
        <h1>Tjekind</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">
        <p>
            <label for="text-basic">Post</label>
			<form>
			    <div class="ui-field-contain">
			        <select id="post-filter-menu" data-native-menu="false" class="filterable-select">
			            <option>Vælg post...</option>
						<?php print ($checkIn->listPostsForWeb("d"));?>
			        </select>
			    </div>
			</form>
        </p>
        <p>
            <label for="number-pattern">Mobil</label>
            <input type="number" name="number" pattern="[0-9]*" id="msisdn" value="">
        </p>
        <p>
            <a href="#verificertjekind" id="tjekindbutton" class="ui-shadow ui-btn ui-corner-all">Tjekind</a>
        </p>
    </div><!-- /content -->
</div><!-- /page -->

<!-- Start Tjekin -->
<div data-role="page" id="verificertjekind">

    <div data-role="header">
        <h1>Verifikationskode</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">
        <p>
            <label for="text-basic">Indtast kode modtaget på SMS</label>
            <input type="text" name="text-basic" id="smscode" value="">
        </p>
        <p>
            <a href="#givpoint" id="verificertjekindbutton" class="ui-shadow ui-btn ui-corner-all">Tjekind</a>
        </p>
    </div><!-- /content -->
</div><!-- /page -->

<!-- Start Giv Point -->
<div data-role="page" id="givpoint">

    <div data-role="header">
        <h1>Giv Point</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">
        <p>
            <label for="text-basic">Hold</label>
            <input type="text" name="text-basic" id="team" value="">
        </p>
        <p>
            <label for="number-pattern">Point 1-100</label>
            <input type="number" name="number" pattern="[0-9]*" id="point" value="">
        </p>
        <p>
            <a href="#pointgivet" id="givpointbutton" class="ui-shadow ui-btn ui-corner-all">Giv point</a>
        </p>
        <p>
            <a href="#tjekind" style="position: absolute; bottom: 0; margin-bottom: 10px"> << Tjek ind på anden post</a>
        </p>
    </div><!-- /content -->
</div><!-- /page -->

<!-- Start Point Givet -->
<div data-role="page" id="pointgivet">

    <div data-role="header">
        <h1>Point Givet</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">
        <p id="pointgivetcontainer">Venter på point...</p>
        <p><a href="#givpoint"><< Gå tilbage</a></p>
    </div><!-- /content -->
</div><!-- /page -->


<script type="text/javascript">

    $().ready(function() {
        $.post('givpointhandler.php', "cmd=mypost", function (data) {
            obj = JSON.parse(data);
            if(obj.status) {
                $('#givpoint h1').html("Giv Point - Post "+obj.message);
            }
        });
    });
	
	$.mobile.defaultPageTransition = "slidefade";

    $("#tjekindbutton").on('click',function(event){
        event.preventDefault();
        var msisdn = $("#msisdn").prop('value');
        var postid = $("#post-filter-menu").prop('value');
		alert(postid);
        $.post('givpointhandler.php', "cmd=sendcode&postid="+postid+"&msisdn="+msisdn, function (data) {
            obj = JSON.parse(data);
            if(obj.status) {
                window.location.href = '#verificertjekind';
            }
            else {
                alert(obj.message);
            }
        });
    });

    $("#verificertjekindbutton").on('click',function(event){
        event.preventDefault();
        var msisdn = $("#msisdn").prop('value');
        var postid = $("#post-filter-menu").prop('value');
        var smscode = $("#smscode").prop('value');
        $.post('givpointhandler.php', "cmd=tjekind&postid="+postid+"&msisdn="+msisdn+"&smscode="+smscode, function (data) {
            obj = JSON.parse(data);
            if(obj.status) {
                window.location.href = '#givpoint';
                $('#givpoint h1').html("Giv Point - Post "+postid);
            }
            else {
                alert(obj.message);
            }
        });
    });

    $("#givpointbutton").on('click',function(event){
        event.preventDefault();
        var msisdn = $("#msisdn").prop('value');
        var postid = $("#post-filter-menu").prop('value');
        var smscode = $("#smscode").prop('value');
        var point = $("#point").prop('value');
        var team = $("#team").prop('value');
        $.post('givpointhandler.php', "cmd=givpoint&postid="+postid+"&msisdn="+msisdn+"&smscode="+smscode+"&point="+point+"&team="+team, function (data) {
            obj = JSON.parse(data);
            if(obj.status) {
                window.location.href = '#pointgivet';
                $("#pointgivetcontainer").html(obj.message);
            }
            else {
                alert(obj.message);
            }
        });
    });
</script>
	
</body>
</html>