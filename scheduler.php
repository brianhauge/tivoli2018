<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 05-05-2018
 * Time: 13:49
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$schedule = '';
$val = getopt(null, ["schedule:"]);
if (isset($val['schedule'])) {
	$schedule = $val['schedule'];
} else if (isset($_GET['schedule'])) {
	$schedule = $_GET['schedule'];
}
if($schedule == '') die("Schedule not set");

setlocale(LC_ALL, "da_DK");
require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;
require 'config.php';

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$logger = new Logger(LOGPATH,Psr\Log\LogLevel::DEBUG, array(
            'dateFormat' => 'Y-m-d G:i:s',
            'logFormat' =>  '[{date}] [{level}]{level-padding} {message}',
			'prefix' => 'scheduler_'
        ));


// Handle incoming SMS queue
if($schedule == 'handleIncomingQueue') {
    $smsDB = new SmsgwDbModel();
    $smss = $smsDB->getSMS();
	$logger->info("Scheduler::handleIncomingQueue: Number of SMS's to handle: ".count($smss));

    foreach ($smss as $sms) {
		$logger->info("Scheduler::handleIncomingQueue: Handling ".$sms['id']);
        if(preg_match("/[Cc]heck|[Tt]jek/",$sms['text'])) {
            $checkinPostModel = new PostCheckInModel();
            $checkinController = new PostCheckInController();
            $checkinPostModel->setSmscontent($sms['text'],$sms['msisdn'],$sms['id']);
            $checkinController->handleCheckin($checkinPostModel);
        }
        else {
            $SmsScoreModel = new SmsScoreModel();
            $scoreController = new SmsScoreController();
            $SmsScoreModel->setSmscontent($sms['text'],$sms['msisdn'],$sms['id']);
            $scoreController->handleReceivedPoints($SmsScoreModel);
        }
    }
	$logger->info("Scheduler::handleIncomingQueue: Done");
}