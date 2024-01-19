<?php
$sub_menu = '940130';
include_once('./_common.php');

// print_r2($_POST);exit;
/*
[sst] => boj_reg_dt
[sod] => DESC
[sfl] => 
[stx] => 
[sfl2] => 
[stx2] => 
[page] => 1
[token] => dbe4218c74eca8c589ca0eec78b0f494
[file_name] => bom_jig_list
[mms_idx] => 170
[jig_code] => L1
[bom_idx] => 2130
[bom_name] => LWR BRKT ASSY-RR A/REST FRM
[mb_id] => 01080996885
[bmw_type] => day
[test_yn] => 0
[act_button] => 등록
*/
$boj_table = $g5['bom_jig_table'];
$bmw_table = $g5['bom_mms_worker_table'];
if ($_POST['act_button'] == "등록"){
    //boj의 기존의 데이터가 있으면 수정해라
    $chk_boj = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$boj_table} 
            WHERE mms_idx = {$mms_idx}
                AND bom_idx = {$bom_idx}
                AND boj_code = {$jig_code}
                AND boj_status = 'ok'
    ");
    if($chk_boj['cnt']){
        $boj_sql = " UPDATE {$boj_table} SET
                    boj_test_yn = '{$test_yn}'
                    , boj_update_dt = '".G5_TIME_YMDHIS."'
                WHERE mms_idx = {$mms_idx}
                    AND bom_idx = {$bom_idx}
                    AND boj_code = {$jig_code}
                    AND boj_status = 'ok'
        ";
    }
    // boj의 기존 데인터가 없으면 새로 등록
    else{
        $boj_sql = " INSERT INTO {$boj_table} SET
                    mms_idx = '{$mms_idx}'
                    , bom_idx = '{$bom_idx}'
                    , boj_code = '{$jig_code}'
                    , boj_test_yn = '{$test_yn}'
                    , boj_status = 'ok'
                    , boj_reg_dt = '".G5_TIME_YMDHIS."'
                    , boj_update_dt = '".G5_TIME_YMDHIS."'
        ";
    }
    sql_query($boj_sql,1);

    // sort의 최대값을 추출한다.
    $bmw_old = sql_fetch(" SELECT MAX(bmw_sort) AS max_sort
                                , GROUP_CONCAT(DISTINCT bmw_type) AS bmw_types
                            FROM {$bmw_table}
                            WHERE mms_idx = {$mms_idx}
                                AND bom_idx = {$bom_idx}
                                AND bmw_status = 'ok'
    ");
    $max_sort = $bmw_old['max_sort'] + 1;
    $bmw_types = ($bmw_old['bmw_types'])?explode(',',$bmw_old['bmw_types']):array();
    $bmw_type = ($bmw_type == 'day' && in_array($bmw_type, $bmw_types) || $bmw_type == 'night' && in_array($bmw_type, $bmw_types)) ? 'sub' : $bmw_type;

    //동일한 조건에서 bmw_main_yn = 1이 있는지 확인한다.
    $main = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$bmw_table}
                                WHERE bom_idx = {$bom_idx}
                                    AND bmw_type = '{$bom_type}'
                                    AND bmw_main_yn = '1'
                                    AND bmw_status = 'ok'
    ");
    $main_yn = 0;
    if(!$main['cnt']){
        $main_yn = ($bmw_type == 'day' || $bmw_type == 'night') ? 1 : 0;
    }


    //bmw의 기존데이터가 있으면 수정
    $chk_bmw = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$bmw_table} 
            WHERE mms_idx = {$mms_idx}
                AND bom_idx = {$bom_idx}
                AND mb_id = {$mb_id}
                AND bmw_status = 'ok'
    ");
    if($chk_bmw['cnt']){
        $bmw_sql = " UPDATE {$bmw_table} SET
                        bmw_test_yn = '{$test_yn}'
                        , bmw_type = '{$bmw_type}'
                        , bmw_main_yn = '{$main_yn}'
                        , bmw_update_dt = '".G5_TIME_YMDHIS."'
                    WHERE mms_idx = {$mms_idx}
                        AND bom_idx = {$bom_idx}
                        AND mb_id = {$mb_id}
                        AND bmw_status = 'ok'
        ";
    }
    // bmw의 기존 데인터가 없으면 새로 등록
    else{
        $bmw_sql = " INSERT INTO {$bmw_table} SET
                    mms_idx = '{$mms_idx}'
                    , bom_idx = '{$bom_idx}'
                    , mb_id = '{$mb_id}'
                    , bmw_type = '{$bmw_type}'
                    , bmw_sort = '{$max_sort}'
                    , bmw_main_yn = '{$main_yn}'
                    , bmw_test_yn = '{$test_yn}'
                    , bmw_status = 'ok'
                    , bmw_reg_dt = '".G5_TIME_YMDHIS."'
                    , bmw_update_dt = '".G5_TIME_YMDHIS."'
        ";
    }
    sql_query($bmw_sql,1);




    // 캐시 업데이트
    $cache_file = G5_DATA_PATH.'/cache/socket-jig.php';
    @unlink($cache_file);
        
    $list = array();
    // $list_idx2 = array();
    $sql = "SELECT * FROM {$g5['bom_jig_table']} WHERE boj_status = 'ok' ORDER BY mms_idx, bom_idx";
    $result = sql_query($sql,1);
    // echo $sql;
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $row['mms'] = get_table('mms','mms_idx',$row['mms_idx']);
        $row['bom'] = get_table('bom','bom_idx',$row['bom_idx']);
        // print_r2($row);
        $ar['mms_name'] = addslashes($row['mms']['mms_name']);
        $ar['boj_code'] = $row['boj_code'];
        $ar['bom_part_no'] = $row['bom']['bom_part_no'];
        $ar['bom_name'] = addslashes($row['bom']['bom_name']);
        $ar['bom_idx'] = $row['bom_idx'];
        $ar['mms_idx'] = $row['mms_idx'];
        $ar['boj_status'] = $row['boj_status'];
        $list[$row['mms_idx']][$row['bom_idx']][] = $ar;
        $list2[$row['mms_idx']][$row['boj_code']][] = $ar;
        unset($ar);
    }
    // print_r2($list);
    // print_r2($list_idx2);

    // 캐시파일 생성
    $handle = fopen($cache_file, 'w');
    $cache_content = "<?php\n";
    $cache_content .= "if (!defined('_GNUBOARD_')) exit;\n";
    $cache_content .= "\$g5['socket_jig']=".var_export($list2, true).";\n";
    $cache_content .= "?>";
    fwrite($handle, $cache_content);
    fclose($handle);


    // python용 변수 생성
    $cache_file = G5_DATA_PATH.'/python/data_jig.py';
    @unlink($cache_file);
    // 캐시파일 생성
    $handle = fopen($cache_file, 'w');
    // PHP 배열을 JSON 형식으로 인코딩
    $cache_content = "data_jig=".json_encode($list2, JSON_PRETTY_PRINT)."\n";
    fwrite($handle, $cache_content);
    fclose($handle);


}
else if ($_POST['act_button'] == "테스트데이터전부삭제"){
    $boj_sql = " DELETE FROM {$g5['bom_jig_table']} WHERE boj_test_yn = '1'
    ";
    sql_query($boj_sql,1);
    $bmw_sql = " DELETE FROM {$g5['bom_mms_worker_table']} WHERE bmw_test_yn = '1'
    ";
    sql_query($bmw_sql,1);
}









goto_url('./'.$file_name.'.php?'.$qstr, false);