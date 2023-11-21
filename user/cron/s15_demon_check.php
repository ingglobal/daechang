<?php
/*
vi /etc/crontab
* * * * * root wget -O - -q -t 1 http://daechang.epcs.co.kr/user/cron/s15_demon_check.php
*/
include_once('./_common.php');

$sql = " SELECT COUNT(*) as cnt FROM 'g5_1_socket' WHERE sck_dt >= NOW() - INTERVAL 5 SECONDS ";
$res = sql_fetch_pg($sql);

if(!$res['cnt']){
    //s15.py 프로세스 종료
    exec("pkill -f s15.py");
    
    // 지연현상이 있을 수 있으니 2초의 sleep추가
    sleep(2);

    //s15.py 프로세스 다시 실행
    exec("nohup python3 /home/daechang/www/user/socket/s15.py & > /dev/null");
}