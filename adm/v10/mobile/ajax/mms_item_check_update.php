<?php
include_once('./_common.php');

$msg = '';
if(!$mms_idx) $msg = 'mms_idx가 제대로 넘어오지 않았어요';

$chk_sql = " SELECT COUNT(*) AS cnt FROM {$g5['mms_table']} WHERE mms_idx = '{$mms_idx}' AND mms_status NOT IN ('trash','pending') ";
$chk = sql_fetch($chk_sql);
if(!$chk['cnt']) $msg = $mms_idx.'는 존재하지 않거나 삭제 되었을 가능성이 있어요.';

if($chk['cnt']){
    $sql = " UPDATE {$g5['mms_table']} SET mms_item_check_yn = '{$flag}' WHERE mms_idx = '{$mms_idx}' ";
    // $msg = $sql;
    sql_query($sql);
}

if(!$msg) $msg = 'ok_'.$flag;

echo $msg;