<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 26-02-2018
 * Time: 17:07
 */


include "config.php";
include "vendor/autoload.php";
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$array = array(
    '0-10'   => 'a Value',
    '11-25'  => 'another Value',
    '26-50'  => 'value',
    '51-100' => 'another string'
);


$search_key = 13;
$result = '';

foreach ($array as $k => $v) {
    $range = array_map('intval', explode('-', $k));
    if ($search_key >= $range[0] && $search_key <= $range[1]) {
        $result = $v;
        break;
    }
}

print_r($result);  // "another Value"