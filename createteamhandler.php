<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 16/08/16
 * Time: 11:38
 */

setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$data = $_POST;
if(!empty($data)) {
    $teammodel = new CreateTeamModel($data);
    $teamcontroller = new CreateTeamController();
    $teamcreated = $teamcontroller->insertTeam($teammodel);
}

if(!empty($data)) {
    print(json_encode($teamcreated));
}