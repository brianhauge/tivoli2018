<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 16/08/16
 * Time: 11:38
 */

session_start();
setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$data = $_POST;
if(!empty($data)) {
    if($data['captcha'] == $_SESSION['captcha']['code']) {
        $teammodel = new CreateTeamModel($data);
        $teamcontroller = new CreateTeamController();
        $teamcreated = $teamcontroller->insertTeam($teammodel);
        print(json_encode($teamcreated));
    }
    else {
        $tmp['message'] = "Forkert code";
        $tmp['status'] = false;
        print(json_encode($tmp));
    }
}