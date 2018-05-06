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


// Handle incoming SMS queue
if($schedule == 'handleIncomingQueue') {
    $smsDB = new SmsgwDbModel();
    $smss = $smsDB->getSMS();

    foreach ($smss as $sms) {
        if(preg_match("/[Cc]heck|[Tt]jek/",$sms['text'])) {
            $checkinPostModel = new PostCheckInModel();
            $checkin = new PostCheckInController();
            $checkinPostModel->setSmscontent($sms['text'],$sms['msisdn']);
            $checkin->handleCheckin($checkinPostModel);
        }
        else {
            $SmsScoreModel = new SmsScoreModel();
            $score = new SmsScoreController();
            $SmsScoreModel->setSmscontent($sms['text'],$sms['msisdn']);
            $score->handleReceivedPoints($SmsScoreModel);
        }
    }
}