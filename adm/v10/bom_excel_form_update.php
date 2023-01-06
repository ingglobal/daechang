<?php
// header('Content-Encoding: none;');

$sub_menu = "940120";
include_once('./_common.php');

if(!$member['mb_manager_yn']) {
    alert('메뉴에 접근 권한이 없습니다.');
}
if(!$excel_type) {
    alert('엑셀 종류를 선택하세요.');
}

$demo = 1;  // 데모모드 = 1

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

// 초기값 정의 (외부 함수들에서 사용)
$g5['bit']['num'] = array();
$g5['bit']['reply'] = array();
$g5['bit_num'] = 0;

flush();
ob_flush();

$idx = 0;
// ==============================================================================
// 첫번째 시트
for($i=0;$i<=sizeof($allData[0]);$i++) {
    // print_r3($allData[0][$i]);
    if($demo) {
        if($i>170) {break;}
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
    $arr['cst_name_customer'] = $list[1]; // 고객사
    $arr['bct_name'] = $list[2];    // 차종
    $arr['level1'] = $list[3];
    $arr['level2'] = $list[4];
    $arr['level3'] = $list[5];
    $arr['level4'] = $list[6];
    $arr['level5'] = $list[7];
    $arr['level6'] = $list[8];
    $arr['level7'] = $list[9];
    $arr['level8'] = $list[10];
    $arr['level9'] = $list[11];
    $arr['level10'] = $list[12];
    $arr['level11'] = $list[13];
    $arr['level12'] = $list[14];
    $arr['bom_std'] = $list[15];        // 사양
    $arr['image'] = $list[16];
    $arr['bom_part_no'] = $list[17];    // 품범
    $arr['bom_name'] = $list[18];  // 품명
    $arr['bit_count'] = $list[19];      // 구성품수
    $arr['cst_name_provider'] = $list[20];  // 공급처
    $arr['remark'] = $list[21];
    // print_r3($arr);

    // 조건에 맞는 해당 라인만 추출
    if( preg_match("/[0-9]/",$arr['no'])
        && preg_match("/[-0-9A-Z]/",$arr['bom_part_no'])
        && $arr['bom_name']
        && preg_match("/[0-9]/",$arr['bit_count']) )
    {
        // print_r3($arr);
        // 완성품이 바뀌면 초기화
        if($arr['level1'] == 1) {
            // 초기값 정의 (외부 함수들에서 사용할 global 변수)
            $g5['bit']['num'] = array();
            $g5['bit']['reply'] = array();
            $g5['bit_num'] = 0;
        }

        // 대창공업 ITEM LIST_REV1(22.12.22)-개발이범희GJ_REV6.xlxs
        if($excel_type=='01') {

            // 납품처(고객사) 디비 생성
            $ar['table']  = 'g5_1_customer';
            $ar['cst_name']  = $arr['cst_name_customer'];
            $ar['cst_type']  = 'customer';
            $arr['cst_idx_'.$ar['cst_type']] = update_db($ar);
            unset($ar);

            // 카테고리 디비 생성
            $ar['table']  = 'g5_1_bom_category';
            $ar['com_idx']  = $_SESSION['ss_com_idx'];
            $ar['bct_name']  = $arr['bct_name'];
            $arr['bct_idx'] = update_db($ar);
            unset($ar);

            // 공급처(거래처) 디비 생성
            $ar['table']  = 'g5_1_customer';
            $ar['cst_name']  = $arr['cst_name_provider'];
            $ar['cst_type']  = 'provider';
            $arr['cst_idx_'.$ar['cst_type']] = update_db($ar);
            unset($ar);

            // bom 생성
            $ar['table']  = 'g5_1_bom';
            $ar['bom_part_no'] = $arr['bom_part_no'];
            $ar['bom_name'] = $arr['bom_name'];
            $arr['bom_idx'] = update_db($ar);
            unset($ar);

            $item['parent_id'] = $parent_id;
            $list = array();
            $list = $item;
            unset($list['children']);   // 서브까지 다 보이면 복잡해서 숨김
            $list['reply'] = get_num_reply($list['id'], $list['parent_id'], $list['depth']);
            $list['bit_num'] = $list['reply'][0];
            $list['bit_reply'] = $list['reply'][1];
            $list['bom_idx'] = $_POST['bom_idx'];   // 넘겨받은 bom_idx
            unset($list['reply']);
            $list['bit_idx'] = update_bom_item($list);
            $g5['bit_idxs'][] = $list['bit_idx'];   // 삭제를 위한 배열
            // print_r2($list);
            //print_r2($g5['bit']['num']);    // 공통 배열 변수
            //print_r2($g5['bit']['reply']);    // 공통 배열 변수
        }

        $idx++; 
    }
    else {continue;}


    // 메시지 보임
    if($arr['no']) {
        echo "<script> document.all.cont.innerHTML += '".$idx
                .". ".$arr['bct_name']." [".$arr['bom_part_no']."]: ".$arr['bom_name']
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
for($i=0;$i<=sizeof($allData[1]);$i++) {
    // print_r3($allData[1][$i]);
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
</script>