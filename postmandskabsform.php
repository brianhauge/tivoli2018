<?php
session_start();
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 27-07-2018
 * Time: 11:54
 */
if(isset($_SESSION['kreds'])) {
    $kreds = $_SESSION['kreds'];
} else {
    $kreds = "";
}

?>
<form class="form-horizontal tilmeld" method="post" id="postmandskab<?php print($_GET['mandskabnum']); ?>">
    <div class="well1">
        <p class="postheader"><b>Postmandskab <?php print($_GET['mandskabnum']); ?></b></p>
        <div class="row">

<div class="col-sm-3">
    <div class="form-group">
        <label class="control-label sr-only">Navn</label>
        <div class="col-md-12 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" class="form-control" name="name" placeholder="Navn">
            </div>
        </div>
    </div>
</div>
<div class="col-sm-2">
    <div class="form-group">
        <label class="control-label sr-only">Mobil</label>
        <div class="col-md-12 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                <input name="mobile" placeholder="Mobil" class="form-control" type="tel">
            </div>
        </div>
    </div>
</div>

<div class="col-sm-3">
    <div class="form-group">
        <label class="control-label sr-only">Kreds / Gruppe</label>
        <div class="col-md-12 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                <input name="kreds" placeholder="Kreds / Gruppe" class="form-control" type="text" value="<?php print($_SESSION['kreds']); ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label class="control-label sr-only">Kommentar</label>
        <div class="col-md-12 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                <textarea class="form-control" rows="1" name="comment" placeholder="Kommentar"></textarea>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</form>