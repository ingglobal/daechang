<?php
include_once('./_common.php');

$sql = " SELECT bmw_idx
                , bom_idx
                , mms_idx
                , bmw.mb_id
                , mb.mb_name
                , bmw_type
        FROM {$g5['bom_mms_worker_table']} bmw
        LEFT JOIN {$g5['member_table']} mb ON mb.mb_id = bmw.mb_id
        WHERE bmw_status = 'ok'
            AND bom_idx = '{$bom_idx}'
            AND mms_idx = '{$mms_idx}'
            AND mb_leave_date = ''
            AND mb_intercept_date = ''
";
$res = sql_query($sql,1);
$options = '';
for($i=0;$row=sql_fetch_array($res);$i++){
    $options .= '<option value="'.$row['mb_id'].'">'.$row['mb_name'].'('.$g5['set_bmw_type_value'][$row['bmw_type']].')'.'</option>';
}

echo $options;