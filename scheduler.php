<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 05-05-2018
 * Time: 13:49
 */

setlocale(LC_ALL, "da_DK");
require 'vendor/autoload.php';
require 'config.php';

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$schedule = $_GET['schedule'];
if($schedule == '') die("Schedule not set");


// Incoming SMS
if($schedule == 'handleIncomingSMS') {
    $smsDB = new smsgwDbModel();
    $smss = $smsDB->getSMS();

    foreach ($smss as $sms) {
        print_r($sms);
        print("\n");
    }

    if($_GET['message'] == '' || $_GET['sender'] == '') {
        die("Empty parameters. Aborting");
    }
    if(preg_match("/[Cc]heck|[Tt]jek/",$_GET['message'])) {
        $checkinPostModel = new PostCheckInModel();
        $checkin = new PostCheckInController();
        $checkinPostModel->setSmscontent($_GET['message'],$_GET['sender']);
        $checkin->handleCheckin($checkinPostModel);
    }
    else {
        $SmsScoreModel = new SmsScoreModel();
        $SmsScoreModel->setSmscontent($_GET['message'],$_GET['sender']);
        $score->handleReceivedPoints($SmsScoreModel);
    }
}
else {
    die("Missing parameters: 'message' and 'sender'");
}