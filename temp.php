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
foreach ($postmandskab['msisdn'] as $msisdn) {
    print "$msisdn <br />";
}