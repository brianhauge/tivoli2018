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

    /**
     * Returns the calling function through a backtrace
     */
    public function get_calling_function() {
        // a funciton x has called a function y which called this
        // see stackoverflow.com/questions/190421
        $caller = debug_backtrace();
        $caller = $caller[2];
        $r = $caller['function'] . '()';
        if (isset($caller['class'])) {
            $r .= ' in ' . $caller['class'];
        }
        if (isset($caller['object'])) {
            $r .= ' (' . get_class($caller['object']) . ')';
        }
        return $r;
    }
}