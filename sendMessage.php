<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/09/16
 * Time: 21:30
 */

require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;

setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$send = new SendSmsModel();
$db = new DbModel();

$mobile = $db->queryToArray("select mobile from tivoli2016_teams");
foreach ($mobile as $m) {
    sleep(2);
    $send->sendSms($m['mobile'],"Hej. Placering fra dagsløbet kan ses på http://haugemedia.net/tivoli2016/");
}