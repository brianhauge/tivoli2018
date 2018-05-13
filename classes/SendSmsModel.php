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
    private $smsgw = SMSGW;
    private $from = SMS_FROMNAME;
    private $smsDB;

    public function __construct()
    {
        parent::__construct();
        $this->smsDB = new SmsgwDbModel();
    }

    public function sendSms($msisdn, $message, $smsid = 0)
    {
        if (!is_array($msisdn)) $msisdn = array($msisdn);
        if ($this->smsgw == "app") {
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

        } else if ($this->smsgw == "mblox") {
            $postData = array("from" => $this->from, "to" => $msisdn, "body" => $message);
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
        else if ($this->smsgw == "nexmo") {
            $basic = new \Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET);
            $client = new \Nexmo\Client($basic);
            $status = 0;
            foreach ($msisdn as $ms) {
                try {
                    $text = new \Nexmo\Message\Text($ms, $this->from, $message);
                    $transaction = $client->message()->send($text);
                    $this->logger->info(__METHOD__.": ". $transaction->getMessageId() ." - Sent message to ". $ms . " Message: " . $message);
                    file_put_contents("testhest.txt",json_encode($transaction->getResponseData()));

                } catch (Nexmo\Client\Exception\Request $e) {
                    //can still get the API response
                    $transaction = $e->getEntity();
                    $request = $transaction->getRequest(); //PSR-7 Request Object
                    $response = $transaction->getResponse(); //PSR-7 Response Object
                    $data = $transaction->getResponseData(); //parsed response object
                    $code = $e->getCode(); //nexmo error code
                    error_log($e->getMessage()); //nexmo error message

                    $this->logger->error(__METHOD__.": Problem sending message to ". $ms . " Error message: " . $e->getMessage() . " Trace: " . $e->getTraceAsString());
                }
                $status = $status + $transaction->getStatus();
                $this->returnmessage[$ms] = $transaction->getResponseData();
                // Update and Insert to SMSGW DB
                if($smsid != 0) {
                    if ($transaction->getStatus() == 0) {
                        $this->smsDB->updateStatus($smsid, 'processed');
                    } else {
                        $this->smsDB->updateStatus($smsid, 'failed');
                    }
                }
            }
            if($status > 0) {
                $this->returnmessage['code'] = 500;
            }
            else {
                $this->returnmessage['code'] = 200;
            }
            
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

    public function setSmsgw($smsgw) {
        if(in_array($smsgw,AVAILABLE_SMSGW)) $this->smsgw = $smsgw;
        else $this->logger->warning($smsgw. " not available, see config for more. Rolling back to ".SMSGW);
    }

    public function setFrom($from) {
        $this->from = $from;
    }
}