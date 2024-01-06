<?php
$sub_menu = "922110";
include_once("./_common.php");
//
auth_check($auth[$sub_menu], 'w');

if(!$bom_idx) alert('제품을 선택해 주세요.');
if(!$prd_idx) alert('수주를 선택해 주세요.');
if(!$prm_date) alert('생산시작일을 입력해 주세요.');
if(!$prm_value) alert('지시수량을 입력해 주세요.');

$prm_value = preg_replace("/,/","",$prm_value);
if($prm_memo) $prm_memo = trim(stripslashes($prm_memo));

// print_r2($_REQUEST);
// exit;

// production 업데이트
// $ar['table']  = 'g5_1_production_main';
// $ar['com_idx']  = $_SESSION['ss_com_idx'];
// $ar['prd_idx']  = $prd_idx;
// $ar['prm_idx']  = $prm_idx;
// $ar['bom_idx']  = $bom_idx;
// $ar['boc_idx']  = $boc_idx;
// $ar['prm_order_no']  = $prm_order_no;
// $ar['prm_date']  = $prm_date;
// $ar['prm_memo']  = $prm_memo;
// $ar['prm_status']  = $prm_status;
// // print_r2($ar);
// $prd_idx = update_db($ar);
// unset($ar);
// echo $prd_idx;
$prd = get_table('production','prd_idx',$prd_idx);
// $prd['prd_value'] = $prm_value;

// 등록모드일때
if($w == ''){
    $prm_res = sql_fetch(" SELECT prm_idx, prd_idx FROM {$g5['production_main_table']}
        WHERE bom_idx = '{$bom_idx}'
            AND boc_idx = '{$boc_idx}'
            AND prm_date = '{$prm_date}'
            AND prm_status NOT IN ('trash','delete')
        LIMIT 1
    ");
    $prm_idx = $prm_res['prm_idx'];
    $prd_idx = ($prm_res['prd_idx']) ? $prm_res['prd_idx'] : $prd_idx;

    // 동일한 조건의 등록된 prm_idx가 없으면 신규등록하자
    if(!$prm_idx){
        $tdcode = preg_replace('/[ :-]*/','',$d[$k]);
        $prm_order_no = "PRD-".$tdcode.wdg_get_random_string('09',10);
        $sql = " INSERT INTO {$g5['production_main_table']} SET
                    com_idx = '{$g5['setting']['set_com_idx']}'
                    , prd_idx = '{$prd_idx}'
                    , bom_idx = '{$bom_idx}'
                    , boc_idx = '{$boc_idx}'
                    , prm_order_no = '{$prm_order_no}'
                    , prm_date = '{$prm_date}'
                    , prm_value = {$prm_value}
                    , prm_memo = {$prm_memo}
                    , prm_status = '{$prm_status}'
                    , prm_reg_dt = '".G5_TIME_YMDHIS."'
                    , prm_update_dt = '".G5_TIME_YMDHIS."'
        ";
        sql_query($sql,1);
        $prm_idx = sql_insert_id();
    }

    // 생산계획 완제품그룹 등록
    insert_production_item($prd_idx,$prm_idx,$boc_idx,$bom_idx,$prm_date,$prm_value,$prm_status);
}
//수정모드일때
else if($w == 'u'){
    // print_r2($_REQUEST);

    if ($_POST['act_button'] == "초기화") {
        // 생산아이템 정보 초기화
        $sql = " DELETE FROM {$g5['production_item_table']} 
                    WHERE prm_idx = '{$prm_idx}'
        ";
        // sql_query($sql,1);

        $sql = " SELECT prm_idx
                        ,prd_idx
                        ,boc_idx
                        ,bom_idx
                        ,prm_date
                        ,prm_value
                        ,prm_status
                FROM {$g5['production_main_table']}
                WHERE prm_idx = '{$prm_idx}'
                    AND prm_status NOT IN ('trash','delete')
                LIMIT 1
        ";
        $prm = sql_fetch($sql);
        // echo $sql;
        // print_r2($_POST);
        // print_r2($prm);exit;
        if($prm['prm_idx']){
            $sql = " UPDATE {$g5['production_main_table']} SET prm_status = 'confirm'
                        WHERE prm_idx = '{$prm_idx}'
            ";
            // BOM 구조를 따라서 관련 정보 생성
            $list = insert_production_item($prm['prd_idx'],$prm['prm_idx'],$prm['boc_idx'],$prm['bom_idx'],$prm['prm_date'],$prm['prm_value'],'confirm');
        }
    }
    // 초기화가 아니면 정보 업데이트
    else {

        $sql = " UPDATE {$g5['production_main_table']}
            SET prm_value = '{$prm_value}'
                , prm_memo = '{$prm_memo}'
                , prm_status = '{$prm_status}'
            WHERE prm_idx = '{$prm_idx}'
        ";
        sql_query($sql,1);

        if(is_array($_REQUEST['chk'])) {
            foreach($_REQUEST['chk'] as $k1=>$v1)
            {
                // echo $k1.'/'.$v1.BR;
                // echo 'pri_idx: '.$_REQUEST['pri_idxs'][$k1].BR;
                // echo 'prm_idx: '.$_REQUEST['prm_idxs'][$k1].BR;
        
                // 천단위 제거
                $_REQUEST['prm_values'][$k1] = preg_replace("/,/","",$_REQUEST['prm_values'][$k1]);
        
                // 생산아이템 정보 입력 ---------------------------------------------------------------
                $sql3 = "   UPDATE {$g5['production_item_table']} SET
                                mms_idx = '".$_REQUEST['mms_idxs'][$k1]."'
                                , com_idx = '".$_SESSION['ss_com_idx']."'
                                , mb_id = '".$_REQUEST['mb_ids'][$k1]."'
                                , pri_value = '".$_REQUEST['pri_values'][$k1]."'
                                , pri_ing = '".$_REQUEST['pri_ing'][$k1]."'
                                , pri_update_dt = '".G5_TIME_YMDHIS."'
                            WHERE pri_idx = '".$_REQUEST['pri_idxs'][$k1]."'
                ";
                // echo $sql3.BR;
                sql_query($sql3,1);
            }
        }
    }
}

// exit;
$qstr .= ($st_date)?'&st_date='.$st_date:'';
$qstr .= ($en_date)?'&en_date='.$en_date:'';
goto_url('./production_form.php?'.$qstr.'&w=u&prm_idx='.$prm_idx, false);