<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 16:05
 */

class SmsScoreModel extends BaseInit
{

    private $point;
    private $post;
    private $team;
    private $msisdn;
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
        return $this->post;

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
        preg_match("/po?i?n?t?(\\d{1,2}|100)(?!\\d)/",$smscontent,$tmpmatch);
        preg_match("/".POINT_REGEX."/",$tmpmatch[0],$this->point);
    }

    /**
     * @param mixed $msisdn
     */
    private function setPost($msisdn)
    {
        $this->post = $this->db->getCheckedinPost($msisdn);
    }

    /**
     * @param mixed $smscontent
     */
    private function setTeam($smscontent)
    {
        preg_match("/".GROUP_REGEX.TEAM_REGEX."/",$smscontent,$tmpmatch);
        preg_match("/".TEAM_REGEX."/",$tmpmatch[0],$this->team);
    }

    /**
     * @return mixed
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * @param mixed $msisdn
     */
    private function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;
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
     * @param mixed $sender
     */
    public function setSmscontent($smscontent, $sender)
    {
        $this->smscontent = strtolower(preg_replace('/\s+/', '', $smscontent));
        $this->setPoint($this->smscontent);
        $this->setMsisdn($sender);
        $this->setPost($sender);
        $this->setTeam($this->smscontent);
        $this->logger->info(__METHOD__.": ".$this->getSmscontent() . " Point: " . $this->getPoint() . " Post: " . $this->getPost() . " Hold: " . $this->getTeam());
    }


}