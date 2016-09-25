<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Brian Hauge Hansen">
    <meta name="description" content="Aktiv Lejr Natløb">
    <title>Aktiv Lejr Natløb</title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
<?php

setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
include 'config.php';


if(isset($_GET['body']) && isset($_GET['sender'])) {
    if($_GET['body'] == '' || $_GET['sender'] == '') {
        $trace = new DbModel();
        $trace->insertTrace(0,"","N/A");
        die("<br /><br /><div class=\"alert alert-danger\" role=\"alert\">Empty fields. Aborting</div>");
    }
    if(preg_match("/[Cc]heck|[Tt]jek/",$_GET['body'])) {
        $checkinPostModel = new PostCheckInModel();
        $checkin = new PostCheckInController();
        $checkinPostModel->setSmscontent($_GET['body'],$_GET['sender']);
        $checkin->handleCheckin($checkinPostModel);
    }
    else {
        $SmsScoreModel = new SmsScoreModel();
        $score = new SmsScoreController();
        $SmsScoreModel->setSmscontent($_GET['body'],$_GET['sender']);
        $score->handleReceivedPoints($SmsScoreModel);
    }
}

else if(isset($_GET['logging']) && $_GET['code'] == LOGCODE) {
    $db = new DbModel();
    ?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#trace" aria-controls="trace" role="tab" data-toggle="tab">SMS Trafik</a></li>
            <li role="presentation"><a href="#teamoverview" aria-controls="teamoverview" role="tab" data-toggle="tab">Hold Oversigt</a></li>
            <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">Log</a></li>
            <li role="presentation"><a href="#postoverview" aria-controls="postoverview" role="tab" data-toggle="tab">Post / Point Oversigt</a></li>
            <li role="presentation"><a href="#smsflow" aria-controls="smsflow" role="tab" data-toggle="tab">SMS Flow</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="trace">
                <div class="col-md-2 col-md-offset-5"><br /><br /><img src="dist/gears.gif" ></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="teamoverview">
                <div class="col-md-2 col-md-offset-5"><br /><br /><img src="dist/gears.gif" ></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="log">
                <div class="col-md-2 col-md-offset-5"><br /><br /><img src="dist/gears.gif" ></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="postoverview">
                <div class="col-md-2 col-md-offset-5"><br /><br /><img src="dist/gears.gif" ></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="smsflow">
				<p class="bg-primary" style="padding: 15px;"><a href="Tivoli_2016_SMS_Flow.pdf" style="color: #FFF">Download Tivoli 2016 SMS Flow.pdf</a></p>
                <p><img src="Tivoli_2016_SMS_Flow.png" width="800" /></p>
            </div>
        </div>
    </div>

    <?php
}

else {
    $score = new SmsScoreController(); ?>
    <div class="page-header">
        <h1>Løbsplacering <small>Kl. <?php echo date("H:i"); ?></small></h1>
    </div>
    <h3 class="text-muted">Natløb</h3>
    <?php print($score->getScoreTableByGroup("H"));
} ?>

        </div>
    </div>
</div>
<script src="dist/js/jquery-3.1.0.min.js" integrity="cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" ></script>
<script src="dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<?php if(isset($_GET['logging']) && $_GET['code'] == LOGCODE) { ?>
<script type="text/javascript">
    $().ready(function() {
        $("#postoverview").load('overview.php?section=postoverview&code=<?php print(LOGCODE); ?>');
        $("#log").load('overview.php?section=log&code=<?php print(LOGCODE); ?>');
        $("#teamoverview").load('overview.php?section=teamoverview&code=<?php print(LOGCODE); ?>');
        $("#trace").load('overview.php?section=trace&code=<?php print(LOGCODE); ?>');


        setInterval(function(){ $("#postoverview").load('overview.php?section=postoverview&code==<?php print(LOGCODE); ?>'); }, 60000);
        setInterval(function(){ $("#log").load('overview.php?section=log&code==<?php print(LOGCODE); ?>'); }, 10000);
        setInterval(function(){ $("#teamoverview").load('overview.php?section=teamoverview&code==<?php print(LOGCODE); ?>'); }, 60000);
        setInterval(function(){ $("#trace").load('overview.php?section=trace&code==<?php print(LOGCODE); ?>'); }, 10000);

        $('#postoverview').on('focus', '.bg-success, .bg-warning, .bg-danger', function () {
            $( this ).popover('show');
        });
    });

</script>
<? } ?>
</body>
</html>
