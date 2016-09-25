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
        $this->con = mysqli_connect(DBHOST, DBUSER, DBPASS, DB) or die("Error " . mysqli_error($this->con));
        // Check connection
        if (mysqli_connect_errno())
        {
            $this->logger("Failed to connect to MySQL: " . mysqli_connect_error());
            die("DB issue");
        }
    }

    public function insertTeam($name, $leader, $msisdn, $email, $kreds, $group, $numberofmembers) {
        $this->con->query("INSERT INTO ".DBPREFIX."_teams (name, leader, mobile, email, kreds, groups, numberofmembers, updated_at) VALUES ('$name', '$leader', '$msisdn', '$email', '$kreds', '$group', '$numberofmembers', now())");
        $this->logger->info(__METHOD__.": ".$group.$this->con->insert_id." $name, $kreds");
        return $this->con->insert_id;
    }

    public function insertNightCrew($name, $msisdn, $kreds) {
        $this->logger->info(__METHOD__.": $name, $kreds");
        $stmt = $this->con->prepare("INSERT INTO ".DBPREFIX."_nightpeople (name, mobile, kreds) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $msisdn, $kreds);
        $stmt->execute();
        return $stmt->sqlstate;
    }

    public function insertScore($team, $point, $post, $msisdn) {
        $this->logger->info(__METHOD__.": Point: $point, Post: $post, Hold: $team");
        $this->con->query("INSERT INTO ".DBPREFIX."_score (teamid, point, postid, creator, updated_at) VALUES ('$team', '$point', '$post', '$msisdn', now()) ON DUPLICATE KEY UPDATE point = '$point'");
    }

    public function insertCheckin($postid, $msisdn) {
        $sql = "INSERT INTO ".DBPREFIX."_postcheckin (mobile, postid, updated_at) VALUES ('$msisdn', '$postid', now()) ON DUPLICATE KEY UPDATE postid = '$postid' ";
        $this->logger->info(__METHOD__.": $sql");
        $this->con->query($sql);
    }

    public function getScore($group) {
        $score = array();
        if ($result = $this->con->query("select t.name team, concat(t.groups,t.id) cid, if(sum(s.point), sum(s.point), 0) point from ".DBPREFIX."_teams t left join ".DBPREFIX."_score s on s.teamid = t.id where t.groups = '$group' group by t.id order by point desc")) {
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
        if ($result = $this->con->query("SELECT if(sum(t.numberofmembers), sum(t.numberofmembers), 0) numberofmembers from ".DBPREFIX."_teams t")) {
            $row = mysqli_fetch_assoc($result);
            $numberofmembers = $row['numberofmembers'];
        }
        return $numberofmembers;
    }

    public function getCheckedinPost($msisdn) {
        $postid = 0;
        if ($result = $this->con->query("select postid from ".DBPREFIX."_postcheckin where mobile = '$msisdn'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['postid'];
        }
        $this->logger->info(__METHOD__.": $postid");
        return $postid;
    }

    public function getTeamPoints($team) {
        $point = 0;
        if ($result = $this->con->query("select sum(point) point from ".DBPREFIX."_score where teamid = '$team'")) {
            $row = mysqli_fetch_assoc($result);
            $point = $row['point'];
        }
        $this->logger->info(__METHOD__.": $team, $point");
        return $point;
    }

    public function insertTrace($msisdn, $input = "", $output = "") {
        $stmt = $this->con->prepare("INSERT INTO ".DBPREFIX."_trace (msisdn, method, input, output) VALUES (?, ?, ?, ?)");
        $method = $this->get_calling_function();
        $stmt->bind_param("ssss", $msisdn, $method, $input, $output);
        $stmt->execute();
    }

    public function __destruct()
    {
        $this->con->close();
    }
}
