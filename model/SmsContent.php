<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 16:05
 */

class SmsContent extends BaseInit
{

    /**
     * SmsContent constructor.
     */
    private $point;
    private $post;
    private $team;
    private $smscontent;

    public function __construct()
    {
        parent::__construct();
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
        preg_match("/poin?t?(\\d{1,2}(?!\\d)|100)/",$smscontent,$tmpmatch);
        preg_match("/(\\d{1,2}(?!\\d)|100)/",$tmpmatch[0],$this->point);
    }

    /**
     * @param mixed $smscontent
     */
    private function setPost($smscontent)
    {
        preg_match("/post?(\\d{1,2}(?!\\d)|100)/",$smscontent,$tmpmatch);
        preg_match("/(\\d{1,2}(?!\\d)|100)/",$tmpmatch[0],$this->post);
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
    public function getSmscontent()
    {
        return $this->smscontent;
    }

    /**
     * @param mixed $smscontent
     */
    public function setSmscontent($smscontent)
    {
        $this->smscontent = preg_replace('/\s+/', '', $smscontent);
        $this->setPoint($this->smscontent);
        $this->setPost($this->smscontent);
        $this->setTeam($this->smscontent);
        $this->logger->info("SMS Content: ".$this->getSmscontent() . " Point: " . $this->getPoint() . " Post: " . $this->getPost() . " Hold: " . $this->getTeam());
    }


}