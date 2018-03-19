<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 26-02-2018
 * Time: 17:07
 */


include "config.php";
include "vendor/autoload.php";

$msisdn = 25212002;
$message = "Noget rigtigt godt";

try{
    $basic  = new \Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET);
    $client = new \Nexmo\Client($basic);
    $text = new \Nexmo\Message\Text($msisdn, SMS_FROMNAME, $message);
    $message = $client->message()->send($text);
} catch (Nexmo\Client\Exception\Request $e) {
    //can still get the API response
    $message     = $e->getEntity();
    $request  = $message->getRequest(); //PSR-7 Request Object
    $response = $message->getResponse(); //PSR-7 Response Object
    $data     = $message->getResponseData(); //parsed response object
    $code     = $e->getCode(); //nexmo error code
    error_log($e->getMessage()); //nexmo error message
}