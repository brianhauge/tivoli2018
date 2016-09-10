<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 10/09/16
 * Time: 12:17
 */

require 'vendor/autoload.php';
use Katzgrau\KLogger\Logger;

setlocale(LC_ALL, "da_DK");
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});


$send = new SendSmsModel();

$directory = '/Users/bhansen/Desktop/sms/incoming/';
$scanned_directory = array_diff(scandir($directory), array('..', '.'));

foreach ($scanned_directory as $file11) {
    $handle = fopen($directory.$file11, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            
            $send->tjekInSMS($line,"12345678");
        }

        fclose($handle);
    } else {
        print("issue with ".$directory);
    }
}

