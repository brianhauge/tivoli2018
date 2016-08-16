<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 15/08/16
 * Time: 10:30
 */
class CreateTeamController extends BaseInit
{
    var $dbModel;
    var $teamstatus = Array();
    /**
     * CreateTeamController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dbModel = new DbModel();
        $this->teamstatus = Array("status"=>false, "message"=>"");
    }

    public function insertTeam(CreateTeamModel $teamModel) {
        if ($teamModel->getName() === "") {
            $this->teamstatus['message'] = "Navn mangler";
        } elseif ($teamModel->getLeader() === "") {
            $this->teamstatus['message'] = "Holdleder mangler";
        } elseif ($teamModel->getMobile() === "") {
            $this->teamstatus['message'] = "Mobil mangler";
        } elseif ($teamModel->getEmail() === "") {
            $this->teamstatus['message'] = "Email mangler";
        } elseif ($teamModel->getKreds() === "") {
            $this->teamstatus['message'] = "Kreds / Gruppe mangler";
        } elseif ($teamModel->getGroup() === "") {
            $this->teamstatus['message'] = "Løbsgruppe mangler";
        } else {
            $teamid = $this->dbModel->insertTeam($teamModel->getName(), $teamModel->getLeader(), $teamModel->getMobile(),$teamModel->getEmail(),$teamModel->getKreds(),$teamModel->getGroup());
            $tmp = "<h3>Hold oprettet</h3>";
            $tmp .= "<p>Følgende hold er oprettet:</p><table class=\"table table-striped\">";
            $tmp .= "<tr><th>Hold Nummer: </th><td>".$teamid."</td></tr>";
            $tmp .= "<tr><th>Holdnavn: </th><td>".$teamModel->getName()." (gruppe: ".$teamModel->getGroup().")</td></tr>";
            $tmp .= "<tr><th>Holdleder: </th><td>".$teamModel->getLeader()."</td></tr>";
            $tmp .= "<tr><th>Mobil: </th><td>".$teamModel->getMobile()."</td></tr>";
            $tmp .= "<tr><th>HoldEmail: </th><td>".$teamModel->getEmail()."</td></tr>";
            $tmp .= "<tr><th>Kreds / Gruppe: </th><td>".$teamModel->getKreds()."</td></tr></table>";
            mail($teamModel->getEmail(),"Tivoli - ".$teamModel->getName(),$tmp);
            $tmp .= "<p>Du modtager snarest en mail med oplysningerne om holdet, sendt til: <b>". $teamModel->getEmail()."</b></p>";
            $this->teamstatus['status'] = true;
            $this->teamstatus['message'] = $tmp;
            if($teamid == 0) {
                $this->teamstatus['status'] = false;
                $this->teamstatus['message'] = "Der er opstået en fejl, indtast venligst oplysningerne igen. Ændrer eventuelt holdets navn.";
            }
        }

        return $this->teamstatus;
    }
}