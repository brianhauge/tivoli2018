<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 01/09/16
 * Time: 11:09
 */

class CreateNightCrewController extends BaseInit
{
    var $dbModel;
    var $status = Array();
    /**
     * CreateNightCrewController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dbModel = new DbModel();
        $this->status = Array("status"=>false, "message"=>"");
    }

    public function insertNightCrew(CreateNightCrewModel $nightCrewModel) {
        if ($nightCrewModel->getName() === "") {
            $this->status['message'] = "Navn mangler";
        } elseif ($nightCrewModel->getMobile() === "") {
            $this->status['message'] = "Mobil mangler";
        } elseif ($nightCrewModel->getKreds() === "") {
            $this->status['message'] = "Kreds / Gruppe mangler";
        } else {
            $teamid = $this->dbModel->insertNightCrew($nightCrewModel->getName(), $nightCrewModel->getMobile(), $nightCrewModel->getKreds());
            if($teamid != "00000") {
                $this->status['status'] = false;
                $this->status['message'] = "Der er opstået en fejl, indtast venligst oplysningerne igen.";
            } else {

                $reponsebody = "<h3>Postmandskab oprettet</h3>";
                $reponsebody .= "<table class=\"table table-striped\">";
                $reponsebody .= "<tr><th align='left'>Navn: </th><td>".$nightCrewModel->getName()."</td></tr>";
                $reponsebody .= "<tr><th align='left'>Mobil: </th><td>".$nightCrewModel->getMobile()."</td></tr>";
                $reponsebody .= "<tr><th align='left'>Kreds / Gruppe: </th><td>".$nightCrewModel->getKreds()."</td></tr></table>";
                $reponsebody .= "<br /><a class=\"btn btn-primary\" href=\"natpostmandskab.php\" role=\"button\">Opret endnu et postmandskab</a>";
                $this->status['status'] = true;
                $this->status['message'] = $reponsebody;
            }
        }
        if($this->status['status']) {
            $this->logger->info(__CLASS__." > ".__FUNCTION__.": Created: ".$nightCrewModel->getName()." ".$nightCrewModel->getMobile());
        }
        else {
            $this->logger->warning(__CLASS__." > ".__FUNCTION__.": Error creating: ".$nightCrewModel->getName()." ".$nightCrewModel->getMobile());
        }
        return $this->status;
    }
}