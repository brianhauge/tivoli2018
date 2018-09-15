<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */

session_start();
if(!isset($_SESSION['loggedin'])) {
    header('Location: oprethold.php?gametype=n');
}
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
    <link rel="canonical" href="http://haugemedia.net/tivoli2018/">
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
?>
    <div class="page-header">
        <h1>Løbsplacering <small>Kl. <?php echo date("H:i"); ?></small></h1>
    </div>
    <?php if(!isset($_GET['natloeb'])) { ?>
    <h3 class="text-muted">0. - 4. klasse - Professor</h3>
    <?php print($score->getScoreTableByGroup("A")); ?>
    <h3 class="text-muted">5. - 8. klasse - DIP</h3>
    <?php print($score->getScoreTableByGroup("B")); ?>
    <h3 class="text-muted">9. klasse - 18 år - Militær</h3>
    <?php print($score->getScoreTableByGroup("C")); ?>
    <h3 class="text-muted">Kun voksne</h3>
    <?php print($score->getScoreTableByGroup("V")); ?>

    <?php } else { ?>
    <h3 class="text-muted">Natløb</h3>
    <?php print($score->getScoreTableByGroup("N")); }

?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>