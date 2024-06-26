<?php
$sub_menu = "940110";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$demo = 0;  // 데모모드 = 1

// print_r2($_REQUEST);
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
</div>
<?php
include_once ('./_tail.php');
?>

<?php
$countgap = 10; // 몇건씩 보낼지 설정
$sleepsec = 10000;  // 백만분의 몇초간 쉴지 설정, default=200
$maxscreen = 30; // 몇건씩 화면에 보여줄건지?

flush();
ob_flush();

$idx = 0;
// ==============================================================================
// 첫번째 시트
for($i=0;$i<=sizeof($allData[0]);$i++) {
    // print_r3($allData[0][$i]);
    if($demo) {
        if($i>12) {break;} // 170
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
    $arr['no'] = $list[0];
    $arr['facility'] = addslashes($list[1]); // 설비명
    $arr['mb_name'] = addslashes($list[2]); // 이름
    $arr['mb_hp'] = $list[3];
    $arr['bct_name'] = $list[4];    // 차종
    // print_r3($arr);

    // 조건에 맞는 해당 라인만 추출
    if( preg_match("/[0-9]/",$arr['no'])
        && $arr['mb_name'] )
    {
        // 설비명 공백 제거
        $arr['facility'] = preg_replace("/\s+/", "", $arr['facility']); 
        // 폰번호가 없는 경우

        // print_r3($arr);
        if(!preg_match("/^01[0-9]{8,9}$/", preg_replace("/[^0-9]/","",$arr['mb_hp']))) {
            $arr['mb_hp'] = make_cell_number($arr['mb_name'],'111');
            print_r3($arr['mb_hp']);
        }


        // make facility db.
        $ar['table'] = 'g5_1_mms';
        $ar['com_idx'] = $_SESSION['ss_com_idx'];
        $ar['imp_idx'] = 27;    // 대표 imp 한개에 일단 전부 연결
        $ar['imp_name'] = '대표IMP';
        $ar['mmg_idx'] = 29;
        $ar['mms_name'] = $arr['facility'];
        $ar['mms_model'] = $arr['facility'];
        $ar['mms_set_output'] = 'shift';
        $ar['mms_data_url_host'] = 'daechang.epcs.co.kr';
        $ar['mms_output_yn'] = 'Y';
        $ar['mms_sort'] = 1;
        $ar['mms_status'] = 'ok';
        $arr['mms_idx'] = update_db($ar);
        // print_r3($arr['mms_idx'].'---');
        unset($ar);

        // make worker db.
        $ar['mb_hp'] = trim($arr['mb_hp']);
        $ar['mb_id'] = preg_replace("/-/","",$arr['mb_hp']);
        $ar['mb_name'] = trim($arr['mb_name']);
        // print_r3($ar['mb_id'].'/'.$ar['mb_hp']);
        $ar['mb_level'] = 4;
        $ar['mb_4'] = 13;
        // print_r3($ar);
        $arr['mb_id'] = make_member($ar);
        // print_r3($arr['mb_id']);
        unset($ar);

        // worker-facility connection for primary worker for the very facility.
        $ar['mb_id'] = $arr['mb_id'];
        $ar['mms_idx'] = $arr['mms_idx'];
        $ar['mmw_type'] = 'primary';
        $ar['mmw_status'] = 'ok';
        // print_r3($ar);
        update_mms_worker($ar);
        unset($ar);

        $idx++; 
    }
    else {continue;}


    // 메시지 보임
    if(preg_match("/-[0-9]/",$arr['mb_hp'])) {
        echo "<script> document.all.cont.innerHTML += '".$idx
                .". ".$arr['facility']." [".$arr['mb_hp']."]: ".$arr['bct_name']
                ." ----------->> 완료<br>'; </script>\n";
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
</script>