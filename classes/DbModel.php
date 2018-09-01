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
     * @var mysqli
     */
    private $con;

    /**
     * DbModel constructor.
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
     * @param $name
     * @param $leader
     * @param $msisdn
     * @param $email
     * @param $kreds
     * @param $group
     * @param $numberofmembers
     * @return mixed
     */
    public function insertTeam($name, $leader, $msisdn, $email, $kreds, $group, $numberofmembers) {
        $this->con->query("INSERT INTO tivoli2018_teams (name, leader, mobile, email, kreds, groups, numberofmembers, updated_at) VALUES ('$name', '$leader', '$msisdn', '$email', '$kreds', '$group', '$numberofmembers', now())");
        $this->logger->info(__METHOD__.": ".$group.$this->con->insert_id." $name, $kreds");
        return $this->con->insert_id;
    }

    /**
     * @param $name
     * @param $msisdn
     * @param $kreds
     * @param $comment
     * @return string
     */
    public function insertCrew($name, $msisdn, $kreds, $comment, $gametype) {
        $this->logger->info(__METHOD__.": $name, $kreds, $gametype");
        $stmt = $this->con->prepare("INSERT INTO tivoli2018_crew (name, mobile, kreds, comment, gametype) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $msisdn, $kreds, $comment, $gametype);
        $stmt->execute();
        return $stmt->sqlstate;
    }

    /**
     * @param $team
     * @param $point
     * @param $post
     * @param $msisdn
     */
    public function insertScore($team, $point, $post, $msisdn) {
        $this->logger->info(__METHOD__.": $team, $point, $post");
        $this->con->query("INSERT INTO tivoli2018_score (teamid, point, postid, creator, updated_at) VALUES ('$team', '$point', '$post', '$msisdn', now()) ON DUPLICATE KEY UPDATE point = '$point'");
    }

    /**
     * @param $postid
     * @param $msisdn
     */
    public function insertCheckin($postid, $msisdn) {
        $this->logger->info(__METHOD__.": $postid");
        $this->con->query("INSERT INTO tivoli2018_postcheckin (mobile, postid, updated_at) VALUES ('$msisdn', '$postid', now()) ON DUPLICATE KEY UPDATE postid = '$postid' ");
    }

    /**
     * @param $group
     * @return array
     */
    public function getScore($group) {
        $score = array();
        if ($result = $this->con->query("select t.name team, concat(t.groups,t.id) cid, if(sum(s.point), sum(s.point), 0) point from tivoli2018_teams t left join tivoli2018_score s on s.teamid = t.id where t.groups = '$group' group by t.id order by point,t.id desc")) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $score[] = $row;
            }
        }
        return $score;
    }

    /**
     * @param $sql
     * @return array
     */
    public function queryToArray($sql) {
        $array = array();
        if ($result = $this->con->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $array[] = $row;
            }
        }
        return $array;
    }

    /**
     * @param $sql
     * @return array
     */
    public function printResultTable($sql) {
        $tmp = "<table class=\"table table-striped\">";
        $row_count = 0;
        if ($result = $this->con->query($sql)) {
            $fields_num = $result->field_count;
            $row_count = $result->num_rows;

            // Table Header
            $tmp .= "<tr>";
            for($i=0; $i<$fields_num; $i++) {
                $field = $result->fetch_field();
                $tmp .= "<th>{$field->name}</th>";
            }
            $tmp .= "</tr>\n";

            // Table Body
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

    /**
     * @return int
     */
    public function getMemberCount() {
        $numberofmembers = 0;
        if ($result = $this->con->query("SELECT if(sum(t.numberofmembers), sum(t.numberofmembers), 0) numberofmembers from tivoli2018_teams t")) {
            $row = mysqli_fetch_assoc($result);
            $numberofmembers = $row['numberofmembers'];
        }
        return $numberofmembers;
    }

    /**
     * @param $msisdn
     * @return int
     */
    public function getCheckedinPost($msisdn) {
        $postid = 0;
        if ($result = $this->con->query("select postid from tivoli2018_postcheckin where mobile = '$msisdn'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['postid'];
        }
        $this->logger->info(__METHOD__.": $postid");
        return $postid;
    }

    /**
     * @param $team
     * @return int
     */
    public function getTeamPoints($team) {
        $point = 0;
        if ($result = $this->con->query("select sum(point) point from tivoli2018_score where teamid = '$team'")) {
            $row = mysqli_fetch_assoc($result);
            $point = $row['point'];
        }
        $this->logger->info(__METHOD__.": $team, $point");
        return $point;
    }

    /**
     * @param $msisdn
     * @param string $input
     * @param string $output
     */
    public function insertTrace($msisdn, $input = "", $output = "") {
        $stmt = $this->con->prepare("INSERT INTO tivoli2018_trace (msisdn, method, input, output) VALUES (?, ?, ?, ?)");
        $method = $this->get_calling_function();
        $stmt->bind_param("ssss", $msisdn, $method, $input, $output);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param $user
     * @param $password
     * @return array
     */
    public function getUserInfo($user, $password) {
        $array = array();
        if ($stmt = $this->con->prepare("SELECT user,created,updated_at from tivoli2018_users where user = ? and password = sha2(?,256) limit 1")) {
            $stmt->bind_param("ss", $user, $password);
            $stmt->execute();
            $stmt->bind_result($user, $created, $updated);
            while ($stmt->fetch()) {
                $array['user'] = $user;
                $array['created'] = $created;
                $array['updated'] = $updated;
            }
            $stmt->close();
        }
        return $array;
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->con->close();
    }
}
