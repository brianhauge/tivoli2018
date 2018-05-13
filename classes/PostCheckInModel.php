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
    private $msisdn;
    private $smsid;

    /**
     * @return mixed
     */
    public function getSmsid()
    {
        return $this->smsid;
    }

    /**
     * @param mixed $smsid
     */
    public function setSmsid($smsid)
    {
        $this->smsid = $smsid;
    }

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
        preg_match("/po?s?t?".POST_REGEX."/",$smscontent,$tmpmatch);
        preg_match("/".POST_REGEX."/",$tmpmatch[0],$this->post);
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
     * @param $smscontent
     * @param $msisdn
     * @param int $smsid
     */
    public function setSmscontent($smscontent, $msisdn, $smsid = 0)
    {
        $this->smscontent = strtolower(preg_replace('/\s+/', '', $smscontent));
        $this->setPost($this->smscontent);
        $this->setMsisdn($msisdn);
        $this->setSmsid($smsid);
    }
}