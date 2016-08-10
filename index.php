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
    print("<head></head><body><pre>");
    print_r($db->getScore());
    print("</pre></body>");
}

