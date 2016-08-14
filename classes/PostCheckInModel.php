<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 13/08/16
 * Time: 19:48
 */
class PostCheckInModel extends BaseInit
{
    private $post;
    private $smscontent;
    private $sender;

    /**
     * SmsContent constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post[0];
    }

    /**
     * @param mixed $smscontent
     */
    private function setPost($smscontent)
    {
        preg_match("/po?s?t?(\\d{1,2}(?!\\d)|100)/",$smscontent,$tmpmatch);
        preg_match("/(\\d{1,2}(?!\\d)|100)/",$tmpmatch[0],$this->post);
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    private function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getSmscontent()
    {
        return $this->smscontent;
    }

    public function setSmscontent($smscontent, $sender)
    {
        $this->smscontent = strtolower(preg_replace('/\s+/', '', $smscontent));
        $this->setPost($this->smscontent);
        $this->setSender($sender);
        $this->logger->info("SMS Content: ".$this->getSmscontent() . " Checking in on post: " . $this->getPost() . " Mobile: " . $this->getSender()
        );
    }
}