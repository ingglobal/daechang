<?php
$sub_menu = "940130";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'plc_protocol';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form_update/","",$g5['file_name']); // _form_update를 제외한 파일명

// If no alarm name, disconnect the existing cod_idx.
if(!$_REQUEST['cod_name']) {
    $_POST['cod_idx'] = 0;
}
// If no product name, disconnect the existing bom_idx.
if(!$_REQUEST['bom_name']) {
    $_POST['bom_idx'] = 0;
}

// 변수 재설정
for($i=0;$i<sizeof($fields);$i++) {
    // 공백 제거
    $_POST[$fields[$i]] = trim($_POST[$fields[$i]]);
    // 천단위 제거
    if(preg_match("/_price$/",$fields[$i]))
        $_POST[$fields[$i]] = preg_replace("/,/","",$_POST[$fields[$i]]);
}

// 추가변수
// $_POST['dta_end_dt'] = strtotime($_POST['dta_end_dt']);

// 공통쿼리
$skips[] = $pre.'_idx';
$skips[] = $pre.'_reg_dt';
$skips[] = $pre.'_update_dt';
for($i=0;$i<sizeof($fields);$i++) {
    if(in_array($fields[$i],$skips)) {continue;}
    $sql_commons[] = " ".$fields[$i]." = '".$_POST[$fields[$i]]."' ";
}
$sql_common = (is_array($sql_commons)) ? implode(",",$sql_commons) : '';


if ($w == '' || $w == 'c') {
    
    $sql = " INSERT into {$g5_table_name} SET 
                {$sql_common} 
                , ".$pre."_reg_dt = '".G5_TIME_YMDHIS."'
                , ".$pre."_update_dt = '".G5_TIME_YMDHIS."'
	";
    sql_query($sql,1);
	${$pre."_idx"} = sql_insert_id();
    
}
else if ($w == 'u') {

	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');
 
    $sql = "	UPDATE {$g5_table_name} SET 
					{$sql_common}
					, ".$pre."_update_dt = '".G5_TIME_YMDHIS."'
				WHERE ".$pre."_idx = '".${$pre."_idx"}."' 
	";
    // echo $sql.BR;
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

// //-- 메타 입력 (디비에 있는 설정된 값은 입력하지 않는다.) --//
// $fields[] = "mms_zip";	// 건너뛸 변수명은 배열로 추가해 준다.
// $fields[] = "mms_sido_cd";	// 건너뛸 변수명은 배열로 추가해 준다.
// foreach($_REQUEST as $key => $value ) {
// 	//-- 해당 테이블에 있는 필드 제외하고 테이블 prefix 로 시작하는 변수들만 업데이트 --//
// 	if(!in_array($key,$fields) && substr($key,0,3)==$pre) {
// 		//echo $key."=".$_REQUEST[$key]."<br>";
// 		meta_update(array("mta_db_table"=>$table_name,"mta_db_id"=>${$pre."_idx"},"mta_key"=>$key,"mta_value"=>$value));
// 	}
// }

// exit;

if ($w == 'u') {
    $qstr .= '&ser_mms_idx='.$ser_mms_idx.'&ser_ppr_ip='.$ser_ppr_ip; // 추가로 확장해서 넘겨야 할 변수들
}


$list = array();
// $list_idx2 = array();
$sql = "SELECT * FROM {$g5['plc_protocol_table']} ORDER BY ppr_ip, ppr_port_no, ppr_no, ppr_bit";
$result = sql_query($sql,1);
// echo $sql;
for($i=0; $row=sql_fetch_array($result); $i++) {
    $row['mms'] = get_table('mms','mms_idx',$row['mms_idx']);
    $row['bom'] = get_table('bom','bom_idx',$row['bom_idx']);
    $row['cod'] = get_table('code','cod_idx',$row['cod_idx']);
    // print_r2($row);
    $ar['ppr_idx'] = $row['ppr_idx'] ?: 0;
    $ar['ppr_idx_parent'] = $row['ppr_idx_parent'] ?: 0;
    $ar['ppr_name'] = addslashes($row['ppr_name']);
    $ar['ppr_data_type'] = $row['ppr_data_type'];
    $ar['ppr_jig_code'] = $row['ppr_jig_code'];
    $ar['ppr_decimal'] = $row['ppr_decimal'];
    $ar['ppr_set_time'] = $row['ppr_set_time'];
    $ar['mms_idx'] = $row['mms_idx'] ?: 0;
    $ar['cod_idx'] = $row['cod_idx'] ?: 0;
    $ar['cod_name'] = addslashes($row['cod']['cod_name']);
    $ar['mms_name'] = addslashes($row['mms']['mms_name']);
    $ar['bom_idx'] = $row['bom_idx'] ?: 0;
    $ar['bom_part_no'] = addslashes($row['bom']['bom_part_no']);
    $ar['bom_name'] = addslashes($row['bom']['bom_name']);
    $list[$row['ppr_ip']][$row['ppr_port_no']][$row['ppr_no']][$row['ppr_bit']] = $ar;
    if($ar['ppr_data_type']=='alarm') {
        $list_alarm[$row['cod_idx']] = array('cod_name'=>$ar['cod_name'],'mms_idx'=>$ar['mms_idx'],'mms_name'=>$ar['mms_name']);
    }
    else {
        $list_ppr[$row['ppr_idx']] = array('ppr_name'=>$ar['ppr_name'],'mms_idx'=>$ar['mms_idx'],'mms_name'=>$ar['mms_name']);
    }
    unset($ar);
}
// print_r2($list);
// print_r2($list_idx2);

// 캐시파일 업데이트
$cache_file = G5_DATA_PATH.'/cache/socket-setting.php';
@unlink($cache_file);
$handle = fopen($cache_file, 'w');
$cache_content = "<?php\n";
$cache_content .= "if (!defined('_GNUBOARD_')) exit;\n";
$cache_content .= "\$g5['socket']=".var_export($list, true).";\n";
$cache_content .= "\$g5['ppr']=".var_export($list_ppr, true).";\n";
$cache_content .= "\$g5['cod']=".var_export($list_alarm, true).";\n";
$cache_content .= "?>";
fwrite($handle, $cache_content);
fclose($handle);
  


// python용 변수 생성
$cache_file = G5_DATA_PATH.'/python/data_socket.py';
@unlink($cache_file);
// 캐시파일 생성
$handle = fopen($cache_file, 'w');
// PHP 배열을 JSON 형식으로 인코딩
$cache_content = "data_socket=".json_encode($list, JSON_PRETTY_PRINT)."\n";
fwrite($handle, $cache_content);
fclose($handle);



// goto_url('./'.$fname.'_list.php?'.$qstr, false);
goto_url('./'.$fname.'_form.php?'.$qstr.'&w=u&'.$pre.'_idx='.${$pre."_idx"}, false);
?>