<?php

/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 27/09/17
 * Time: 14:17
 */

session_start();
if(!isset($_SESSION['loggedin'])) {
    die("no access");
}
else if($_SESSION['loggedin'] != 1) die("no access");;

setlocale(LC_ALL, "da_DK");
require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

if(isset($_GET['start']) && isset($_GET['end'])) {
    $db = new DbModel();

    $dp = new DatePeriod(
        new DateTime($_GET['start']),
        DateInterval::createFromDateString('+1 minute'),
        new DateTime($_GET['end'])
    );

    $dates = [];
    $points = [];
    $graphPoints = [];


    if($_GET['start'] == '' || $_GET['end'] == '') {
        die("Empty parameters. Aborting");
    }

    $graphdata = $db->queryToArray('select DATE_FORMAT(tstamp,"%Y-%m-%d %H:%i") as x, count(*) as y from tivoli2018_trace where tstamp between "'.$_GET['start'].'" and "'.$_GET['end'].'" group by day(tstamp), hour(tstamp), minute(tstamp) ORDER BY x');
    foreach ($graphdata as $graph) {
        $points[$graph['x']] = $graph['y'];
    }
    foreach ($dp as $period) {
        $tmpPeriod = $period->format('Y-m-d H:i');
        $tmpStamp = strtotime($tmpPeriod) * 1000;
        if(array_key_exists($tmpPeriod,$points)) {
            $graphPoints[] = "[".$tmpStamp.",".$points[$tmpPeriod]."]";
        }
        else {
            $graphPoints[] = "[".$tmpStamp.",0]";
        }
    }
   print("[".join($graphPoints,',')."]");
}

else {
    die("Missing parameters: 'start' and 'end'");
}