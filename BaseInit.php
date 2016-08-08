<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 24/04/16
 * Time: 19:03
 */

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
    var $con;
    function __construct()
    {
        $this->premessage = str_pad(basename($_SERVER['PHP_SELF']),20)." | ";
        $this->logger = new Logger(__DIR__.'/logs');

        $this->con = mysqli_connect('mysql6.unoeuro.com', 'haugemedia_net', '11PVT8se','haugemedia_net_db') or die("Error " . mysqli_error($this->con));
        // Check connection
        if (mysqli_connect_errno())
        {
            $this->logger("Failed to connect to MySQL: " . mysqli_connect_error());
            die("DB issue");
        }
    }

    function __destruct()
    {
        mysqli_close($this->con);
    }
}