<?php
include_once('./_common.php');
// 'bom_idx': bom_idx, 'mms_idx': mms_idx, 'mb_id': mb_id, 'bmw_type': bmw_type
$msg = 'ok';

if(!$bom_idx)
    $msg = '제품ID 데이터가 없습니다.';

if(!$mms_idx)
    $msg = '설비ID 데이터가 없습니다.';

if(!$mb_id)
    $msg = '작업자ID 데이터가 없습니다.';

if(!$bmw_type)
    $msg = '작업유형 데이터가 없습니다.';

// 동일한 조건의 작업자가 존재하는지 확인
$ol_res = sql_fetch(" SELECT COUNT(*) AS cnt
            FROM {$g5['bom_mms_worker_table']}
            WHERE bom_idx = '{$bom_idx}'
                AND mms_idx = '{$mms_idx}'
                AND mb_id = '{$mb_id}'
                AND bmw_type = '{$bmw_type}'
                AND bmw_status = 'ok'
");
if($ol_res['cnt'])
    $msg = '동일한 데이터가 이미 존재합니다.';

//bmw_main_yn여부를 확인하고 설정하자
$bmw_main_yn = 0;
if($bmw_type == 'day' || $bmw_type == 'night'){
    $tp_res = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$g5['bom_mms_worker_table']}
                WHERE bom_idx = '{$bom_idx}'
                    AND bmw_type = '{$bmw_type}'
                    AND bmw_main_yn = 1
                    AND bmw_status = 'ok'
    ");
    if(!$tp_res['cnt']) $bmw_main_yn = 1;
}

//
$chk_res = sql_fetch(" SELECT COUNT(*) AS cnt
                            , GROUP_CONCAT(DISTINCT bmw_type) AS bmw_types
            FROM {$g5['bom_mms_worker_table']}
            WHERE bom_idx = '{$bom_idx}'
                AND mms_idx = '{$mms_idx}'
                AND bmw_status = 'ok'
");
$cnt = $chk_res['cnt'] + 1;
$type_arr = ($chk_res['bmw_types']) ? explode(',',$chk_res['bmw_types']) : array();

$type = (in_array($bmw_type,$type_arr) && $bmw_type != 'sub') ? 'sub' : $bmw_type;
// 'bom_idx': bom_idx, 'mms_idx': mms_idx, 'mb_id': mb_id, 'bmw_type': bmw_type
if($msg == 'ok'){
    $sql = " INSERT INTO {$g5['bom_mms_worker_table']}
                SET bom_idx = '{$bom_idx}'
                    , mms_idx = '{$mms_idx}'
                    , mb_id = '{$mb_id}'
                    , bmw_type = '{$type}'
                    , bmw_sort = '{$cnt}'
                    , bmw_main_yn = '{$bmw_main_yn}'
                    , bmw_status = 'ok'
                    , bmw_reg_dt = '".G5_TIME_YMDHIS."'
                    , bmw_update_dt = '".G5_TIME_YMDHIS."'
    ";
    sql_query($sql,1);
}

echo $msg;