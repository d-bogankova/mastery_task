<?php
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
    } elseif (is_numeric($argv[1])==true) {
        die('ERROR: Username is not string ');
    }
    if (!isset($argv[2])) {
        die ("ERROR: Task number not specified");
    } elseif (is_numeric($argv[2])==false) {
        die('ERROR: Task number should be indicated as a numerical value ');
    }
    if (!isset($argv[3])) {
        die ("ERROR: Month number not specified");
    } elseif (is_numeric($argv[3])==false) {
        die('ERROR: Month number should be indicated as a numerical value ');
    }
    echo  getNewFile($argv[1],$argv[2],$argv[3]);
} else {
    if (isset($_POST) and $_SERVER["REQUEST_METHOD"] == "POST"){
        $newFile = getNewFile($_POST['username'], $_POST['taskNumber'], $_POST['months']);
    }
}
//Error output browser
if (!empty($_POST)) {
    if (isset($_POST['submit']) && !empty($_POST['username']) ) {
        $username = $_POST['username'];
    } else {
        die('Username is empty');
    }
    if (is_numeric($_POST['username'])===false) {
        $username = $_POST['username'];
    } else {
        die('Username is not string');
    }
    if (isset($_POST['submit']) && !empty($_POST['taskNumber'])) {
        $taskNumber = $_POST['taskNumber'];
    } else {
        die('Task Number is empty');
    }
    if (is_numeric($_POST['taskNumber'])){
        $taskNumber = $_POST['taskNumber'];
    } else {
        die('Task Number should be indicated as a numerical value');
    }

    if (isset($_POST['submit']) && !empty($_POST['months'])) {
        $months = $_POST['months'];
    } else {
        die('Number of Months is empty');
    }
    if (is_numeric($_POST['months'])){
        $months = $_POST['months'];
    } else {
        die('Number of months should be indicated as a numerical value');
    }
    include_once 'index.phtml';
}



