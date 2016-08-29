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
    var $mail;
    /**
     * CreateTeamController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dbModel = new DbModel();
        $this->teamstatus = Array("status"=>false, "message"=>"");
        $this->mail = new \PHPMailer\PHPMailer\PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = MAIL_HOST;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = MAIL_ADDRESS;
        $this->mail->Password = MAIL_PASSWORD;
        $this->mail->setFrom(MAIL_ADDRESS, MAIL_FROMNAME);
        $this->mail->addReplyTo(MAIL_ADDRESS, MAIL_REPLYNAME);
        $this->mail->isHTML(true);
        $this->mail->CharSet = "UTF-8";
    }

    public function insertTeam(CreateTeamModel $teamModel) {
        if ($teamModel->getName() === "") {
            $this->teamstatus['message'] = "Holdnavn mangler";
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

                $mailbody = "<h3>Hold oprettet</h3>";
                $mailbody .= "<div class=\"row\"><div class=\"col-md-4\">I har fået hold nummer:</div><div class=\"col-md-8\" style='font-size: 900%'>".$teamModel->getGroup().$teamid."</div></div><div class=\"alert alert-success\" role=\"alert\"><p><b>Skriv nummeret ned og medbring på dagen. Det skal bruges ved pointgivning på posterne.</b></p></div>";
                $mailbody .= "<table class=\"table table-striped\">";
                $mailbody .= "<tr><th align='left'>Holdnavn: </th><td>".$teamModel->getName()." (gruppe: ".$teamModel->getGroup().")</td></tr>";
                $mailbody .= "<tr><th align='left'>Holdleder: </th><td>".$teamModel->getLeader()."</td></tr>";
                $mailbody .= "<tr><th align='left'>Mobil: </th><td>".$teamModel->getMobile()."</td></tr>";
                $mailbody .= "<tr><th align='left'>HoldEmail: </th><td>".$teamModel->getEmail()."</td></tr>";
                $mailbody .= "<tr><th align='left'>Kreds / Gruppe: </th><td>".$teamModel->getKreds()."</td></tr></table>";

                //Set who the message is to be sent to
                $this->mail->addAddress($teamModel->getEmail(), $teamModel->getName());

                //Set the subject line
                $this->mail->Subject = "Hold: ".$teamModel->getGroup().$teamid." - ".$teamModel->getName();
                $this->mail->Body = $mailbody;

                //send the message, check for errors
                if (!$this->mail->send()) {
                    $this->logger->error(__CLASS__." > ".__FUNCTION__.": Mailer Error: " . $this->mail->ErrorInfo);
                } else {
                    $this->logger->info(__CLASS__." > ".__FUNCTION__.": Mail sent!");
                }

                //mail($teamModel->getEmail(),"Hold ".$teamid." oprettet - ".$teamModel->getName(),$mailbody, $headers);
                $mailbody .= "<p>Du modtager snarest en mail med oplysningerne om holdet, sendt til: <b>". $teamModel->getEmail()."</b></p>";
                $mailbody .= "<br /><a class=\"btn btn-primary\" href=\"oprethold.php\" role=\"button\">Opret endnu et hold</a>";
                $this->teamstatus['status'] = true;
                $this->teamstatus['message'] = $mailbody;
            }
        }
        if($this->teamstatus['status']) {
            $this->logger->info(__CLASS__." > ".__FUNCTION__.": Status: ".$this->teamstatus['status']." - Message: Team created: ".$teamModel->getGroup().$teamid." - ".$teamModel->getName());
        }
        else {
            $this->logger->warning(__CLASS__." > ".__FUNCTION__.": Status: ".$this->teamstatus['status']." - Message: Problem creating team: ".$teamModel->getGroup().$teamid." - ".$teamModel->getName());
        }
        return $this->teamstatus;
    }
}