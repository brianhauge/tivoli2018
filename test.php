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


$dbModel = new DbModel();

var_dump ($dbModel->getTeamDetails(22));



$allPosts = $dbModel->getAllPostDetails("d");
$array = array();

foreach($allPosts as $key => $post) {
	$array[] = $key . " - " . $post['name'];
}

$json = json_encode($array);
print($json);
