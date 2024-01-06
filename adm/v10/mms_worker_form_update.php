<?php
$sub_menu = "940130";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'bom_mms_worker';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form_update/","",$g5['file_name']); // _form_update를 제외한 파일명

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
$skips = array($pre.'_idx',$pre.'_reg_dt',$pre.'_update_dt', $pre.'_main_yn');
for($i=0;$i<sizeof($fields);$i++) {
    if(in_array($fields[$i],$skips)) {continue;}
    $sql_commons[] = " ".$fields[$i]." = '".$_POST[$fields[$i]]."' ";
}
$sql_common = (is_array($sql_commons)) ? implode(",",$sql_commons) : '';


if ($w == '' || $w == 'c') {
    $chk_sql = " SELECT bmw_idx
                        , MAX(bmw_sort) OVER (PARTITION BY bom_idx, mms_idx) AS bmw_sort 
                    FROM {$g5['bom_mms_worker_table']}
                    WHERE bom_idx = '{$bom_idx}'
                        AND mms_idx = '{$mms_idx}'
                        AND mb_id = '{$mb_id}'
                        AND bmw_type = '{$bmw_type}'
                        AND bmw_status NOT IN ('trash','delete')
                    ORDER BY bmw_reg_dt DESC LIMIT 1
    ";
    // echo $chk_sql."<br>";
    $chk_res = sql_fetch($chk_sql);
    if($chk_res['bmw_idx'])
        alert('동일한 조건의 작업자가 이미 존재합니다.');
    
    // 해당 bom_idx와 mms_idx조건의 작업자들의 정렬순서중에 가장 큰값을 가져온다.
    // $bmw_sort_res = sql_fetch(" SELECT MAX(bmw_sort) AS bmw_sort FROM {$g5['bom_mms_worker_table']}
    //         WHERE bom_idx = '{$bom_idx}'
    //             AND mms_idx = '{$mms_idx}'
    //             AND bmw_status NOT IN ('trash','delete')
    // ");
    // $bmw_sort = ($bmw_sort_res['bmw_sort']) ? (int)$bmw_sort_res['bmw_sort'] + 1 : 0;

    
    //혹시라도 같은 제품의 같은 설비에서 day또는 night가 존재하면 등록할 수 없다.
    //반은 같은 제품과 같은 설비조건에서 day 1명, night1명 존재해야 하고 나머지는 전부 sub이다.
    if($bmw_type == 'day' || $bmw_type == 'night'){
        $chk_sql = " SELECT COUNT(*) AS cnt
                        FROM {$g5['bom_mms_worker_table']}
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx = '{$mms_idx}'
                            AND bmw_type = '{$bmw_type}'
                            AND bmw_status NOT IN ('trash','delete')
        ";
        // echo $chk_sql."<br>";
        $chk_res = sql_fetch($chk_sql);
        if($chk_res['cnt']){
            alert('작업자타입:"'.$g5['set_bmw_type_value'][$bmw_type].'"이 이미 존재합니다."서브"로 등록하셔야 합니다.');
        }
    }

    // 자신이외에 bmw_main_yn = 1 로 되어 있는게 없으면 (day,night)일 경우 반드시 bmw_main_yn = 1로 되어야 한다.
    if((!$bmw_main_yn && $bmw_type == 'day') || (!$bmw_main_yn && $bmw_type == 'night')){
        $mchk_res = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$g5_table_name}
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx != '{$mms_idx}'
                            AND bmw_main_yn = 1
                            AND bmw_status = 'ok'
        ");
        if(!$mchk_res['cnt']){
            $bmw_main_yn = 1;
        }
    }
    // 기존 해당 bom_idx를 작업하는 설비의 bmw_main_yn설정을 전부 0으로 셋팅한다.
    else if(($bmw_main_yn && $bmw_type == 'day') || ($bmw_main_yn && $bmw_type == 'night')){
        $m_sql = " UPDATE {$g5_table_name} SET bmw_main_yn = 0
                        WHERE bom_idx = '{$bom_idx}'
        ";
        sql_query($m_sql,1);
    }
    else {
        $bmw_main_yn = 0; //day , night가 아니면 무조건 0으로 설정되어야 한다.
    }

    $sql = " INSERT into {$g5_table_name} SET 
                {$sql_common} 
                , bmw_main_yn = '{$bwm_main_yn}'
                , bmw_reg_dt = '".G5_TIME_YMDHIS."'
                , bmw_update_dt = '".G5_TIME_YMDHIS."'
	";
    sql_query($sql,1);
	$bmw_idx = sql_insert_id();

    // 만약 day가 bmw_main_yn = 0로 되었다면 night도 0로 설정해야 하고 그 반대의 경우도 마찬가지아다.
    if((!$bmw_main_yn && $bmw_type == 'day') || (!$bmw_main_yn && $bmw_type == 'night')){
        $m_sql = " UPDATE {$g5_table_name} SET bmw_main_yn = 0
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx = '{$mms_idx}'
                            AND bmw_type IN ('day','night')
        ";
        sql_query($m_sql,1);
    }
    // 만약 day가 bmw_main_yn = 1로 되었다면 night도 1로 설정해야 하고 그 반대의 경우도 마찬가지아다.
    else if(($bmw_main_yn && $bmw_type == 'day') || ($bmw_main_yn && $bmw_type == 'night')){
        $m_sql = " UPDATE {$g5_table_name} SET bmw_main_yn = 1
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx = '{$mms_idx}'
                            AND bmw_type IN ('day','night')
        ";
        sql_query($m_sql,1);
    }
}
else if ($w == 'u') {
	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');

    $chk_sql = " SELECT bmw_idx
            , MAX(bmw_sort) OVER (PARTITION BY bom_idx, mms_idx) AS bmw_sort 
        FROM {$g5['bom_mms_worker_table']}
        WHERE bom_idx = '{$bom_idx}'
            AND mms_idx = '{$mms_idx}'
            AND mb_id = '{$mb_id}'
            AND bmw_type = '{$bmw_type}'
            AND bmw_status NOT IN ('trash','delete')
            AND bmw_idx != '".${$pre."_idx"}."'
        ORDER BY bmw_reg_dt DESC LIMIT 1
    ";
    // echo $chk_sql."<br>";
    $chk_res = sql_fetch($chk_sql);
    if($chk_res['bmw_idx'])
        alert('동일한 조건의 데이터가 이미 존재합니다.');

    // 자신이외에 bmw_main_yn = 1 로 되어 있는게 없으면 (day,night)일 경우 반드시 bmw_main_yn = 1로 되어야 한다.
    if((!$bmw_main_yn && $bmw_type == 'day') || (!$bmw_main_yn && $bmw_type == 'night')){
        $mchk_res = sql_fetch(" SELECT COUNT(*) AS cnt FROM {$g5_table_name}
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx != '{$mms_idx}'
                            AND bmw_idx != '{$bmw_idx}'
                            AND bmw_main_yn = 1
                            AND bmw_status = 'ok'
        ");
        if(!$mchk_res['cnt']){
            $bmw_main_yn = 1;
        }
    }
    else if(($bmw_main_yn && $bmw_type == 'day') || ($bmw_main_yn && $bmw_type == 'night')){
            $m_sql = " UPDATE {$g5_table_name} SET bmw_main_yn = 0
                    WHERE bom_idx = '{$bom_idx}'
            ";
            sql_query($m_sql,1);
    }
    else {
        $bmw_main_yn = 0; //day , night가 아니면 무조건 0으로 설정되어야 한다.
    }

    $sql = "	UPDATE {$g5_table_name} SET 
					{$sql_common}
                    , bmw_main_yn = '{$bmw_main_yn}'
					, bmw_update_dt = '".G5_TIME_YMDHIS."'
				WHERE bmw_idx = '{$bmw_idx}' 
	";
    //echo $sql.'<br>';
    sql_query($sql,1);

    // 만약 day가 bmw_main_yn = 0로 되었다면 night도 0로 설정해야 하고 그 반대의 경우도 마찬가지아다.
    if((!$bmw_main_yn && $bmw_type == 'day') || (!$bmw_main_yn && $bmw_type == 'night')){
        $m_sql = " UPDATE {$g5_table_name} SET bmw_main_yn = 0
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx = '{$mms_idx}'
                            AND bmw_type IN ('day','night')
        ";
        sql_query($m_sql,1);
    }
    // 만약 day가 bmw_main_yn = 1로 되었다면 night도 1로 설정해야 하고 그 반대의 경우도 마찬가지아다.
    else if(($bmw_main_yn && $bmw_type == 'day') || ($bmw_main_yn && $bmw_type == 'night')){
        $m_sql = " UPDATE {$g5_table_name} SET bmw_main_yn = 1
                        WHERE bom_idx = '{$bom_idx}'
                            AND mms_idx = '{$mms_idx}'
                            AND bmw_type IN ('day','night')
        ";
        sql_query($m_sql,1);
    }
        
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
$fields[] = "mms_zip";	// 건너뛸 변수명은 배열로 추가해 준다.
$fields[] = "mms_sido_cd";	// 건너뛸 변수명은 배열로 추가해 준다.
foreach($_REQUEST as $key => $value ) {
	//-- 해당 테이블에 있는 필드 제외하고 테이블 prefix 로 시작하는 변수들만 업데이트 --//
	if(!in_array($key,$fields) && substr($key,0,3)==$pre) {
		//echo $key."=".$_REQUEST[$key]."<br>";
		meta_update(array("mta_db_table"=>$table_name,"mta_db_id"=>${$pre."_idx"},"mta_key"=>$key,"mta_value"=>$value));
	}
}

// exit;

if ($w == 'u') {
    $qstr .= '&ser_mms_idx='.$ser_mms_idx; // 추가로 확장해서 넘겨야 할 변수들
}

// goto_url('./'.$fname.'_list.php?'.$qstr, false);
goto_url('./'.$fname.'_form.php?'.$qstr.'&w=u&'.$pre.'_idx='.${$pre."_idx"}, false);
?>