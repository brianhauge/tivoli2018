<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 27/09/17
 * Time: 14:17
 */


setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$score = new SmsScoreController();

if(isset($_GET['body']) && isset($_GET['sender'])) {
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