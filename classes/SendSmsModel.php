<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/08/16
 * Time: 17:32
 */
class SendSmsModel extends BaseInit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendSms($receiver, $message) {
        $receiver = urlencode($receiver);
        $message = urlencode($message);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://127.0.0.1:8585/send.html?smsto=".$receiver."&smsbody=".$message."&smstype=sms",
            CURLOPT_USERAGENT => 'PHP Tivoli'
        ));
        $resp = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        $this->logger->info(__CLASS__." > ".__FUNCTION__.": Sending SMS: '".urldecode($message)."' To: ".urldecode($receiver). " - Response code: ".$info['http_code']);
    }

}