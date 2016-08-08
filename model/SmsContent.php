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
        return $this->point;
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
        return $this->team;
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
        $this->smscontent = $smscontent;
        preg_match("poin?t?(\d{1,2}(?!\d)|100)(\D|$)",$smscontent,$this->point);
        preg_match("post?(\d{1,2}(?!\d)|100)(\D|$)",$smscontent,$this->post);
        preg_match("te?a?m?(\d{1,2}(?!\d)|100)(\D|$)",$smscontent,$this->team);
        $this->logger->info("SMS Content: ").$smscontent;
    }


}