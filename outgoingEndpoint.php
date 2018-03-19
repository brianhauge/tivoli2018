<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 01/10/17
 * Time: 19:13
 */

setlocale(LC_ALL, "da_DK");
require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

function trim_value(&$value)
{
    $value = trim($value);
}

if(isset($_REQUEST['message']) && isset($_REQUEST['receivers'])) {
    if($_REQUEST['message'] == '' || $_REQUEST['receivers'] == '') {
        $tmp['message'] = "Empty parameters. 'message': ".$_REQUEST['message']." 'receiver': ".$_REQUEST['receivers'];
        $tmp['status'] = false;
    }

    else {
        $msisdn = explode(';',$_REQUEST['receivers']);
        array_walk($msisdn, 'trim_value');
        $message = $_REQUEST['message'];

        $send = new SendSmsModel();
        if(isset($_REQUEST['smsgw'])) $send->setSmsgw($_REQUEST['smsgw']);
        if(isset($_REQUEST['from'])) $send->setFrom($_REQUEST['from']);
        $result = $send->sendSms($msisdn,$message);
        $tmp['message'] = $result;
        $tmp['status'] = true;
    }
}

else {
    $tmp['message'] = "Missing parameters. 'message' and 'receiver'";
    $tmp['status'] = false;
}

print(json_encode($tmp));