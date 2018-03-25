<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 27/09/17
 * Time: 14:17
 */

setlocale(LC_ALL, "da_DK");

/**
 * SMSGW APP Version
 **/
if(SMSGW == 'app') {
    if(isset($_GET['message']) && isset($_GET['sender'])) {

        if($_GET['message'] == '' || $_GET['sender'] == '') {
            die("Empty parameters. Aborting");
        }
        require 'vendor/autoload.php';

        spl_autoload_register(function ($class) {
            include 'classes/' . $class . '.php';
        });

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

/**
 * SMSGW Nexmo Version
 *
 * {
"msisdn": "447700900001",
"to": "447700900000",
"messageId": "0A0000000123ABCD1",
"text": "Hello world",
"type": "text",
"keyword": "Hello",
"message-timestamp": "2020-01-01T12:00:00.000+00:00",
"timestamp": "1578787200",
"nonce": "aaaaaaaa-bbbb-cccc-dddd-0123456789ab",
"concat": "true",
"concat-ref": "1",
"concat-total": "3",
"concat-part": "2",
"data": "abc123",
"udh": "abc123"
}
 *
 * {"msisdn":"FDF","to":"4592452008","messageId":"0B000000BAE28273","text":"Test Test 555","type":"text","keyword":"TEST","message-timestamp":"2018-02-26 20:44:18"}
 *
 **/
else if (SMSGW == "nexmo") {
    //file_put_contents("received.txt",json_encode($_REQUEST));
    $dbModel = new DbModel();

    print $dbModel->insertSMS($_REQUEST);
}


