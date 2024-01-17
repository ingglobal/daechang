<?php
include_once('./_common.php');
$res = array('ok' => true);

if(!$mto_idx){
    $res['ok'] = false;
    $res['msg'] = '발주ID번호가 넘어오지 않았습니다.';
}

if(!$mb_id_check){
    $res['ok'] = false;
    $res['msg'] = '품질검사자 아이디가 제대로 넘어오지 않았습니다.';
}

if(!$moi_idx){
    $res['ok'] = false;
    $res['msg'] = '발주제품ID번호가 넘어오지 않았습니다.';
}

if(!$moi_count){
    $res['ok'] = false;
    $res['msg'] = '발주갯수가 넘어오지 않았습니다.';
}
/*
"mto_idx": mto_idx,
"moi_idx": moi_idx,
"moi_count": moi_cnt,
"flag":flag,
"moi_status":cur_status,
"mb_id_check":chker,
"pass":pass,
"nopass":nopass,
"moi_check_text":msg,
*/
// echo json_encode($_POST);exit;
if($res['ok']){
    if($flag){
        $mto = sql_fetch(" SELECT mb_id_driver FROM {$g5['material_order_table']} WHERE mto_idx='{$mto_idx}' ");
        $mb_id_driver = ($mto['mb_id_driver']) ? $mto['mb_id_driver'] : $mb_id_check;
        //moi_idx번호가 존재하는지 확인
        $chk_sql = " SELECT COUNT(*) AS cnt, bom_idx, moi_status FROM {$g5['material_order_item_table']} WHERE moi_idx = '{$moi_idx}' AND moi_status IN ('ok','ready','reject') ";
        $chk = sql_fetch($chk_sql);
        $bom = sql_fetch(" SELECT * FROM {$g5['bom_table']} WHERE bom_idx = '{$chk['bom_idx']}' ");
        //입고처리 불가능할때
        if(!$chk['cnt']){
            $res['ok'] = false;
            $res['msg'] = '검사대기중 또는 입고완료상태일 수 있습니다.';
        }
        //입고처리 가능할때
        else {
            //검사자를 업데이트한다.
            $mto_sql = " UPDATE {$g5['material_order_table']} SET
                            mb_id_check = '{$mb_id_check}'
                        WHERE mto_idx = '{$mto_idx}'
            ";
            sql_query($mto_sql,1);
            //해당발수제품의 내용을 업데이트한다.
            $moi_sql = " UPDATE {$g5['material_order_item_table']} SET
                        mb_id_driver = '{$mb_id_driver}'
                        , mb_id_check = '{$mb_id_check}'
                        , moi_check_yn = '1'
                        , moi_check_text = ''
                        , moi_history = CONCAT(moi_history,'\ninput|".G5_TIME_YMDHIS."')
                        , moi_status = 'input'
                        , moi_input_dt = '".G5_TIME_YMDHIS."'
                        , moi_update_dt = '".G5_TIME_YMDHIS."'
                    WHERE moi_idx = '{$moi_idx}'
            ";
            sql_query($moi_sql,1);

            // 혹시라도 존재할지 모르는 해당moi_idx가진 레코드를 삭제하자
            $del_sql = " DELETE FROM {$g5['material_table']}
                            WHERE moi_idx = '{$moi_idx}'
            ";
            sql_query($del_sql,1);

            //발주개수만큼 재고테이블에 입고처리한다.
            $mtr_sql = " INSERT INTO {$g5['material_table']}
                (com_idx, cst_idx_provider, cst_idx_customer, bom_idx, moi_idx, mtr_name, mtr_part_no, mtr_price, mtr_value, mtr_date, mtr_type, mtr_status, mtr_auth_dt, mtr_reg_dt, mtr_update_dt) VALUES
            ";

            for($i=0;$i<$moi_count;$i++){
                $mtr_sql .= ($i==0) ? '':',';
                $mtr_sql .= "('{$_SESSION['ss_com_idx']}','{$bom['cst_idx_provider']}','{$bom['cst_idx_customer']}','{$bom['bom_idx']}', '{$moi_idx}','{$bom['bom_name']}','{$bom['bom_part_no']}','{$bom['bom_price']}','1','".G5_TIME_YMD."','material','ok','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."','".G5_TIME_YMDHIS."')";
            }
            sql_query($mtr_sql,1);
        }
    }
    else{
        //검사자를 초기화하는 업데이트를 한다.
        $mto_sql = " UPDATE {$g5['material_order_table']} SET
                mb_id_check = ''
            WHERE mto_idx = '{$mto_idx}'
        ";
        sql_query($mto_sql,1);
        //moi의 정보를 업데이트한다.
        $moi_status = ($nopass) ? 'reject' : 'ready';
        if($nopass && $moi_check_text){
            $moi_chk_txt = $moi_check_text;
            $mb_chk = $mb_id_check;
        }
        else if($nopass && !$moi_check_text){
            $moi_chk_txt = '불합격';
            $mb_chk = $mb_id_check;
        }
        else{
            $moi_chk_txt = '';
        }
        $moi_sql = " UPDATE {$g5['material_order_item_table']} SET
                    mb_id_driver = ''
                    , mb_id_check = '{$mb_chk}'
                    , moi_history = CONCAT(moi_history,'\nready|".G5_TIME_YMDHIS."')
                    , moi_status = '{$moi_status}'
                    , moi_check_yn = '0'
                    , moi_check_text = '{$moi_chk_txt}'
                    , moi_input_dt = '0000-00-00 00:00:00'
                    , moi_update_dt = '".G5_TIME_YMDHIS."'
                WHERE moi_idx = '{$moi_idx}'
        ";
        sql_query($moi_sql,1);

        $mtr_sql = " DELETE FROM {$g5['material_table']} WHERE moi_idx = '{$moi_idx}'
        ";
        sql_query($mtr_sql,1);
    }
}

echo json_encode($res);