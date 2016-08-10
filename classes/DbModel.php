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

    public function insertScore($team, $point, $post, $creator) {
        $this->con->query("INSERT INTO tivoli2016_score (teamid, point, postid, creator, updated_at) VALUES ('$team', '$point', '$post', '$creator', now()) ON DUPLICATE KEY UPDATE point = '$point'");
    }

    public function getScore() {
        $score = array();
        if ($result = $this->con->query("SELECT * FROM tivoli2016_score")) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $score[] = $row;
            }
        }
        return $score;
    }

    public function __destruct()
    {
        $this->con->close();
    }
}