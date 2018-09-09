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
                $message = "Sikkerhedskode: " . $_SESSION['smscode'];
                $smsSender->sendSms($_SESSION['msisdn'], $message);
                $dbModel->insertTrace($_SESSION['msisdn'],"",$message);
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
            $team = strtolower(preg_replace('/\s+/', '', $data['team']));
            preg_match("/".GROUP_REGEX.TEAM_REGEX."/",$data['team'],$tmpmatch);
            preg_match("/".TEAM_REGEX."/",$tmpmatch[0],$teamid);
            $dbModel->insertScore($teamid[0],$data['point'],$checkedInPost,$_SESSION['msisdn']);
			$teampoints = $dbModel->getTeamPoints($teamid[0]);
			$teamdetails = $dbModel->getTeamDetails($teamid[0]);
            $tmp['message'] = "<p>".$data['point'] . " point givet til:</p><p><b>" . $teamdetails['cid'] . " - " . $teamdetails['name'] . "</b></p><p>Holdet har nu ".$teampoints." point.";
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