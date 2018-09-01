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

$smsSender = new SendSmsModel();
$smsSender->setFrom("Julemanden");
$dbModel = new DbModel();

$data = $_POST;
$tmp['message'] = "Ingen post data modtaget";
$tmp['status'] = false;
if(!empty($data)) {
    if ($data['cmd'] == "sendcode") {
        if(preg_match('/^\d{8}$/',$data['msisdn'])) {
            $_SESSION['msisdn'] = "45" . $data['msisdn'];
            if ($data['cmd'] == "sendcode") {
                $smsSender->sendSms($_SESSION['msisdn'], "Sikkerhedskode: " . $_SESSION['smscode']);
                $tmp['message'] = "Kode sendt til " . $data['msisdn'];
                $tmp['status'] = true;
            }
        }
        else {
            $tmp['message'] = "Mobilnummer ikke korrekt: ".$data['msisdn'];
            $tmp['status'] = false;
        }
    }
    else if ($data['cmd'] == "tjekind" and $data['smscode'] == $_SESSION['smscode']) {
        $dbModel->insertCheckin($data['postid'], $_SESSION['msisdn']);
        $tmp['message'] = "Kode accepteret. Du er nu tjekket in på post " . $data['postid'];
        $tmp['status'] = true;
        $_SESSION['checkedin'] = true;
    }
    else if ($data['cmd'] == "givpoint" && $_SESSION['checkedin']) {
        $checkedInPost = $dbModel->getCheckedinPost($_SESSION['msisdn']);
        if ($data['point'] === "" or $data['team'] === "") {
            $tmp['message'] = "Point eller Hold ikke udfyldt";
            $tmp['status'] = false;
        }
        else if (preg_match("/".POINT_REGEX."/",$data['point']) && preg_match("/".TEAM_REGEX."/",$data['team'])) {
            $dbModel->insertScore($data['team'],$data['point'],$checkedInPost,$_SESSION['msisdn']);
            $tmp['message'] = $data['point'] . " givet til hold " . $data['team'] . " På post ".$checkedInPost;
            $tmp['status'] = true;
        } else {
            $tmp['message'] = $data['point'] . " IKKE givet til hold " . $data['team'] . " på post ".$checkedInPost;
            $tmp['status'] = false;
        }
    }
    else if ($_SESSION['checkedin'] && $data['cmd'] == "mypost") {
        $tmp['message'] = $dbModel->getCheckedinPost($_SESSION['msisdn']);
        $tmp['status'] = true;
    }
    else {
        $tmp['message'] = "Du er ikke tjekket ind på posten - ring 25 21 20 02 hvis problemet fortsætter";
        $tmp['status'] = false;
    }
}



else {
    $tmp['message'] = "Forkert kode";
    $tmp['status'] = false;

}
print(json_encode($tmp));