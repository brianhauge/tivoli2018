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
            $this->teamstatus['message'] = "Patruljenavn mangler";
        } elseif ($teamModel->getLeader() === "") {
            $this->teamstatus['message'] = "Patruljeleder mangler";
        } elseif ($teamModel->getMobile() === "") {
            $this->teamstatus['message'] = "Mobil mangler";
        } elseif ($teamModel->getEmail() === "") {
            $this->teamstatus['message'] = "Email mangler";
        } elseif ($teamModel->getKreds() === "") {
            $this->teamstatus['message'] = "Kreds / Gruppe mangler";
        } elseif ($teamModel->getGroup() === "") {
            $this->teamstatus['message'] = "Løbsgruppe mangler";
        } else {
            $teamid = $this->dbModel->insertTeam($teamModel->getName(), $teamModel->getLeader(), $teamModel->getMobile(),$teamModel->getEmail(),$teamModel->getKreds(),$teamModel->getGroup(),$teamModel->getNumberofmembers());

            if($teamid == 0) {
                $this->teamstatus['status'] = false;
                $this->teamstatus['message'] = "Der er opstået en fejl, indtast venligst oplysningerne igen. Ændrer eventuelt patruljens navn.";
            } else {

                $mailbody = "<h3>Patrulje oprettet</h3>";
                $mailbody .= "<div class=\"row\"><div class=\"col-md-4\">I har fået holdnummer:</div><div class=\"col-md-8\" style='font-size: 600%'>".$teamModel->getGroup().$teamid."</div></div><div class=\"alert alert-success\" role=\"alert\"><p><b>Skriv nummeret ned og medbring på dagen. Det skal angives ved pointgivning på posterne.</b></p></div>";
                $mailbodyTable = "<table class=\"table table-striped\">";
                $mailbodyTable .= "<tr><th align='left'>Patruljenavn: </th><td>".$teamModel->getName()." (gruppe: ".$teamModel->getGroup().")</td></tr>";
                $mailbodyTable .= "<tr><th align='left'>Patruljeleder: </th><td>".$teamModel->getLeader()."</td></tr>";
                $mailbodyTable .= "<tr><th align='left'>Mobil: </th><td>".$teamModel->getMobile()."</td></tr>";
                $mailbodyTable .= "<tr><th align='left'>HoldEmail: </th><td>".$teamModel->getEmail()."</td></tr>";
                $mailbodyTable .= "<tr><th align='left'>Kreds / Gruppe: </th><td>".$teamModel->getKreds()."</td></tr></table>";
                $mailbodyTableMandskab = "<h3>Postmandskab</h3>";
                $mailbodyTableMandskab .= "Hver kreds / gruppe skal stille med følgende postmandskab afhænging af antal tilmeldte deltagere:";
                $mailbodyTableMandskab .= "<ul><li>0-4 deltagere: Ingen postmandskab</li><li>5-10 deltagere: 1 leder til postmandskab</li><li>11-25 deltagere: 2 ledere til postmandskab</li><li>26-40 deltagere: 3 ledere til postmandskab</li><li>40+ deltagere: 4 ledere til postmandskab</li></ul>";
                $mailbodyTableMandskab .= "<br /><a class=\"btn btn-primary\" href=\"opretpostmandskab.php\" role=\"button\">Tilmeld postmandskab</a>";
                //Set who the message is to be sent to
                $this->mail->addAddress($teamModel->getEmail(), $teamModel->getName());

                //Set the subject line
                $this->mail->Subject = "Hold: ".$teamModel->getGroup().$teamid." - ".$teamModel->getName();
                $this->mail->Body = $mailbody.$mailbodyTable.$mailbodyTableMandskab;

                //send the message, check for errors
                if (!$this->mail->send()) {
                    $this->logger->error(__METHOD__.": Mailer Error: " . $this->mail->ErrorInfo);
                    $mailbody .= "<p>Der skete en fejl da vi forsøgte at sende en mail med oplysningerne til: <b>". $teamModel->getEmail()."</b> Kontakt venligst ".MAIL_ADDRESS." for at være sikker på at i er tilmeldt korrekt</p>";
                } else {
                    $this->logger->info(__METHOD__.": Mail sent!");
                    $mailbody .= "<p>Du modtager snarest en mail med oplysningerne om patruljen, sendt til: <b>". $teamModel->getEmail()."</b></p>";
                }

                
                $mailbody .= "<br /><a class=\"btn btn-primary\" href=\"oprethold.php\" role=\"button\">Opret endnu en patrulje</a>";

                $this->teamstatus['status'] = true;
                $this->teamstatus['message'] = $mailbody.$mailbodyTableMandskab;
            }
        }
        if($this->teamstatus['status']) {
            $this->logger->info(__METHOD__.": Status: ".$this->teamstatus['status']." - Message: Team created: ".$teamModel->getGroup().$teamid." - ".$teamModel->getName());
        }
        else {
            $this->logger->warning(__METHOD__.": Status: ".$this->teamstatus['status']." - Message: Problem creating team: ".$teamModel->getGroup().$teamid." - ".$teamModel->getName());
        }
        return $this->teamstatus;
    }
}