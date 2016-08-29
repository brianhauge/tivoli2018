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

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://".SMSGW_HOST.":".SMSGW_PORT."/send.html?smsto=".$msisdn."&smsbody=".$message."&smstype=sms",
            CURLOPT_USERAGENT => 'PHP Tivoli'
        ));
        curl_exec($curl);
        $info = curl_getinfo($curl);

        if (!curl_errno($curl)) {
            switch ($info['http_code']) {
                case 200:  # OK
                    $this->logger->info(__CLASS__." > ".__FUNCTION__.": Sending SMS: '".urldecode($message)."' To: ".urldecode($msisdn). " - Response code: ".$info['http_code']);
                default:
                    $this->logger->error(__CLASS__." > ".__FUNCTION__.": Unexpected HTTP answer from SMSGW ". $info['url']." - Response code: ".$info['http_code']);
            }
        }
        else {
            $this->logger->error(__CLASS__." > ".__FUNCTION__.": Unexpected issue calling SMSGW ". $info['url']." - Response code: ".$info['http_code']);
        }


        curl_close($curl);
    }

}