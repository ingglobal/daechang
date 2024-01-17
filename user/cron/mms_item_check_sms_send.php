<?php
include_once('./_common.php');

$demo = 0; //demo mode = 1

// $g5['title'] = '점검설비의 생산품질검사 문자전송';
// include_once('./_head.sub.php');

/*
{$g5['production_item_table']} pic_date, pic_ing = 0 or 1, pri_idx
{$g5['production_item_count_table']} mms_idx
{$g5['mms_table']} mms_item_check_yn = 1
$g5['setting']['mng_mms_item_checker'] = 'test1,test2'
BIZ_LOG
BIZ_MSG array(
    CMID = 1
)
*/
// 발신번호 정보가 없으면 실행중지
if(!$g5['setting']['mng_send_phone']) exit;
$send_phone = $g5['setting']['mng_send_phone'];

$checkers = ($g5['setting']['mng_mms_item_checker'])?explode(',',$g5['setting']['mng_mms_item_checker']):array();
// 수신자 mb_id가 존재하지 않으면 실행중지
if(!count($checkers)) exit;

$sms_use = $g5['setting']['mng_sms_use4'];
// 전송 문자열 내용이 없으면 실행중지
if(!$sms_use) exit;

$todate = G5_TIME_YMD;
$prefix = '-DS4-';
$sms_def = $g5['setting']['mng_sms_cont4'].$prefix;
$sms_con = $sms_def;
$msql = " SELECT mms_idx,mms_name,mms_item_check_yn 
                FROM {$g5['mms_table']} 
            WHERE mms_status NOT IN ('trash','delete')
                AND mms_item_check_yn = '1'
";
$mres = sql_query($msql,1);
for($i=0;$mrow=sql_fetch_array($mres);$i++){
    // print_r2($mrow);
    // 해당(설비)에 (오늘날짜)로 생산된 제품이 존재하는지 첵크 
    $psql = " SELECT SUM(pic.pic_value) AS cnt 
                FROM {$g5['production_item_count_table']} pic
                INNER JOIN {$g5['production_item_table']} pri ON pic.pri_idx = pri.pri_idx
            WHERE pri.mms_idx = '{$mrow['mms_idx']}'
                AND pic.pic_date = '".G5_TIME_YMD."'
    ";
    $pres = sql_fetch($psql);
    if(!$pres['cnt']) continue;
    // (오늘날짜)에 해당 (설비)로 (-DC4-)품질팀에 문자를 보낸 내역이 있는지 확인
    $ssql = " SELECT COUNT(*) AS cnt FROM BIZ_LOG
            WHERE SEND_TIME LIKE '{$todate}%'
                AND MSG_BODY LIKE '%{$mrow['mms_name']}%'
                AND MSG_BODY LIKE '%{$prefix}%'
    ";
    $sres = sql_fetch($ssql);
    // 오늘 위의 조건으로 해당 설비의 품질검사요청 문자를 보낸적이 있으면 건너띄자
    if($sres['cnt']) continue;
    
    //여기까지 왔으면 (오늘날짜)로 해당(설비)에 (생산제품)이 있고, 문자도 (발송하기 전)이다.
    //문자발송을 해야 한다.
    $sms_con = str_replace("{설비명}",$mrow['mms_name'],$sms_con);
    foreach($checkers as $chk_mb_id){
        $mb = get_member($chk_mb_id);
        // 수신자의 휴대폰번호가 없으면 건너띈다.
        if(!$mb['mb_hp']) continue;
        
        $dest_phone = $mb['mb_hp'];
        $sms_arr = array(
            'MSG_TYPE' => '0',
            'DEST_PHONE' => $dest_phone,
            'SEND_PHONE' => $send_phone,
            'MSG_BODY' => $sms_con
        );
        send_sms_purio($sms_arr);
    }

    // 다음 루프의 데이터를 위해 기본값으로 되돌려 놓는다.
    $sms_con = $sms_def;
}