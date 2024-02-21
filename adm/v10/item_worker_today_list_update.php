<?php
$sub_menu = "922120";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

// print_r2($_POST);
// exit;
auth_check($auth[$sub_menu], 'w');

check_admin_token();
if ($_POST['act_button'] == "선택수정") {
    /*
    $target_day
    $prd_idx
    $pri_idx
    $bom_idx
    $bom_type
    $bom_part_no
    $bom_name
    $mms_idx
    $mb_id
    $pri_value
    $pic_sum_old
    $pic_sum
    */
    // print_r2($chk);
    $sec = 64;
    foreach($chk as $cv){
        $pic_sum[$cv] = preg_replace("/,/","",$pic_sum[$cv]);
        $dif_sum = $pic_sum[$cv] - $pic_sum_old[$cv];
        $stock_tbl = ($bom_type[$cv] == 'product') ? $g5['item_table'] : $g5['material_table'];
        $prf = ($bom_type[$cv] == 'product') ? 'itm' : 'mtr';
        // $dif_sum 만큼 추가해라
        if($dif_sum > 0){
            $maxres = sql_fetch(" SELECT MAX(pic_reg_dt) AS max_dt
                    FROM {$g5['production_item_count_table']}
                    WHERE pic_date = '{$target_day}'
                        AND pri_idx = '{$pri_idx[$cv]}'
                        AND mb_id = '{$mb_id[$cv]}'
            ");
            $var_dt = '0000-00-00 00:00:00';
            if($pic_sum_old == 0){
                // 해당 작업자가 주간 작업자인지 오전 작업자인지 확인하여
                // 그에 맞는 시작시간을 정한다.
                $tres = sql_fetch(" SELECT shf_start_time FROM {$g5['shift_table']} WHERE shf_status = 'ok' AND (shf_name = '주간' OR shf_idx = '425') LIMIT 1 ");
                $start_dt = ($tres['shf_start_time']) ? $target_day.' '.$tres['shf_start_time'] : $target_day.' 07:00:00';
                $var_dt = get_secAddDateTime($start_dt,$sec);
            }
            else{
                $var_dt = get_secAddDateTime($maxres['max_dt'],$sec);
            }
            $shf_idx = shift_idx($var_dt);
            
            $prf_status = ($bom_type[$cv] == 'product') ? 'finish' : 'finish';
            $psql = " INSERT INTO {$g5['production_item_count_table']}
            (pri_idx,mb_id,pic_ing,pic_value,pic_date,pic_reg_dt,pic_update_dt) VALUES
            ";
            $ssql = " INSERT INTO {$stock_tbl}
            (com_idx,mms_idx,prd_idx,pri_idx,bom_idx,shf_idx,mb_id,".$prf."_part_no,".$prf."_name,".$prf."_type,".$prf."_value,".$prf."_status,".$prf."_date,".$prf."_reg_dt,".$prf."_update_dt) VALUES
            ";
            for($i=0;$i<$dif_sum;$i++){
                $psql .= ($i == 0) ? '' : ',';
                $psql .= "('{$pri_idx[$cv]}','{$mb_id[$cv]}','1','1','{$target_day}','{$var_dt}','{$var_dt}')";
                $ssql .= ($i == 0) ? '' : ',';
                $ssql .= "('{$g5['setting']['set_com_idx']}','{$mms_idx[$cv]}','{$prd_idx[$cv]}','{$pri_idx[$cv]}','{$bom_idx[$cv]}','{$shf_idx}','{$mb_id[$cv]}','{$bom_part_no[$cv]}','{$bom_name[$cv]}','{$bom_type[$cv]}','1','{$prf_status}','{$target_day}','{$var_dt}','{$var_dt}')";
                $var_dt = get_secAddDateTime($var_dt,$sec);
                $shf_idx = shift_idx($var_dt);
            }
            // echo $psql."<br>";
            sql_query($psql,1);
            // echo $ssql."<br>";
            sql_query($ssql,1);
        }
        // $dif_sum 만큼 차감해라
        else if($dif_sum < 0){
            $dif_sum = abs($dif_sum);
            // 삭제시에는 등록시간을 기준으로 제일 빠른시간과 제일 늦은 시간을 제외한 레코드중에 삭제한다.
            // $dpsql = " DELETE FROM {$g5['production_item_count_table']}
            //     WHERE pic_idx IN (
            //         SELECT pic_idx FROM {$g5['production_item_count_table']}
            //             WHERE pic_date = '{$target_day}'
            //                 AND pri_idx = '{$pri_idx[$cv]}'
            //                 AND mb_id = '{$mb_id[$cv]}'
            //             ORDER BY pic_reg_dt DESC LIMIT $dif_sum
            //     )
            // ";
            $dpsql = " DELETE pic FROM {$g5['production_item_count_table']} pic
            INNER JOIN (
                    SELECT pic_idx FROM {$g5['production_item_count_table']}
                        WHERE pic_date = '{$target_day}'
                            AND pri_idx = '{$pri_idx[$cv]}'
                            AND mb_id = '{$mb_id[$cv]}'
                        ORDER BY pic_reg_dt DESC LIMIT $dif_sum
            ) spic
            ON pic.pic_idx = spic.pic_idx
            ";
            // echo $dpsql."<br>";
            sql_query($dpsql,1);
            // item 또는 material 삭제한다.
            // $dssql = " DELETE FROM {$stock_tbl}
            //     WHERE {$prf}_idx IN (
            //         SELECT {$prf}_idx FROM {$stock_tbl}
            //             WHERE {$prf}_date = '{$target_day}'
            //                 AND pri_idx = '{$pri_idx[$cv]}'
            //                 AND mb_id = '{$mb_id[$cv]}'
            //             ORDER BY {$prf}_reg_dt DESC LIMIT $dif_sum
            //     )
            // ";
            $dssql = " DELETE stc FROM {$stock_tbl} stc
            INNER JOIN (
                    SELECT {$prf}_idx FROM {$stock_tbl}
                        WHERE {$prf}_date = '{$target_day}'
                            AND pri_idx = '{$pri_idx[$cv]}'
                            AND mb_id = '{$mb_id[$cv]}'
                        ORDER BY {$prf}_reg_dt DESC LIMIT $dif_sum
            ) sstc
            ON stc.{$prf}_idx = sstc.{$prf}_idx
            ";
            // echo $dssql."<br>";
            sql_query($dssql,1);
        }
    }
}

foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
                $qstr .= '&'.$key.'[]='.$v2;
            }
        }
        else {
            $qstr .= '&'.$key.'='.$value;
        }
    }
}

goto_url('./item_worker_today_list.php?'.$qstr);