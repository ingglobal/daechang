<?php
include_once('./_common.php');

$msg = 'ok';
if(!$bmw_idx)
    $msg = '작업자ID 데이터가 없습니다.';

$sql = " DELETE FROM {$g5['bom_mms_worker_table']}
            WHERE bmw_idx = '{$bmw_idx}'
";
sql_query($sql,1);

echo $msg;