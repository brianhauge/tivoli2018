<?php

/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 05-05-2018
 * Time: 13:27
 */
class SmsgwDbModel extends BaseInit
{
    /**
     * @var mysqli
     */
    private $con;

    /**
     * SmsgwDbModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->con = mysqli_connect(DBHOST, DBUSER, DBPASS, DB, DBPORT) or die("Error " . mysqli_error($this->con));
        // Check connection
        if (mysqli_connect_errno())
        {
            $this->logger->error(__METHOD__.": Failed to connect to MySQL: " . mysqli_connect_error());
            die("DB issue");
        }
    }

    /**
     * @param array $inboundSMS
     * @return bool
     */
    public function insertIncomingSMS(array $inboundSMS) {
        $inboundJsonSMS = json_encode($inboundSMS);
        $messageId = $inboundSMS['messageId'];
        $this->logger->info(__METHOD__.": ". $messageId ." - Received message " . $inboundJsonSMS);
        if($inboundSMS['to'] === $inboundSMS['msisdn']) {
            $this->logger->warning(__METHOD__.": ". $messageId ." - Receiver and sender are the same, cancelling. 'to': " . $inboundSMS['to'] . " 'msisdn': " . $inboundSMS['msisdn']);
        }
        else if($inboundSMS['text'] === "") {
            $this->logger->warning(__METHOD__.": ". $messageId ." - Text in message empty, cancelling.");
        }
        else {
            $inboundSMS['direction'] = 'in';
            $keys = "`".implode("`,`",array_keys($inboundSMS))."`";
            $values = "'".implode("','",$inboundSMS)."'";
            $sql = "INSERT INTO tivoli2018_smsgw ($keys) VALUES ($values)";
            if ($this->con->query($sql) === TRUE) {
                $this->logger->info(__METHOD__.": ". $messageId ." - New DB record created successfully");
                return true;
            } else {
                $this->logger->warning(__METHOD__.": ". $messageId ." - Error: \n\n" . $sql . "\n\n" . $this->con->error);
            }
        }
        return false;
    }

    /**
     * @param \Nexmo\Message\Message $transaction
     */
    public function insertOutgoingSMS($transaction, $message) {

        $msisdn = $transaction->getFrom();
        $to = $transaction->getTo();
        $direction = 'out';
        $type = 'text';
        $messageId = $transaction->getMessageId();
        $messageTimestamp = $transaction->getDateReceived()->format('Y-m-d H:i:s');
        $timestamp = '';
        $messageCount = $transaction->count();
        if($messageCount < 2) {
            $concat = 'false';
            $concatRef = '';
            $concatTotal = '';
        }
        else {
            $concat = 'true';
            $concatRef = '';
            $concatTotal = '$messageCount';
        }
        $messagePrice = $transaction->getPrice();
        $remainingPrice = $transaction->getRemainingBalance();
        $errorCode = $transaction->getStatus();
        if($transaction->getStatus() === 0) {
            $status = 'sent';
        } else {
            $status = 'failed';
        }

        $stmt = $this->con->prepare("INSERT INTO tivoli2018_smsgw (`msisdn`,`to`,`direction`,`text`,`type`,`messageId`,`message-timestamp`,`timestamp`,`concat`,`concat-ref`,`concat-total`,`price`,`remaining-balance`,`status`,`err-code`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssss", $msisdn, $to, $direction, $message, $type, $messageId, $messageTimestamp, $timestamp, $concat, $concatRef, $concatTotal, $messagePrice, $remainingPrice, $status, $errorCode);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param int $limit
     * @param string $direction
     * @return array
     */
    public function getSMS($limit = 20, $direction = 'in') {
        $array = array();
        if ($result = $this->con->query("SELECT * FROM tivoli2018_smsgw where status in ('notProcessed') AND direction = '".$direction."' LIMIT ".$limit." FOR UPDATE")) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->con->query("UPDATE tivoli2018_smsgw set status = 'processing' WHERE id = ".$row['id']);
                $array[] = $row;
            }
        }
        return $array;
    }

    /**
     * @param $smsid
     * @param $status
     * @return bool
     */
    public function updateStatus($smsid, $status)
    {
        $sql = "UPDATE tivoli2018_smsgw set status = '".$status."' WHERE id = ".$smsid;
        if ($this->con->query($sql) === TRUE) {
            return true;
        } else {
            $this->logger->warning(__METHOD__.": ". $smsid ." - Error: \n\n" . $sql . "\n\n" . $this->con->error);
        }
        return false;
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->con->close();
    }
}



/*


INBOUND
{
"messageId": "0A0000000123ABCD1",
"msisdn": "447700900001",
"to": "447700900000",
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

DLVR
{
"messageId": "0A0000001234567B",
"msisdn": "447700900000",
"to": "Acme Inc",
"message-timestamp": "2020-01-01T12:00:00.000+00:00"
"network-code": "12345",
"price": "0.03330000",
"status": "delivered",  <-- delivered, expired, failed, rejected, accepted, buffered, unknown
"scts": "2001011400",
"err-code": "0",
}


*/