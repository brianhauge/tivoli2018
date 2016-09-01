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
        $this->con->query("INSERT INTO tivoli2016_teams (name, leader, mobile, email, kreds, groups, numberofmembers, updated_at) VALUES ('$name', '$leader', '$msisdn', '$email', '$kreds', '$group', '$numberofmembers', now())");
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": ".$group.$this->con->insert_id." $name, $kreds");
        return $this->con->insert_id;
    }

    public function insertNightCrew($name, $msisdn, $kreds) {
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": $name, $kreds");
        $stmt = $this->con->prepare("INSERT INTO tivoli2016_nightpeople (name, mobile, kreds) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $msisdn, $kreds);
        $stmt->execute();
        return $stmt->sqlstate;
    }

    public function insertScore($team, $point, $post, $msisdn) {
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": $team, $point, $post");
        $this->con->query("INSERT INTO tivoli2016_score (teamid, point, postid, creator, updated_at) VALUES ('$team', '$point', '$post', '$msisdn', now()) ON DUPLICATE KEY UPDATE point = '$point'");
    }

    public function insertCheckin($postid, $msisdn) {
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": $postid");
        $this->con->query("INSERT INTO tivoli2016_postcheckin (mobile, postid, updated_at) VALUES ('$msisdn', '$postid', now()) ON DUPLICATE KEY UPDATE postid = '$postid' ");
    }

    public function getScore($group) {
        $score = array();
        if ($result = $this->con->query("select t.name team, if(sum(s.point), sum(s.point), 0) point from tivoli2016_teams t left join tivoli2016_score s on s.teamid = t.id where t.groups = '$group' group by t.id order by point desc")) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $score[] = $row;
            }
        }
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": Group ".$group);
        return $score;
    }

    public function getCheckedinPost($msisdn) {
        $postid = 0;
        if ($result = $this->con->query("select postid from tivoli2016_postcheckin where mobile = '$msisdn'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['postid'];
        }
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": ".$postid);
        return $postid;
    }

    public function getTeamPoints($team) {
        $postid = 0;
        if ($result = $this->con->query("select sum(point) point from tivoli2016_score where teamid = '$team'")) {
            $row = mysqli_fetch_assoc($result);
            $postid = $row['point'];
        }
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": $team, $postid");
        return $postid;
    }

    public function insertTrace($msisdn, $input = "", $output = "") {
        $stmt = $this->con->prepare("INSERT INTO tivoli2016_trace (msisdn, method, input, output) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $msisdn, $this->get_calling_function(), $input, $output);
        $stmt->execute();
    }

    public function __destruct()
    {
        $this->con->close();
    }
}
