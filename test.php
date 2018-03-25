<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 26-02-2018
 * Time: 17:07
 */


include "config.php";
include "vendor/autoload.php";
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});



$inboundSMS = "{\"msisdn\":\"FDF\",\"to\":\"4592452008\",\"messageId\":\"0B000000BAE28273\",\"text\":\"Test Test 555\",\"type\":\"text\",\"keyword\":\"TEST\",\"message-timestamp\":\"2018-02-26 20:44:18\"}";


$dbModel = new DbModel();

print $dbModel->insertSMS($inboundSMS);