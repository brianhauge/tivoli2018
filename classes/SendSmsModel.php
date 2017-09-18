<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/08/16
 * Time: 17:32
 *
 *
 * curl -i -X POST \
-H "Authorization:Bearer 4a6d9a74144a4fdda580b78991564bab" \
-H "Content-Type:application/json" \
-d \
'{
"from": "Mig Selv",
"to": [
"4525212002"
],
"body": "Test SMS"
}' \
'https://api.mblox.com/xms/v1/haugemedia13/batches'
 *
 */
class SendSmsModel extends BaseInit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendSms($msisdn, $message) {
        if(SMSGW == "app") {
            $msisdn = urlencode($msisdn);
            $message = urlencode($message);

            $curlargs = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => "http://".SMSGW_HOST.":".SMSGW_PORT."/send.html?smsto=".$msisdn."&smsbody=".$message."&smstype=sms",
                CURLOPT_USERAGENT => 'PHP Tivoli'
            );
        }
        else if (SMSGW == "mblox") {
            $postData = array("from" => SMS_FROMNAME, "to" => array($msisdn), "body" => $message);
            $curlargs = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_URL => SMSGW_HOST,
                CURLOPT_USERAGENT => 'PHP Tivoli',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: '.AUTH_TOKEN,
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
            );
        }
        else {
            die("'SMSGW' constant not set in config.php");
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curlargs);
        curl_exec($ch);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch)) {
            if ($info['http_code'] != "200" || $info['http_code'] != "201") {
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