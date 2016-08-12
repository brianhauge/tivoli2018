<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/08/16
 * Time: 17:32
 */
class SendSmsModel extends BaseInit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendSms($receiver, $message) {
        $this->logger->info("Sending SMS: '".$message."' To: ".$receiver);
    }
}