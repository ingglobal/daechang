<?php
include_once('./_common.php');
/*
'bom_idx': bom_idx 
,'mms_idx': mms_idx
,'mb_id': mb_id
,'prd_idx': prd_idx
,'prm_idx': prm_idx
,'boc_idx': boc_idx
,'bom_idx_parent': bom_idx_parent
,'pri_value': pri_value
,'pri_ing': pri_ing
*/
$msg = 'ok';
$chk_sql = " SELECT COUNT(*) AS cnt FROM {$g5['production_item_table']}
                WHERE com_idx = '{$g5['setting']['set_com_idx']}'
                    AND boc_idx = '{$boc_idx}'
                    AND prd_idx = '{$prd_idx}'
                    AND prm_idx = '{$prm_idx}'
                    AND bom_idx = '{$bom_idx}'
                    AND bom_idx_parent = '{$bom_idx_parent}'
                    AND mms_idx = '{$mms_idx}'
                    AND mb_id = '{$mb_id}'
                    AND pri_date = '{$pri_date}'
";
$chk_res = sql_fetch($chk_sql);

if(!$chk_res['cnt']){
    $sql = " INSERT INTO {$g5['production_item_table']}
                SET com_idx = '{$g5['setting']['set_com_idx']}'
                    , boc_idx = '{$boc_idx}'
                    , prd_idx = '{$prd_idx}'
                    , prm_idx = '{$prm_idx}'
                    , bom_idx = '{$bom_idx}'
                    , bom_idx_parent = '{$bom_idx_parent}'
                    , mms_idx = '{$mms_idx}'
                    , mb_id = '{$mb_id}'
                    , pri_value = '{$pri_value}'
                    , pri_date = '{$pri_date}'
                    , pri_ing = '{$pri_ing}'
                    , pri_status = 'confirm'
                    , pri_reg_dt = '".G5_TIME_YMDHIS."'
                    , pri_update_dt = '".G5_TIME_YMDHIS."'
    ";
    sql_query($sql,1);
    $pri_idx = sql_insert_id();
    if($pri_idx) $msg = 'ok';
    else $msg = '데이터가 제대로 등록되지 않았습니다.';
}
else{
    $msg = '동일한 내용의 데이터가 이미 존재합니다.';
}

echo $msg;