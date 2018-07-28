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
        $crewmodel = new CreateCrewModel($data);
        $crewcontroller = new CreateCrewController();
        $crew = $crewcontroller->insertCrew($crewmodel);
        $crew['sikkerhedskode'] = true;
        print(json_encode($crew));
    }
    else {
        $tmp['message'] = "Forkert sikkerhedskode";
        $crew['sikkerhedskode'] = false;
        $tmp['status'] = false;
        print(json_encode($tmp));
    }
}