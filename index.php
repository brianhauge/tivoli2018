<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */

setlocale(LC_ALL, "da_DK");

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$db = new DbModel();

if(isset($_GET['body'])) {
    $smsBody = new IncomingSmsScoreModel();
    $smsBody->setSmscontent($_GET['body'],$_GET['sender']);

    print("SMS Content: ".$smsBody->getSmscontent() . " Point: " . $smsBody->getPoint() . " Post: " . $smsBody->getPost() . " Hold: " . $smsBody->getTeam());


    $db->insertScore($smsBody->getTeam(), $smsBody->getPoint(), $smsBody->getPost(), $smsBody->getSender());
}

else {
?>
<!DOCTYPE html>
<html lang="da">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Brian Hauge Hansen">
        <meta name="description" content="FDF og spejderne indtager Tivoli">
        <title>
            FDF og spejderne indtager Tivoli - Score
        </title>
        <link rel="canonical" href="http://haugemedia.net/tivoli2016/">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="stylesheet" href="vendor/twitter/bootstrap/dist/css/bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="vendor/twitter/bootstrap/dist/css/bootstrap-theme.min.css" type="text/css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="page-header">
                    <h1>Løbsplacering <small>Klokken <?php echo date("H:i"); ?></small></h1>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Placering</th>
                        <th>Hold</th>
                        <th>Point</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $counter = 1;
                    foreach ($db->getScore() as $score) {
                        print("<tr>");
                        print("<th placering='row'>$counter</th>");
                        print("<td>" . $score['team'] . "</td>");
                        print("<td><span class=\"badge\">" . $score['point'] . "</span></td>");
                        print("</tr>");
                        $counter++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
<?php
}
