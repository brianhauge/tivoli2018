<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/08/16
 * Time: 16:47
 */
class SmsScoreController extends BaseInit
{
    var $smsSender;
    var $dbModel;
    public function __construct()
    {
        parent::__construct();
        $this->smsSender = new SendSmsModel();
        $this->dbModel = new DbModel();
    }

    public function handleReceivedPoints(SmsScoreModel $smsModel) {
        if ($smsModel->getPost() < 1) {
            $this->smsSender->sendSms($smsModel->getSender(),"Du er ikke tjekket ind på en post. Send 'checkin post 9' for at tjekke ind. Ring 25 21 20 02 for hjælp.");
        }
        elseif($smsModel->getTeam() < 1) {
            $this->smsSender->sendSms($smsModel->getSender(),"'hold' ikke fundet i beskeden eller dens værdi er ugyldig. Ring 25 21 20 02 for hjælp.");
        }
        elseif ($smsModel->getPoint() < 1) {
            $this->smsSender->sendSms($smsModel->getSender(),"'point' ikke fundet i beskeden eller dens værdi er ugyldig. Ring 25 21 20 02 for hjælp.");
        }
        else {
            // Insert Score
            $this->dbModel->insertScore($smsModel->getTeam(),$smsModel->getPoint(),$smsModel->getPost(),$smsModel->getSender());
            // Send status to $sender
            $this->smsSender->sendSms($smsModel->getSender(),$smsModel->getPoint()." point til hold ".$smsModel->getTeam()." på post ".$smsModel->getPost()." givet. Holdet har nu ".$this->dbModel->getTeamPoints($smsModel->getTeam())." point.");
        }
    }
    
    public function getScoreTableByGroup($group) {
        $scoreTable = "<table class=\"table table-striped\"><thead><tr><th>Placering</th><th>Hold</th><th>Point</th></tr></thead><tbody>";
        $counter = 1;
        foreach ($this->dbModel->getScore($group) as $score) {
            $scoreTable .= "<tr>";
            $scoreTable .= "<th width='20%'>$counter</th>";
            $scoreTable .= "<td width='70%'>" . $score['team'] . "</td>";
            $scoreTable .= "<td><span class=\"badge\">" . $score['point'] . "</span></td>";
            $scoreTable .= "</tr>";
            $counter++;
        }
        $scoreTable .= "</tbody>";
        $scoreTable .= "</table>";
        return $scoreTable;
    }
}