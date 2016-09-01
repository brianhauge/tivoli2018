<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 01/09/16
 * Time: 11:06
 */

class CreateNightCrewModel extends BaseInit
{
    private $name;
    private $mobile;
    private $kreds;

    /**
     * CreateNightCrewModel constructor.
     * @param $postdata
     */
    public function __construct($postdata)
    {
        parent::__construct();
        $this->name = (isset($postdata['name']) ? $postdata['name'] : "");
        $this->mobile = (isset($postdata['mobile']) ? $postdata['mobile'] : "");
        $this->kreds = (isset($postdata['kreds']) ? $postdata['kreds'] : "");
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
}