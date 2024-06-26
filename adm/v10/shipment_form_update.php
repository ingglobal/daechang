<?php
$sub_menu = "918120";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'shipment';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form_update/","",$g5['file_name']); // _form_update를 제외한 파일명

foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
    //    print_r3($key.'='.$value);
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
//                print_r3($key.$k2.'='.$v2);
                $qstr .= '&'.$key.'[]='.$v2;
            }
        }
        else {
            $qstr .= '&'.$key.'='.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value);
        }
    }
}

// 변수 재설정
for($i=0;$i<sizeof($fields);$i++) {
    // 공백 제거
    $_POST[$fields[$i]] = trim($_POST[$fields[$i]]);
    // 천단위 제거
    if(preg_match("/_price$/",$fields[$i])||preg_match("/_count$/",$fields[$i])||preg_match("/_value$/",$fields[$i]))
        $_POST[$fields[$i]] = preg_replace("/,/","",$_POST[$fields[$i]]);
}

// prior post value setting
$_POST['com_idx'] = $_SESSION['ss_com_idx'];


// 공통쿼리
$skips = array($pre.'_idx',$pre.'_sort',$pre.'_reg_dt',$pre.'_update_dt');
for($i=0;$i<sizeof($fields);$i++) {
    if(in_array($fields[$i],$skips)) {continue;}
    $sql_commons[] = " ".$fields[$i]." = '".$_POST[$fields[$i]]."' ";
}

// after sql_common value setting
// $sql_commons[] = " com_idx = '".$_SESSION['ss_com_idx']."' ";

// 공통쿼리 생성
$sql_common = (is_array($sql_commons)) ? implode(",",$sql_commons) : '';

// echo $sql_common;exit;

if ($w == '' || $w == 'c') {
    
    $c_res = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$g5['shipment_table']} 
            WHERE prd_idx = '{$prd_idx}'
                AND shp_status NOT IN ('trash','delete','del')
            GROUP BY prd_idx
    ");
    $t_cnt = $c_res['cnt'] + 1;
    $sql_common .= ",shp_sort = '{$t_cnt}'";
    $sql = "INSERT INTO {$g5_table_name} SET 
               {$sql_common} 
                , ".$pre."_reg_dt = '".G5_TIME_YMDHIS."'
                , ".$pre."_update_dt = '".G5_TIME_YMDHIS."'
	";
    
    sql_query($sql,1);
	${$pre."_idx"} = sql_insert_id();
    
}
else if ($w == 'u') {
    // print_r2($_POST);exit;
    // 혹시 삭제되거나 사용되지 않는 shp_idx인지를 확인
	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');
    //수주변경을 했을시 다른 레코드 중에 동일한 prd_idx를 가지고 있는 경우도 있다.
    //그럴때는 해당 shp_idx의 복제와 같은 개념의 업데이트가 되는 개념이다.
    $chk_sql = " SELECT COUNT(*) AS cnt, shp_idx FROM {$g5['shipment_table']}
                WHERE shp_idx != '".${$pre."_idx"}."'
                    AND prd_idx = '".${$pre}['prd_idx']."'
                    AND shp_status NOT IN ('trash','delete')
    ";
    $chk_res = sql_fetch($chk_sql);
    if($chk_res['cnt']){
        $t_cnt = $chk_res['cnt'] + 1;
        $sql_common .= ",shp_sort = '{$t_cnt}'";
    }
    
    // echo $sql_common;exit;
    $sql = "UPDATE {$g5_table_name} SET 
                {$sql_common}
                , ".$pre."_update_dt = '".G5_TIME_YMDHIS."'
            WHERE ".$pre."_idx = '".${$pre."_idx"}."' 
	";
    //echo $sql.'<br>';
    sql_query($sql,1);
    
}
else if ($w == 'd') {

    $sql = "UPDATE {$g5_table_name} SET
                ".$pre."_status = 'trash'
            WHERE ".$pre."_idx = '".${$pre."_idx"}."'
    ";
    sql_query($sql,1);
    goto_url('./'.$fname.'_list.php?'.$qstr, false);
    
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


//-- 체크박스 값이 안 넘어오는 현상 때문에 추가, 폼의 체크박스는 모두 배열로 선언해 주세요.
$checkbox_array=array();
for ($i=0;$i<sizeof($checkbox_array);$i++) {
	if(!$_REQUEST[$checkbox_array[$i]])
		$_REQUEST[$checkbox_array[$i]] = 0;
}

//-- 메타 입력 (디비에 있는 설정된 값은 입력하지 않는다.) --//
// $fields[] = "bom_start_date";	// 건너뛸 변수명을 배열로 추가해 준다.
// foreach($_REQUEST as $key => $value ) {
// 	//-- 해당 테이블에 있는 필드 제외하고 테이블 prefix 로 시작하는 변수들만 업데이트 --//
// 	if(!in_array($key,$fields) && substr($key,0,3)==$pre) {
// 		//echo $key."=".$_REQUEST[$key]."<br>";
// 		meta_update(array("mta_db_table"=>$table_name,"mta_db_id"=>${$pre."_idx"},"mta_key"=>$key,"mta_value"=>$value));
// 	}
// }

// exit;
if($w=='c') {
    goto_url('./'.$fname.'_list.php?sfl=shp.prd_idx&ser_stx='.$prd_idx, false);
}
// goto_url('./'.$fname.'_list.php?'.$qstr.'&w=u&'.$pre.'_idx='.${$pre."_idx"}, false);
goto_url('./'.$fname.'_form.php?'.$qstr.'&w=u&'.$pre.'_idx='.${$pre."_idx"}, false);
?>