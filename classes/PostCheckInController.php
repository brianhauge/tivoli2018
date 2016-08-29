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
            $message = "Postindtjekning: 'post' ikke fundet i beskeden eller dens værdi er ugyldig. Ring 25 21 20 02 for hjælp.";
            $this->smsSender->sendSms($checkInModel->getMsisdn(),$message);
            $this->dbModel->insertTrace($checkInModel->getMsisdn(),$checkInModel->getSmscontent(),$message);
        }
        else {
            // Insert Check-in to database
            $this->dbModel->insertCheckin($checkInModel->getPost(),$checkInModel->getMsisdn());
            // Send status to $sender
            $this->logger->info(__CLASS__." > ".__FUNCTION__.": ".$checkInModel->getMsisdn()." has checked in on post ".$checkInModel->getPost());
            $this->smsSender->sendSms($checkInModel->getMsisdn(),"Du er nu checked in på post ".$checkInModel->getPost().". For at give point, send ex: hold 4 point 20. Husk at checke ind igen, hvis du flytter post!");
        }
    }
}