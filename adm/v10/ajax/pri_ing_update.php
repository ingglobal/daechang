<?php
include_once('./_common.php');

$msg = '';
if(!$pri_idx) $msg = 'mms_idx가 제대로 넘어오지 않았어요';

$chk_sql = " SELECT COUNT(*) AS cnt FROM {$g5['production_item_table']} WHERE pri_idx = '{$pri_idx}' AND pri_status NOT IN ('trash','delete') ";
$chk = sql_fetch($chk_sql);
if(!$chk['cnt']) $msg = $pri_idx.'는 존재하지 않거나 삭제 되었을 가능성이 있어요.';

if($chk['cnt']){
    $sql = " UPDATE {$g5['production_item_table']} SET pri_ing = '{$ing_flag}' WHERE pri_idx = '{$pri_idx}' ";
    sql_query($sql);
}

if(!$msg) $msg = 'ok_'.$ing_flag;

echo $msg;
