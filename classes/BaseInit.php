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
    function __construct()
    {
        $this->logger = new Logger(LOGPATH,Psr\Log\LogLevel::DEBUG, array(
            'dateFormat' => 'Y-m-d G:i:s',
            'logFormat' =>  '[{date}] [{level}]{level-padding} {message}'
        ));
    }

    /**
     * Returns the calling function through a backtrace
     */
    public function get_calling_function() {
        // a function x has called a function y which called this
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