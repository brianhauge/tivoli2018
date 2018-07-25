<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 01/09/16
 * Time: 11:09
 */

class CreateCrewController extends BaseInit
{
    var $dbModel;
    var $status = Array();
    /**
     * CreateCrewController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dbModel = new DbModel();
        $this->status = Array("status"=>false, "message"=>"");
    }

    public function insertCrew(CreateCrewModel $crewModel) {
        if ($crewModel->getName() === "") {
            $this->status['message'] = "Navn mangler";
        } elseif ($crewModel->getMobile() === "") {
            $this->status['message'] = "Mobil mangler";
        } elseif ($crewModel->getKreds() === "") {
            $this->status['message'] = "Kreds / Gruppe mangler";
        } else {
            $teamid = $this->dbModel->insertCrew($crewModel->getName(), $crewModel->getMobile(), $crewModel->getKreds());
            if($teamid != "00000") {
                $this->status['status'] = false;
                $this->status['message'] = "Der er opstået en fejl, indtast venligst oplysningerne igen.";
            } else {

                $reponsebody = "<h3>Postmandskab oprettet</h3>";
                $reponsebody .= "<table class=\"table table-striped\">";
                $reponsebody .= "<tr><th align='left'>Navn: </th><td>".$crewModel->getName()."</td></tr>";
                $reponsebody .= "<tr><th align='left'>Mobil: </th><td>".$crewModel->getMobile()."</td></tr>";
                $reponsebody .= "<tr><th align='left'>Kreds / Gruppe: </th><td>".$crewModel->getKreds()."</td></tr></table>";
                $reponsebody .= "<tr><th align='left'>Kommentar: </th><td>".$crewModel->getComment()."</td></tr></table>";
                $reponsebody .= "<br /><a class=\"btn btn-primary\" href=\"opretpostmandskab.php\" role=\"button\">Opret endnu et postmandskab</a>";
                $this->status['status'] = true;
                $this->status['message'] = $reponsebody;
            }
        }
        if($this->status['status']) {
            $this->logger->info(__METHOD__.": Created: ".$crewModel->getName()." ".$crewModel->getMobile());
        }
        else {
            $this->logger->warning(__METHOD__.": Error creating: ".$crewModel->getName()." ".$crewModel->getMobile());
        }
        return $this->status;
    }
}