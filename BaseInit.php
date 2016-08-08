<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 24/04/16
 * Time: 19:03
 */

require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;

abstract class BaseInit
{
    var $logger;
    var $premessage;
    function __construct()
    {
        $this->premessage = str_pad(basename($_SERVER['PHP_SELF']),20)." | ";
        $this->logger = new Logger(__DIR__.'/logs');
    }
}