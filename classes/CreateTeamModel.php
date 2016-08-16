<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 15/08/16
 * Time: 10:31
 */
class CreateTeamModel extends BaseInit
{
    private $name;
    private $mobile;
    private $leader;
    private $email;
    private $kreds;
    private $group;

    /**
     * CreateTeamModel constructor.
     * @param $postdata
     */
    public function __construct($postdata)
    {
        parent::__construct();
        $this->name = (isset($postdata['name']) ? $postdata['name'] : "");
        $this->mobile = (isset($postdata['mobile']) ? $postdata['mobile'] : "");
        $this->leader = (isset($postdata['leader']) ? $postdata['leader'] : "");
        $this->email = (isset($postdata['email']) ? $postdata['email'] : "");
        $this->kreds = (isset($postdata['kreds']) ? $postdata['kreds'] : "");
        $this->group = (isset($postdata['group']) ? $postdata['group'] : "");
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return mixed
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getKreds()
    {
        return $this->kreds;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }
}