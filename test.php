<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 26-02-2018
 * Time: 17:07
 */

session_start();
$cache_expire = session_cache_expire();
print($cache_expire);
include "config.php";
include "vendor/autoload.php";
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});


