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
$dbModel = new DbModel();

$data = $_POST;
$tmp['message'] = "Ingen post data modtaget";
$tmp['status'] = false;
if(!empty($data)) {
    if(preg_match('/^\d{8}$/',$data['msisdn'])) {
        $msisdn = "45" . $data['msisdn'];

        if ($data['cmd'] == "sendcode") {
            $smsSender->sendSms($msisdn, "Sikkerhedskode: " . $_SESSION['smscode']);
            $tmp['message'] = "Kode sendt til " . $data['msisdn'];
            $tmp['status'] = true;
        }

        else if ($data['cmd'] == "tjekind" and $data['smscode'] == $_SESSION['smscode']) {
            $dbModel->insertCheckin($data['post'], $msisdn);
            $tmp['message'] = "Kode accepteret. Du er nu tjekket in på post " . $data['post'];
            $tmp['status'] = true;
        }

        else if ($data['cmd'] == "givpoint" and $data['smscode'] == $_SESSION['smscode']) {
            $checkedInPost = $dbModel->getCheckedinPost($msisdn);
            if (preg_match(POINT_REGEX,$data['point']) && preg_match(TEAM_REGEX,$data['team'])) {
                $tmp['message'] = $data['point'] . " givet til hold " . $data['team'] . " På post ".$checkedInPost;
                $tmp['status'] = true;
            } else {
                $tmp['message'] = $data['point'] . " IKKE givet til hold " . $data['team'] . " På post ".$checkedInPost;
                $tmp['status'] = false;
            }
        }

        else {
            $tmp['message'] = "Forkert kode";
            $tmp['status'] = false;

        }

    }

    else {
        $tmp['message'] = "Mobilnummer ikke korrekt: ".$data['msisdn'];
        $tmp['status'] = false;
    }
}
print(json_encode($tmp));