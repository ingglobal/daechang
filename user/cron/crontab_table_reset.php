<?php
/*
crontab -e
0 4 * * * wget -O - -q -t 1 http://daechang.epcs.co.kr/user/cron/crontab_table_reset.php
*/
include_once('./_common.php');

$truncate_sql = " TRUNCATE {$g5['crontab_log_table']} ";
sql_query($truncate_sql,1);
