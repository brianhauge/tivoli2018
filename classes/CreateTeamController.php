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

            if($teamid == 0) {
                $this->teamstatus['status'] = false;
                $this->teamstatus['message'] = "Der er opstået en fejl, indtast venligst oplysningerne igen. Ændrer eventuelt holdets navn.";
            } else {

                $tmp = "<h3>Hold oprettet</h3>";
                $tmp .= "<div class=\"row\"><div class=\"col-md-4\">I har fået hold nummer:</div><div class=\"col-md-8\" style='font-size: 900%'>".$teamid."</div></div><div class=\"alert alert-success\" role=\"alert\"><b>Skriv nummeret ned. Det skal bruges ved pointgivning på posterne.</b><br />Ved ankomst kan i komme forbi løbsteltet og får udleveret et visitkort med jeres hold navn og nummer.<br /></div>";
                $tmp .= "<table class=\"table table-striped\">";
                $tmp .= "<tr><th align='left'>Holdnavn: </th><td>".$teamModel->getName()." (gruppe: ".$teamModel->getGroup().")</td></tr>";
                $tmp .= "<tr><th align='left'>Holdleder: </th><td>".$teamModel->getLeader()."</td></tr>";
                $tmp .= "<tr><th align='left'>Mobil: </th><td>".$teamModel->getMobile()."</td></tr>";
                $tmp .= "<tr><th align='left'>HoldEmail: </th><td>".$teamModel->getEmail()."</td></tr>";
                $tmp .= "<tr><th align='left'>Kreds / Gruppe: </th><td>".$teamModel->getKreds()."</td></tr></table>";

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Additional headers
                $headers .= 'Reply-to: FDF og Spejderne <brian.hauge@gmail.com>' . "\r\n";

                mail($teamModel->getEmail(),"Hold ".$teamid." oprettet - ".$teamModel->getName(),$tmp, $headers);
                $tmp .= "<p>Du modtager snarest en mail med oplysningerne om holdet, sendt til: <b>". $teamModel->getEmail()."</b></p>";
                $tmp .= "<br /><a class=\"btn btn-primary\" href=\"oprethold.php\" role=\"button\">Opret endnu et hold</a>";
                $this->teamstatus['status'] = true;
                $this->teamstatus['message'] = $tmp;
            }
        }

        return $this->teamstatus;
    }
}