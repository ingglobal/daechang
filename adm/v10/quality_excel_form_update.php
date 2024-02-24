<?php
$sub_menu = "922145";
include_once('./_common.php');

// if(!$member['mb_manager_yn']) {
//     alert('메뉴에 접근 권한이 없습니다.');
// }
if(!$excel_type) {
    alert('엑셀 종류를 선택하세요.');
}

// print_r2($_REQUEST);
// exit;

$demo = 0;  // 데모모드 = 1

// 년월 정보 추출 
$year = $_REQUEST['ym'] ? substr($_REQUEST['ym'],0,4) : substr(G5_TIME_YMD,0,4);
$month = substr($_REQUEST['ym'],-2);
// echo $year.'/'.$month.BR;
// exit;


require_once G5_LIB_PATH.'/PhpSpreadsheet19/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$upload_file_name = $_FILES['file_excel']['name'];
$file_type= pathinfo($upload_file_name, PATHINFO_EXTENSION);
if ($file_type =='xls') {
	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();	
}
elseif ($file_type =='xlsx') {
	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
}
else {
	echo '처리할 수 있는 엑셀 파일이 아닙니다';
	exit;
}

$upload_file=$_FILES['file_excel']['tmp_name'];
// $reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load($upload_file);	
$sheetCount = $spreadsheet->getSheetCount();
for ($i = 0; $i < $sheetCount; $i++) {
    $sheet = $spreadsheet->getSheet($i);
    $sheetData = $sheet->toArray(null, true, true, true);
    // echo $i.' ------------- <br>';
    // print_r2($sheetData);
    $allData[$i] = $sheetData;
}
// print_r3($allData[0]);
// print_r3(sizeof($allData));
// exit;


// 엑셀 파일 저장 (최근 10개만 남겨놓기)
$extension = pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION);
$destfile = date("YmdHis").'.'.$extension;
// $destfile = '2024-02-09.xlsx';
$dir = '/data/excels/quality';
if(is_file(G5_PATH.$dir.'/'.$destfile)) {
    @unlink(G5_PATH.$dir.'/'.$destfile);
}
upload_common_file($upload_file, $destfile, $dir);
// exit;



$g5['title'] = '엑셀 업로드';
//include_once('./_top_menu_applicant.php');
include_once('./_head.php');
//echo $g5['container_sub_title'];
?>
<div class="" style="padding:10px;">
	<span>
		작업 시작~~ <font color=crimson><b>[끝]</b></font> 이라는 단어가 나오기 전 중간에 중지하지 마세요.
	</span><br><br>
	<span id="cont"></span>
	<div id="defect"></div>
</div>
<?php
include_once ('./_tail.php');
?>

<?php
$countgap = 10; // 몇건씩 보낼지 설정
$sleepsec = 1000;  // 백만분의 몇초간 쉴지 설정, default=200
$maxscreen = 100; // 몇건씩 화면에 보여줄건지?


flush();
ob_flush();

$idx = 0;
// ==============================================================================
// 첫번째 시트 그럼 이제
for($i=0;$i<=sizeof($allData[0]);$i++) {
    // print_r3($allData[0][$i]);
    if($demo) {
        if($i>51) {break;} // 51
    }

    // 초기화
    unset($arr);
    unset($list);
    // 한 라인씩 $list 숫자 배열로 변경!!
    if(is_array($allData[0][$i])) {
        foreach($allData[0][$i] as $k1=>$v1) {
            // print_r3($k1.'='.$v1);
            $list[] = trim($v1);
        }
    }
    // print_r3($list);
    $arr['no'] = intval($list[0]);  // 순번
    $arr['month'] = $list[1] ? sprintf("%02d",intval($list[1])) : $month; // 월
    $arr['day'] = intval($list[2]);   // 일
    $arr['type'] = trim($list[3]);  // 구분
    $arr['cat'] = trim(addslashes($list[4]));  // 차종
    $arr['bom_part_no'] = trim(addslashes($list[5]));  // 품번
    $arr['bom_name'] = trim(addslashes($list[6]));  // 품명
    $arr['defect_text'] = addslashes($list[11]); // 문제점 및 원인
    $arr['count'] = intval($list[12]); // 발생수량
    $arr['defect_type'] = trim(addslashes($list[13])); // 불량유형
    $arr['result'] = trim(addslashes($list[14])); // 처리방안 및 대책
    // print_r3($arr);

    // 조건에 맞는 해당 라인만 추출
    if( preg_match("/[-0-9A-Z]/",$arr['bom_part_no'])
        && $arr['bom_name'] && $arr['count']
        && is_numeric($arr['count']) )
    {
        // print_r3($arr);
        
        // 해당 날짜 기존 불량 디비가 있으면 먼저 초기화 (both of item & material)
        $itm_date[$i] = $year.'-'.$arr['month'].'-'.$arr['day'];
        // Reset for items ..............
        $sql = " SELECT itm_idx FROM {$g5['item_table']} WHERE itm_part_no = '".$arr['bom_part_no']."' AND itm_date = '".$itm_date[$i]."' AND itm_status = 'defect' ";
        $rs = sql_query($sql,1);
        $itm_count[$i] = sql_num_rows($rs);
        // print_r3($sql);
        // print_r3($itm_count[$i]);
        // 불량 데이터 초기화 (defect -> finish)
        if($itm_count[$i]) {
            $sql = "UPDATE {$g5['item_table']} SET itm_status = 'finish'
                    WHERE itm_part_no = '".$arr['bom_part_no']."' AND itm_date = '".$itm_date[$i]."' AND itm_status = 'defect'
            ";
            // print_r3($sql);
            sql_query($sql,1);
        }
        // Reset for materials .................
        $sql = " SELECT mtr_idx FROM {$g5['material_table']} WHERE mtr_part_no = '".$arr['bom_part_no']."' AND mtr_date = '".$itm_date[$i]."' AND mtr_status = 'defect' ";
        $rs = sql_query($sql,1);
        $material_count[$i] = sql_num_rows($rs);
        // print_r3($sql);
        // print_r3($itm_count[$i]);
        // 불량 데이터 초기화 (defect -> finish)
        if($material_count[$i]) {
            $sql = "UPDATE {$g5['material_table']} SET mtr_status = 'finish'
                    WHERE mtr_part_no = '".$arr['bom_part_no']."' AND mtr_date = '".$itm_date[$i]."' AND mtr_status = 'defect'
            ";
            // print_r3($sql);
            sql_query($sql,1);
        }
        
        // print_r3($g5['set_defect_type_value']);
        // print_r3($arr['defect_type']);
        // print_r3($g5['set_mtr_defect_type_value']);
        // item insert. 불량 타입이 있을 때만 입력합니다.
        if(in_array($arr['defect_type'],$g5['set_defect_type_value'])) {
            // $key[$i] = array_search($arr['defect_type'], $g5['set_defect_type_value']);
            foreach ($g5['set_defect_type_value'] as $key => $value) {
                if ($value == $arr['defect_type']) {
                    $type[$i] = $key;
                    break;
                }
            }
            // 해당 날짜 생산중에서 맨 앞에서부터 불량 갯수만큼 불량처리
            $sql = "UPDATE {$g5['item_table']} SET 
                        itm_status = 'defect',
                        itm_defect_type = '".$type[$i]."',
                        itm_defect_text = '".$arr['defect_text']."',
                        itm_memo = '".$arr['result']."'
                    WHERE itm_part_no = '".$arr['bom_part_no']."' AND itm_date = '".$itm_date[$i]."' AND itm_status = 'finish'
                    ORDER BY itm_idx LIMIT ".$arr['count']."
            ";
            // print_r3($sql);
            sql_query($sql,1);
        }
        else {
            $defect_types[] = $arr['defect_type'];
        }
        // material. 불량 타입이 있을 때만 입력합니다.
        if(in_array($arr['defect_type'],$g5['set_mtr_defect_type_value'])) {
            // $key[$i] = array_search($arr['defect_type'], $g5['set_mtr_defect_type_value']);
            foreach ($g5['set_mtr_defect_type_value'] as $key => $value) {
                if ($value == $arr['defect_type']) {
                    $type[$i] = $key;
                    break;
                }
            }
            // 해당 날짜 생산중에서 맨 앞에서부터 불량 갯수만큼 불량처리
            $sql = "UPDATE {$g5['material_table']} SET 
                        mtr_status = 'defect',
                        mtr_defect_type = '".$type[$i]."',
                        mtr_defect_text = '".$arr['defect_text']."',
                        mtr_memo = '".$arr['result']."'
                    WHERE mtr_part_no = '".$arr['bom_part_no']."' AND mtr_date = '".$mtr_date[$i]."' AND mtr_status = 'finish'
                    ORDER BY mtr_idx LIMIT ".$arr['count']."
            ";
            // print_r3($sql);
            sql_query($sql,1);
        }
        else {
            $defect_types[] = $arr['defect_type'];
        }

        $idx++; 
    }
    else {continue;}


    // 메시지 보임
    if(preg_match("/[-0-9A-Z]/",$arr['bom_part_no'])) {
        echo "<script> document.all.cont.innerHTML += '".$idx
                .". ".$arr['month']."/".$arr['day'].": ".$arr['bom_part_no']."]: ".$arr['bom_name']." -> ".$arr['count']." 개 불량"
                ." ----------->> 처리 완료<br>'; </script>\n";
    }

    flush();
    ob_flush();
    ob_end_flush();
    usleep($sleepsec);
    
    // 보기 쉽게 묶음 단위로 구분 (단락으로 구분해서 보임)
    if ($i % $countgap == 0)
        echo "<script> document.all.cont.innerHTML += '<br>'; </script>\n";
    
    // 화면 정리! 부하를 줄임 (화면 싹 지움)
    if ($i % $maxscreen == 0)
        echo "<script> document.all.cont.innerHTML = ''; </script>\n";

}
// ==============================================================================
// 두번째 시트
// for($i=0;$i<=sizeof($allData[1]);$i++) {
//     print_r3($allData[1][$i]);
// }

// 입력 불가 불량 타입 표현
if($defect_types[0]) {
    $defect_types = array_unique($defect_types);
    // print_r3($defect_types);
    // print_r3('새로운 불량 유형이 있어서 등록하지 못했습니다.<span style="color:darkorange;">'.BR.implode(",",$defect_types).'</span>'.BR.'불량유형이 환경설정에 먼저 등록되어야 합니다.'.BR.'관리자에게 문의해 주세요.');
    $defect_msg = '<div style="margin-top:20px;border:1px solid #ddd;padding:15px;">새로운 불량 유형이 있어서 일부 정보를 등록하지 못했습니다.'.BR.'불량유형: <span style="color:darkorange;">'.implode(",",$defect_types).'</span>'.BR.'환경설정에 먼저 등록되어야 하므로 관리자에게 문의해 주세요.</div>';
}




// 관리자 디버깅 메시지
if( is_array($g5['debug_msg']) ) {
    for($i=0;$i<sizeof($g5['debug_msg']);$i++) {
        echo '<div class="debug_msg">'.$g5['debug_msg'][$i].'</div>';
    }
?>
    <script>
    $(function(){
        $("#container").prepend( $('.debug_msg') );
    });
    </script>
<?php
}
?>


<script>
	document.all.cont.innerHTML += "<br><br>총 <?php echo number_format($idx) ?>건 완료<br><br><font color=crimson><b>[끝]</b></font>";
    <?php if($defect_types[0]) { ?>
        document.all.defect.innerHTML = '<?=$defect_msg?>';
    <?php } ?>
</script>