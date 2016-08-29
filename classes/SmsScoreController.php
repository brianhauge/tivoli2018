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
            $message = "Du er ikke tjekket ind på en post. Send 'checkin post 9' for at tjekke ind.".SMS_HELPTEXT;

        }
        elseif($smsModel->getTeam() < 1) {
            $message = "'a', 'b', 'c' eller 'n' (for hold-id) ikke fundet i beskeden eller dens værdi er ugyldig.".SMS_HELPTEXT;
        }
        elseif ($smsModel->getPoint() < 1) {
            $message = "'point' ikke fundet i beskeden eller dens værdi er ugyldig.".SMS_HELPTEXT;
        }
        else {
            // Insert Score
            $this->dbModel->insertScore($smsModel->getTeam(),$smsModel->getPoint(),$smsModel->getPost(),$smsModel->getMsisdn());
            // Send status to $sender
            $message = $smsModel->getPoint()." point til hold ".$smsModel->getTeam()." på post ".$smsModel->getPost()." givet. Holdet har nu ".$this->dbModel->getTeamPoints($smsModel->getTeam())." point.";
        }

        $this->smsSender->sendSms($smsModel->getMsisdn(),$message);
        $this->dbModel->insertTrace($smsModel->getMsisdn(),$smsModel->getSmscontent(),$message);
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
        $this->logger->info(__CLASS__." > ".__FUNCTION__.": Creating score table");
        return $scoreTable;
    }
}