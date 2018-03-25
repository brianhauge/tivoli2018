<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 09/08/16
 * Time: 18:23
 */
class DbModel extends BaseInit
{

    /**
     * DbModel constructor.
     */
    private $con;
    public function __construct()
    {
        parent::__construct();
        $this->con = mysqli_connect(DBHOST, DBUSER, DBPASS, DB, DBPORT) or die("Error " . mysqli_error($this->con));
        // Check connection
        if (mysqli_connect_errno())
        {
            $this->logger("Failed to connect to MySQL: " . mysqli_connect_error());
            die("DB issue");
        }
    }

    public function insertTeam($name, $leader, $msisdn, $email, $kreds, $group, $numberofmembers) {
        $this->con->query("INSERT INTO tivoli2018_teams (name, leader, mobile, email, kreds, groups, numberofmembers, updated_at) VALUES ('$name', '$leader', '$msisdn', '$email', '$kreds', '$group', '$numberofmembers', now())");
        $this->logger->info(__METHOD__.": ".$group.$this->con->insert_id." $name, $kreds");
        return $this->con->insert_id;
    }

    public function insertNightCrew($name, $msisdn, $kreds) {
        $this->logger->info(__METHOD__.": $name, $kreds");
        $stmt = $this->con->prepare("INSERT INTO tivoli2018_nightpeople (name, mobile, kreds) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $msisdn, $kreds);
        $stmt->execute();
        return $stmt->sqlstate;
    }

    public function insertScore($team, $point, $post, $msisdn) {
        $this->logger->info(__METHOD__.": $team, $point, $post");
        $this->con->query("INSERT INTO tivoli2018_score (teamid, point, postid, creator, updated_at) VALUES ('$team', '$point', '$post', '$msisdn', now()) ON DUPLICATE KEY UPDATE point = '$point'");
    }

    public function insertCheckin($postid, $msisdn) {
        $this->logger->info(__METHOD__.": $postid");
        $this->con->query("INSERT INTO tivoli2018_postcheckin (mobile, postid, updated_at) VALUES ('$msisdn', '$postid', now()) ON DUPLICATE KEY UPDATE postid = '$postid' ");
    }

    public function getScore($group) {
        $score = array();
        if ($result = $this->con->query("select t.name team, concat(t.groups,t.id) cid, if(sum(s.point), sum(s.point), 0) point from tivoli2018_teams t left join tivoli2018_score s on s.teamid = t.id where t.groups = '$group' group by t.id order by point desc")) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $score[] = $row;
            }
        }
        return $score;
    }

    public function queryToArray($sql) {
        $array = array();
        if ($result = $this->con->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $array[] = $row;
            }
        }
        return $array;
    }

    public function printResultTable($sql) {
        $tmp = "<table class=\"table table-striped\"><tr>";
        $row_count = 0;
        if ($result = $this->con->query($sql)) {
            $fields_num = $result->field_count;
            $row_count = $result->num_rows;
            for($i=0; $i<$fields_num; $i++) {
                $field = $result->fetch_field();
                $tmp .= "<th>{$field->name}</th>";
            }
            $tmp .= "</tr>\n";
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $tmp .= "<tr>";
                foreach($row as $cell)
                    $tmp .= "<td>$cell</td>";
                $tmp .= "</tr>\n";
            }
            $result->close();
        }
        return array("count" => $row_count, "table" => $tmp . "</table>");
    }

    public function getMemberCount() {
        $numberofmembers = 0;
        if ($result = $this->con->query("SELECT if(sum(t.numberofmembers), sum(t.numberofmembers), 0) numberofmembers from tivoli2018_teams t")) {
            $row = mysqli_fetch_assoc($result);
            $numberofmembers = $row['numberofmembers'];
        }
        return $numberofmembers;
    }

    public function getCheckedinPost($msisdn) {
        $postid = 0;
        if ($result = $this->con->query("select postid from tivoli2018_postcheckin where mobile = '$msisdn'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['postid'];
        }
        $this->logger->info(__METHOD__.": $postid");
        return $postid;
    }

    public function getTeamPoints($team) {
        $point = 0;
        if ($result = $this->con->query("select sum(point) point from tivoli2018_score where teamid = '$team'")) {
            $row = mysqli_fetch_assoc($result);
            $point = $row['point'];
        }
        $this->logger->info(__METHOD__.": $team, $point");
        return $point;
    }

    public function insertTrace($msisdn, $input = "", $output = "") {
        $stmt = $this->con->prepare("INSERT INTO tivoli2018_trace (msisdn, method, input, output) VALUES (?, ?, ?, ?)");
        $method = $this->get_calling_function();
        $stmt->bind_param("ssss", $msisdn, $method, $input, $output);
        $stmt->execute();
        $stmt->close();
    }

    public function getUserInfo($user, $password) {
        if ($stmt = $this->con->prepare("SELECT user,created,updated_at from tivoli2018_users where user = ? and password = sha2(?,256) limit 1")) {
            $stmt->bind_param("ss", $user, $password);
            $stmt->execute();
            $stmt->bind_result($user, $created, $updated);
            $array = array();
            while ($stmt->fetch()) {
                $array['user'] = $user;
                $array['created'] = $created;
                $array['updated'] = $updated;
            }
            $stmt->close();
            return $array;
        }
    }

    public function insertSMS($inboundJsonSMS, $direction) {
        $inboundSMS = json_decode($inboundJsonSMS, true);
        $inboundSMS['direction'] = $direction;
        $keys = "`".implode("`,`",array_keys($inboundSMS))."`";
        $values = "'".implode("','",$inboundSMS)."'";
        $sql = "INSERT INTO tivoli2018_smsgw ($keys) VALUES ($values)";
        if ($this->con->query($sql) === TRUE) {
            $stat = "New record created successfully - ".$inboundJsonSMS." - ".$sql;
        } else {
            $stat = "Error: \n\n" . $sql . "\n\n" . $this->con->error;
        }
        $this->logger->info(__METHOD__.": ".$stat);
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

    public function __destruct()
    {
        $this->con->close();
    }
}
