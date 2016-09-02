<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 13/08/16
 * Time: 19:59
 */
class PostCheckInController extends BaseInit
{
    var $smsSender;
    var $dbModel;
    public function __construct()
    {
        parent::__construct();
        $this->smsSender = new SendSmsModel();
        $this->dbModel = new DbModel();
    }

    public function handleCheckin(PostCheckInModel $checkInModel) {
        if ($checkInModel->getPost() < 1) {
            $message = "Postindtjekning: 'post' eller 'p' ikke fundet i beskeden eller dens værdi er ugyldig.".SMS_HELPTEXT;
            $this->smsSender->sendSms($checkInModel->getMsisdn(),$message);
            $this->logger->warning(__METHOD__.": ".$checkInModel->getMsisdn().",".$checkInModel->getSmscontent().", Wrong value in message");
        }
        else {
            // Insert Check-in to database
            $this->dbModel->insertCheckin($checkInModel->getPost(),$checkInModel->getMsisdn());
            // Send status to $sender
            $this->logger->info(__METHOD__.": ".$checkInModel->getMsisdn()." has checked in on post ".$checkInModel->getPost());
            $this->smsSender->sendSms($checkInModel->getMsisdn(),"Du er nu checked in på post ".$checkInModel->getPost().". For at give point, send ex: c4 p20 (20 point til hold C4). Husk at checke ind igen, hvis du flytter post!");
        }
    }
}
