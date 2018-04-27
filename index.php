<?php
function add_month($time, $num=1) {
    $day = date('j',$time);
    $month = date('n',$time);
    $year = date('Y',$time);

    // Switching months
    $month += $num;
    if ($month > 12) {
        $year += floor($month/12);
        $month =($month%12);
        // Check on december
        if (!$month) {
            $month=12;
            $year--;
        }
    }

    // Is it the last day of month?
    if ($day == date('t',$time)) {
        $day = 31;
    }
    // Check out date
    while(true) {
        if (checkdate($month ,$day,$year)){
            break;
        }
        $day--;
    }
    // Return date into TIMESTAMP
    return mktime(0,0,0,$month,$day,$year);
}

function getNewFile ($username, $taskNumber, $months) {
    $username = preg_replace('#[^\p{L}\p{N}\s-]+#u','',$username);
    $taskNumber = intval($taskNumber);
    $months = intval($months);

    $execDate = date('d,m,Y', time());
    $endDate = date('d,m,Y', add_month(strtotime($execDate), $months));
    $newFile = str_replace(array('%USERNAME%', '%NUMBER%', '%EXECDATE%', '%MONTHNUM%', '%ENDDATE%'), array($username, $taskNumber, $execDate, $months, $endDate), file_get_contents('/www/template.tpl'));
    return $newFile;

}
    //Error output and CLI
if (isset($argv[0])) {
    if (!isset($argv[1])) {
        die ("ERROR: Username not specified");
    } elseif (!isset($argv[2])) {
        die("ERROR: Task number not specified");
    } elseif (!isset($argv[3])) {
        die("ERROR: Month number not specified");
    }
    echo  getNewFile($argv[1],$argv[2],$argv[3]);
} else {
    if (isset($_POST) and $_SERVER["REQUEST_METHOD"] == "POST"){
        $newFile = getNewFile($_POST['username'], $_POST['taskNumber'], $_POST['months']);
    }

    include 'index.phtml';
}
