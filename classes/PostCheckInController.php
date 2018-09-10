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
        if (!$checkInModel->getPost()) {
            $message = "Postindtjekning: 'post' eller 'p' ikke fundet i beskeden eller dens værdi er ugyldig (Axx).".SMS_HELPTEXT;
            $this->smsSender->sendSms($checkInModel->getMsisdn(),$message,$checkInModel->getSmsid());
            $this->logger->warning(__METHOD__.": ".$checkInModel->getMsisdn().",".$checkInModel->getSmscontent().", Wrong value in message");
        }
        else {
            // Insert Check-in to database
            $this->dbModel->insertCheckin($checkInModel->getPost(),$checkInModel->getMsisdn());
            // Send status to $sender
            $this->logger->info(__METHOD__.": ".$checkInModel->getMsisdn()." has checked in on post ".$checkInModel->getPost());
            $message = "Du er nu tjekket ind på post ".$checkInModel->getPost().". For at give point, send ex: a4 p20 (20 point til hold A4). Husk at checke ind igen, hvis du flytter post!";
            $this->smsSender->sendSms($checkInModel->getMsisdn(),$message);
        }
        $this->dbModel->insertTrace($checkInModel->getMsisdn(),$checkInModel->getSmscontent(),$message);
    }
	
	public function listPostsForWeb() {
		$postList = "";
		$allPosts = $this->dbModel->getAllPostDetails();
		foreach($allPosts as $post) {
			$postList .= '<option value="' . $post['postnr'] . '">' . $post['postnr'] . ' - ' . $post['name'] . '</option>';
		}
		return $postList;
	}
}
