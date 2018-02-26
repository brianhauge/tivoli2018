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
    private $returnmessage = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function sendSms($msisdn, $message)
    {
        if (!is_array($msisdn)) $msisdn = array($msisdn);
        if (SMSGW == "app") {
            foreach ($msisdn as $ms) {
                $ms = urlencode($ms);
                $message = urlencode($message);

                $curlargs = array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => "http://" . SMSGW_HOST . ":" . SMSGW_PORT . "/send.html?smsto=" . $ms . "&smsbody=" . $message . "&smstype=sms",
                    CURLOPT_USERAGENT => 'PHP Tivoli'
                );
                $this->doCurl($curlargs);
            }

        } else if (SMSGW == "mblox") {
            $postData = array("from" => SMS_FROMNAME, "to" => $msisdn, "body" => $message);
            $curlargs = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_URL => SMSGW_HOST,
                CURLOPT_USERAGENT => 'PHP Tivoli',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . AUTH_TOKEN,
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postData)
            );
            $this->doCurl($curlargs);
        }
        else if (SMSGW == "nexmo") {



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

            $this->returnmessage['smsgw'] = json_decode($message);
            $this->returnmessage['code'] = "Sikkert 200";

        } else {
            die("'SMSGW' constant not set correct in config.php");
        }
        return $this->returnmessage;
    }
    private function doCurl($curlargs) {
        $ch = curl_init();
        curl_setopt_array($ch, $curlargs);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch)) {
            if ($info['http_code'] == "200" || $info['http_code'] == "201") {
                $this->logger->info(__METHOD__.": SMSGW Response: ".$content);
            } else {
                $this->logger->error(__METHOD__.": Unexpected SMSGW Response: ".$content);
            }
        }
        else {
            $this->logger->error(__METHOD__.": Unexpected issue calling SMSGW ". $info['url']." - Response code: ".$info['http_code']);
        }
        $this->returnmessage['smsgw'] = json_decode($content);
        $this->returnmessage['code'] = $info['http_code'];
        curl_close($ch);
    }
}