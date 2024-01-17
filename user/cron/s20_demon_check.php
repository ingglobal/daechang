<?php
/*
vi /etc/crontab
* * * * * root wget -O - -q -t 1 http://daechang.epcs.co.kr/user/cron/s20_demon_check.php
*/
include_once('./_common.php');

$sql = " SELECT COUNT(*) as cnt FROM g5_1_socket WHERE sck_dt >= CURRENT_TIMESTAMP - INTERVAL '50' SECOND ";
// echo $sql.BR;
$res = sql_fetch_pg($sql);

if(!$res['cnt']){
    //s20.py 프로세스 종료
    exec("pkill -f s20.py");
    
    // 지연현상이 있을 수 있으니 2초의 sleep추가
    sleep(2);

    //s20.py 프로세스 다시 실행
    exec("nohup python3 /home/daechang/www/user/socket/s20.py & > /dev/null");
    
    $writefile = '../../data/cron/s20.txt';
    $person = date("Y-m-d H:i:s")."\n";  //<-- 입력하고자 하는 값
    file_put_contents($writefile, $person, FILE_APPEND | LOCK_EX);
}