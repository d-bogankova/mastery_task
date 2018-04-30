<?php
function isUsernameValid ($username){
    if (preg_match('/^\p{L}[\p{L}\p{N}\-\s]+$/u', $username)){
        return true;
    }
    return false;
}

function isNaturalNum ($number){
    if (intval($number) < 1){
        return false;
    }
    return true;
}

function getNewFile ($username, $taskNumber, $months) {
    $username = preg_replace('#[^\p{L}\p{N}\s-]+#u','',$username);
    $taskNumber = intval($taskNumber);
    $months = intval($months);

    $execDate = date('d.m.Y', time());
    $endDate = date_create();
    date_modify($endDate, $months . "month");
    $endDate = date_format($endDate, "d.m.Y");
    $newFile = str_replace(['%USERNAME%', '%NUMBER%', '%EXECDATE%', '%MONTHNUM%', '%ENDDATE%'], [$username, $taskNumber, $execDate, $months, $endDate], file_get_contents('template.tpl'));
    return $newFile;

}
//Error output CLI
if (isset($argv[0])) {
    if (!isset($argv[1])) {
        die ("ERROR: Username not specified");
    } elseif (isUsernameValid($argv[1])==false) {
        die('ERROR: Username is not string ');
    }
    if (!isset($argv[2])) {
        die ("ERROR: Task number not specified");
    } elseif (isNaturalNum($argv[2])==false) {
        die('ERROR: Task number should be indicated as a numerical value ');
    }
    if (!isset($argv[3])) {
        die ("ERROR: Month number not specified");
    } elseif (isNaturalNum($argv[3])==false) {
        die('ERROR: Month number should be indicated as a numerical value ');
    }
    echo  getNewFile($argv[1],$argv[2],$argv[3]);
} else {
    if (!empty($_POST) and $_SERVER["REQUEST_METHOD"] == "POST"){
        $newFile = getNewFile($_POST['username'], $_POST['taskNumber'], $_POST['months']);
    }
}

/*Error output browser*/
if (!empty($_POST['username']) ) {
    $username = trim($_POST['username']);
    if (!isUsernameValid ($username)){
        $errors[] = 'Username is not valid!';
    }
}

if (!empty($_POST['taskNumber']) ) {
    $taskNumber = trim($_POST['taskNumber']);
    if (!isNaturalNum ($taskNumber)){
        $errors[] = 'Task Number is not valid!';
    }
}

if (!empty($_POST['months']) ) {
    $months = trim($_POST['months']);
    if (!isNaturalNum ($months)){
        $errors[] = 'Invalid number of months!';
    }
}

if (empty($argv)) {
    include_once 'index.phtml';
}



