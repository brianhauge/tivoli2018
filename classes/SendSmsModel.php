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

    public function sendSms($msisdn, $message) {
        $msisdn = urlencode($msisdn);
        $message = urlencode($message);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://".SMSGW_HOST.":".SMSGW_PORT."/send.html?smsto=".$msisdn."&smsbody=".$message."&smstype=sms",
            CURLOPT_USERAGENT => 'PHP Tivoli'
        ));
        curl_exec($ch);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch)) {
            if ($info['http_code'] != "200") {
                $this->logger->error(__METHOD__.": Unexpected HTTP answer from SMSGW ". $info['url']." - Response code: ".$info['http_code']);
            } else {
                $this->logger->info(__METHOD__.": ".urldecode($message)." To: ".urldecode($msisdn). " - Response code: ".$info['http_code']);
            }
        }
        else {
            $this->logger->error(__METHOD__.": Unexpected issue calling SMSGW ". $info['url']." - Response code: ".$info['http_code']);
        }
        curl_close($ch);
    }

    public function tjekInSMS($message, $msisdn = "12345678") {
        $msisdn = urlencode($msisdn);
        $message = urlencode($message);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://tivoli/?sender=".$msisdn."&body=".$message,
            CURLOPT_USERAGENT => 'PHP Tivoli'
        ));
        curl_exec($ch);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch)) {
            if ($info['http_code'] != "200") {
                $this->logger->error(__METHOD__.": Unexpected HTTP answer from SMSGW ". $info['url']." - Response code: ".$info['http_code']);
            } else {
                $this->logger->info(__METHOD__.": ".urldecode($message)." To: ".urldecode($msisdn). " - Response code: ".$info['http_code']);
            }
        }
        else {
            $this->logger->error(__METHOD__.": Unexpected issue calling SMSGW ". $info['url']." - Response code: ".$info['http_code']);
        }
        curl_close($ch);
    }

}