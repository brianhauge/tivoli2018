<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 01/09/16
 * Time: 11:06
 */

class CreateCrewModel extends BaseInit
{
    private $name;
    private $mobile;
    private $kreds;
    private $comment;

    /**
     * CreateCrewModel constructor.
     * @param $postdata
     */
    public function __construct($postdata)
    {
        parent::__construct();
        $this->name = (isset($postdata['name']) ? $postdata['name'] : "");
        $this->mobile = (isset($postdata['mobile']) ? $postdata['mobile'] : "");
        $this->kreds = (isset($postdata['kreds']) ? $postdata['kreds'] : "");
        $this->comment = (isset($postdata['comment']) ? $postdata['comment'] : "");
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
    public function getKreds()
    {
        return $this->kreds;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

}