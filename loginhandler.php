<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 27/09/17
 * Time: 10:26
 */

session_start();
setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$data = $_POST;
if(!empty($data)) {
    $db = new DbModel();

    $userExist = $db->userExists($data['username'],$data['password']);
    if($userExist) {
        $tmp['message'] = "Thumbs Up";
        $tmp['status'] = true;
        $_SESSION['loggedin'] = true;
    }
    else {
        $tmp['message'] = "Nope";
        $tmp['status'] = false;
    }

    print(json_encode($tmp));


}