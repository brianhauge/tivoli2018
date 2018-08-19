<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 11/08/18
 * Time: 14:41
 */

session_start();
include "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>

<body>

<!-- Start Tjekin -->
<div data-role="page" id="tjekind">

    <div data-role="header">
        <h1>Tjekind</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">
        <p>
            <label for="text-basic">Post Nummer</label>
            <input type="text" name="text-basic" id="text-basic" value="">
        </p>
        <p>
            <label for="number-pattern">Mobil</label>
            <input type="number" name="number" pattern="[0-9]*" id="number-pattern" value="">
        </p>
        <p>
            <a href="#verificertjekind" class="ui-shadow ui-btn ui-corner-all">Tjekind</a>
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
            <input type="text" name="text-basic" id="text-basic" value="">
        </p>
        <p>
            <a href="#givpoint" class="ui-shadow ui-btn ui-corner-all">Tjekind</a>
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
            <label for="text-basic">Holdnummer</label>
            <input type="text" name="text-basic" id="text-basic" value="">
        </p>
        <p>
            <label for="number-pattern">Point 1-100</label>
            <input type="number" name="number" pattern="[0-9]*" id="number-pattern" value="">
        </p>
        <p>
            <a href="#pointgivet" class="ui-shadow ui-btn ui-corner-all">Giv point</a>
        </p>
    </div><!-- /content -->
</div><!-- /page -->

<!-- Start Point Givet -->
<div data-role="page" id="pointgivet">

    <div data-role="header">
        <h1>Point Givet</h1>
    </div><!-- /header -->

    <div role="main" class="ui-content">
        <p>Holdet har nu fået alle deres point</p>
        <p><a href="#givpoint"><< Gå tilbage</a></p>
    </div><!-- /content -->
</div><!-- /page -->



</body>
</html>