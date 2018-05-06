<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 27/09/17
 * Time: 14:17
 */

setlocale(LC_ALL, "da_DK");
require 'vendor/autoload.php';
require 'config.php';

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

/**
 * SMSGW APP Version
 **/
if(SMSGW == 'app') {
    if(isset($_GET['message']) && isset($_GET['sender'])) {

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
}

else if (SMSGW == "nexmo") {
    $dbModel = new SmsgwDbModel();
    $dbModel->insertSMS($_REQUEST,'in');
}