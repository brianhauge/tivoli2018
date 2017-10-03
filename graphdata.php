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

    if($_GET['start'] == '' || $_GET['end'] == '') {
        die("Empty parameters. Aborting");
    }




    $db = new DbModel();

    $graphdata = $db->queryToArray('select DATE_FORMAT(tstamp,"%Y-%m-%d %H:%i:00") as x, count(*) as y from tivoli2016_trace where tstamp between "'.$_GET['start'].' 00:00:00" and "'.$_GET['end'].' 23:59:59" group by day(tstamp), hour(tstamp), minute(tstamp) ORDER BY x');
    foreach ($graphdata as $graph) {
        $date_stamp = strtotime($graph['x']) * 1000;
        $data[] = "[".$date_stamp.",".$graph['y']."]";
    }

    print("[".join($data,',')."]");
}

else {
    die("Missing parameters: 'start' and 'end'");
}