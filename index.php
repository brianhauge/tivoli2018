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
<?php

setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$score = new SmsScoreController();

if(isset($_GET['body']) && isset($_GET['sender'])) {
    print("<h3>Getters:</h3><pre>");
    print_r($_GET);
    if($_GET['body'] == '' || $_GET['sender'] == '') {
        die("<br /><br /><div class=\"alert alert-danger\" role=\"alert\">Empty fields. Aborting</div>");
    }
    if(preg_match("/[Cc]heck|[Ttjek]/",$_GET['body'])) {
        $checkinPostModel = new PostCheckInModel();
        $checkin = new PostCheckInController();
        $checkinPostModel->setSmscontent($_GET['body'],$_GET['sender']);
        $checkin->handleCheckin($checkinPostModel);
    }
    else {
        $SmsScoreModel = new SmsScoreModel();
        $SmsScoreModel->setSmscontent($_GET['body'],$_GET['sender']);
        $score->handleReceivedPoints($SmsScoreModel);
    }
    print("</pre><h3>Log:</h3><pre style='font-size: 8px'>");
    print(str_replace(PHP_EOL, '<br />', shell_exec("tail -n50 logs/log_2016-08-18.txt")));
    print("</pre>");
}

else {
?>
    <div class="page-header">
        <h1>Løbsplacering <small>Kl. <?php echo date("H:i"); ?></small></h1>
    </div>
    <h3 class="text-muted">0. - 4. klasse</h3>
    <?php print($score->getScoreTableByGroup(1)); ?>
    <h3 class="text-muted">5. - 8. klasse</h3>
    <?php print($score->getScoreTableByGroup(2)); ?>
    <h3 class="text-muted">9. klasse til 18 år</h3>
    <?php print($score->getScoreTableByGroup(3)); ?>
    <h3 class="text-muted">Natløb</h3>
    <?php print($score->getScoreTableByGroup(9)); ?>

<?php
}
?>
        </div>
    </div>
</div>
</body>
</html>
