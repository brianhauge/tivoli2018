<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 24/04/16
 * Time: 19:03
 */

include_once("config.php");

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('Europe/Copenhagen');
}

require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;

abstract class BaseInit
{
    var $logger;
    var $premessage;
    function __construct()
    {
        $this->premessage = str_pad(basename($_SERVER['PHP_SELF']),20)." | ";
        $this->logger = new Logger(LOGPATH);
    }

    function __destruct()
    {
        mysqli_close($this->con);
    }
}