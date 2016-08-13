<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 16:05
 */

class IncomingSmsScoreModel extends BaseInit
{

    private $point;
    private $post;
    private $team;
    private $sender;
    private $smscontent;
    private $db;

    /**
     * SmsContent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = new DbModel();
    }

    /**
     * @return mixed
     */
    public function getPoint()
    {
        return $this->point[0];
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post[0];

    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team[0];
    }

    /**
     * @param mixed $smscontent
     */
    private function setPoint($smscontent)
    {
        preg_match("/po?i?n?t?(\\d{1,2}(?!\\d)|100)/",$smscontent,$tmpmatch);
        preg_match("/(\\d{1,2}(?!\\d)|100)/",$tmpmatch[0],$this->point);
    }

    /**
     * @param mixed $sender
     */
    private function setPost($sender)
    {
        $this->post = $this->db->getCheckedinPost($sender);
    }

    /**
     * @param mixed $smscontent
     */
    private function setTeam($smscontent)
    {
        preg_match("/ho?l?d?(\\d{1,2}(?!\\d)|100)/",$smscontent,$tmpmatch);
        preg_match("/(\\d{1,2}(?!\\d)|100)/",$tmpmatch[0],$this->team);
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

    /**
     * @param mixed $smscontent
     */
    public function setSmscontent($smscontent, $sender)
    {
        $this->smscontent = strtolower(preg_replace('/\s+/', '', $smscontent));
        $this->setPoint($this->smscontent);
        $this->setPost($sender);
        $this->setTeam($this->smscontent);
        $this->setSender($sender);
        $this->logger->info("SMS Content: ".$this->getSmscontent() . " Point: " . $this->getPoint() . " Post: " . $this->getPost() . " Hold: " . $this->getTeam());
    }


}