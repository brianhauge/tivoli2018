<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/08/16
 * Time: 16:47
 */
class ScoreController extends BaseInit
{
    var $smsSender;
    var $dbModel;
    public function __construct()
    {
        parent::__construct();
        $this->smsSender = new SendSmsModel();
        $this->dbModel = new DbModel();
    }

    public function handleReceivedPoints(IncomingSmsScoreModel $smsModel) {
        if($smsModel->getTeam() < 1) {
            $this->smsSender->sendSms($smsModel->getSender(),"'hold' ikke fundet i beskeden");
        }
        elseif ($smsModel->getPost() < 1) {
            $this->smsSender->sendSms($smsModel->getSender(),"'post' ikke fundet i beskeden");
        }
        elseif ($smsModel->getPoint() < 1) {
            $this->smsSender->sendSms($smsModel->getSender(),"'point' ikke fundet i beskeden");
        }
        else {
            // Insert Score
            $this->dbModel->insertScore($smsModel->getTeam(),$smsModel->getPoint(),$smsModel->getPost(),$smsModel->getSender());
            // Send status to $sender
            $this->smsSender->sendSms($smsModel->getSender(),"Point til hold ".$smsModel->getTeam()." post ".$smsModel->getPost()." givet");
        }
    }
    
    public function getScoreByTeam($team) {
        
    }
}