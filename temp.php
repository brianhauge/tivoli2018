<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/09/16
 * Time: 12:17
 */

setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$db = new DbModel();
$sms = new SendSmsModel();

$postmandskab = $db->queryToArray("select * from tivoli2016_postcheckin");
$sms->sendSms("+4525212002","Lidt info. Der kan gives mellem 1-100 point, brug jeres egen vurdering og sunde fornuft til at give point. En mobil kan kun tjekke ind på en post af gangen.");
foreach ($postmandskab as $m) {
    $sms->sendSms($m['mobile'],"Lidt info. Der kan gives mellem 1-100 point, brug jeres egen vurdering og sunde fornuft til at give point. En mobil kan kun tjekke ind på en post af gangen.");
}