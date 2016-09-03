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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
    if(preg_match("/[Cc]heck|[Tt]jek/",$_GET['body'])) {
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
}

if(isset($_GET['logging']) && $_GET['code'] == LOGCODE) {
    $db = new DbModel();

    ?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#trace" aria-controls="trace" role="tab" data-toggle="tab">SMS Trafik</a></li>
            <li role="presentation"><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab">Hold Oversigt</a></li>
            <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">Log</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="trace">
                <?php
                // Trace
                $sql = "select DATE_FORMAT(tstamp, '%Y-%m-%d %H:%i:%S') tid,msisdn mobil,input modtaget,output sendt from tivoli2016_trace ORDER BY tstamp desc limit 20";
                $result = $db->printResultTable($sql);
                print($result['table']);
                ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="overview">
                <?php
                // Team Overview
                $sql = "select concat(t.groups,t.id) id, t.name holdnavn, t.kreds 'kreds / gruppe', t.leader holdleder, t.mobile mobil, t.email, t.numberofmembers antal, if(sum(s.point), sum(s.point), 0) point from tivoli2016_teams t left join tivoli2016_score s on s.teamid = t.id group by t.id order by id asc";
                $result = $db->printResultTable($sql);
                print("<h3>Antal hold: ".$result['count']." - Antal deltagere: ".$db->getMemberCount()."</h3>");
                print($result['table']);
                ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="log">
                <?php
                // Log
                print("<h3>Log fra denne server:</h3><pre style='font-size: 8px'>");
                $cmd = "tail -n50 logs/log_".date("Y-m-d").".txt";
                print(str_replace(PHP_EOL, '<br />', shell_exec($cmd)));
                print("</pre>");
                ?>
            </div>
        </div>
    </div>
    <?php
}

else {
?>
    <div class="page-header">
        <h1>Løbsplacering <small>Kl. <?php echo date("H:i"); ?></small></h1>
    </div>
    <h3 class="text-muted">0. - 4. klasse</h3>
    <?php print($score->getScoreTableByGroup("A")); ?>
    <h3 class="text-muted">5. - 8. klasse</h3>
    <?php print($score->getScoreTableByGroup("B")); ?>
    <h3 class="text-muted">9. klasse til 18 år</h3>
    <?php print($score->getScoreTableByGroup("C")); ?>
    <h3 class="text-muted">Natløb</h3>
    <?php print($score->getScoreTableByGroup("N")); ?>

<?php
}
?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
