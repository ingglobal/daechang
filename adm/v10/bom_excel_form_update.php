<?php
// header('Content-Encoding: none;');

$sub_menu = "940120";
include_once('./_common.php');

if(auth_check($auth[$sub_menu],"r,w",1)) {
    alert('메뉴에 접근 권한이 없습니다.');
}

if(!$excel_type) {
    alert('엑셀 종류를 선택하세요.');
}

$demo = 0;  // 데모모드 = 1

// print_r2($_REQUEST);
// exit;

// 카테고리 구조 변수.. 2자리씩 묶어서 계층구조 만들 예정
$cats = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
$cat_arr = array();
for($i=0;$i<count($cats);$i++){
    if($i == 0) continue;
    for($j=0;$j<count($cats);$j++){
        //echo $cats[$i].$cats[$j]."<br>";
        array_push($cat_arr,$cats[$i].$cats[$j]);
    }
}
// print_r2($cat_arr);
// echo array_search('za',$cat_arr);
$bom_type_arr = array("E"=>"product","I"=>"half","D"=>"material","A"=>"material","G"=>"goods");


// bom db update for excel upload.
function update_bom2($arr)
{
	global $g5;
    // print_r3($arr);
	
	if(!$arr['bom_part_no']||!$arr['bom_name']) {
		return false;
    }
    
    $arr['com_idx'] = $arr['com_idx'] ?: $_SESSION['ss_com_idx'];

    $g5_table_name = $g5['bom_table'];
    $fields = sql_field_names($g5_table_name);
    $pre = substr($fields[0],0,strpos($fields[0],'_'));
    // print_r3($fields);
    
    // 변수 재설정
    $arr[$pre.'_update_dt'] = G5_TIME_YMDHIS;

    // 건너뛸 변수 배열
    $skips[] = $pre.'_idx';
    // 공통쿼리 (only array variables that passed through)
    for($i=0;$i<sizeof($fields);$i++) {
        if(in_array($fields[$i],$skips)) {continue;}
        if($arr[$fields[$i]]) {
            // print_r3($fields[$i].'/'.$arr[$fields[$i]]);
            $sql_commons[] = " ".$fields[$i]." = '".$arr[$fields[$i]]."' ";
        }
    }

    // 공통쿼리 생성
    $sql_common = (is_array($sql_commons)) ? implode(",",$sql_commons) : '';
    
    // 중복 조건
    $sql = "SELECT * FROM {$g5_table_name} 
            WHERE bom_part_no = '".$arr['bom_part_no']."'
    ";
    // echo $sql.'<br>';
    $row = sql_fetch($sql,1);
	if($row[$pre."_idx"]) {
		$sql = "UPDATE {$g5_table_name} SET 
                    {$sql_common} 
				WHERE ".$pre."_idx = '".$row[$pre."_idx"]."'
        ";
		sql_query($sql,1);
	}
	else {
		$sql = "INSERT INTO {$g5_table_name} SET 
                    {$sql_common} 
                    , ".$pre."_reg_dt = '".G5_TIME_YMDHIS."'
        ";
		sql_query($sql,1);
        $row[$pre."_idx"] = sql_insert_id();
	}
    // echo print_r3($sql);

    return $row[$pre."_idx"];
}


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
$dir = '/data/excels/bom';
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
// 첫번째 시트
for($i=0;$i<=sizeof($allData[0]);$i++) {
    // print_r3($allData[0][$i]);
    if($demo) {
        if($i>69) {break;} // 51
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
    $arr['bct_name'] = str_replace("\n"," ",addslashes($list[1])); // 차종
    $arr['bct_idx'] = $list[2]; // 차종ID
    $arr['end_product'] = $list[3]; 
    $arr['lv1'] = $list[4];
    $arr['lv2'] = $list[5];
    $arr['lv3'] = $list[6];
    $arr['lv4'] = $list[7];
    $arr['lv5'] = $list[8];
    $arr['lv6'] = $list[9];
    $arr['lv7'] = $list[10];
    $arr['lv8'] = $list[11];
    $arr['lv9'] = $list[12];
    $arr['lv10'] = $list[13];
    $arr['bom_code'] = $list[14];               // CODE =>      E : MIP END, I : MIP 공정품, D : 협력사 입고품, A : 협력사 SUB품, G : 상품
    $arr['bom_part_no'] = $list[15];            // 품번
    $arr['bom_name'] = addslashes($list[16]);   // 품명
    $arr['q1'] = $list[17];
    $arr['q2'] = $list[18];
    $arr['q3'] = $list[19];
    $arr['q4'] = $list[20];
    $arr['q5'] = $list[21];
    $arr['q6'] = $list[22];
    $arr['q7'] = $list[23];
    $arr['q8'] = $list[24];
    $arr['q9'] = $list[25];
    $arr['q10'] = $list[26];
    $arr['q11'] = $list[27];
    $arr['q12'] = $list[28];
    $arr['q13'] = $list[29];
    $arr['q14'] = $list[30];
    $arr['q15'] = $list[31];
    $arr['q16'] = $list[32];
    $arr['q17'] = $list[33];
    $arr['q18'] = $list[34];
    $arr['q19'] = $list[35];
    $arr['q20'] = $list[36];
    $arr['q21'] = $list[37];
    $arr['q22'] = $list[38];
    $arr['q23'] = $list[39];
    $arr['q24'] = $list[40];
    $arr['q25'] = $list[41];
    $arr['q26'] = $list[42];
    $arr['q27'] = $list[43];
    $arr['q28'] = $list[44];
    $arr['q29'] = $list[45];
    $arr['q30'] = $list[46];
    $arr['q31'] = $list[47];
    $arr['q32'] = $list[48];
    $arr['q33'] = $list[49];
    $arr['q34'] = $list[50];
    $arr['q35'] = $list[51];
    $arr['q36'] = $list[52];
    $arr['cst_name_provider'] = addslashes($list[53]);  // 제조업체
    $arr['cst_idx_provider'] = $list[54];  // 제조사id
    $arr['cst_name_customer1'] = addslashes($list[55]);  // 고객사
    $arr['cst_idx_customer1'] = $list[56];  // 고객사id
    $arr['cst_name_customer2'] = addslashes($list[57]);  // 고객사
    $arr['cst_idx_customer2'] = $list[58];  // 고객사id
    // print_r3($arr);

    // 조건에 맞는 해당 라인만 추출
    if( preg_match("/^[-0-9A-Z]+$/",$arr['bom_part_no'])
        && $arr['bom_name']
        && $arr['bct_idx'] )
    {
        // print_r3($arr);

        // // if(!preg_match('/89311-S8510/',$arr['bom_part_no'])) {continue;}
        // if($arr['bom_part_no']!='89311-S8510') {continue;}

        // 레벨 추출
        for($j=1;$j<=10;$j++) {
            // print_r3($j);
            if($arr['lv'.$j]) {
                $arr['level'] = $j;
                break;
            }
        }
        // print_r3($arr);
        // Quantity 추출
        for($j=1;$j<=36;$j++) {
            if($arr['q'.$j]) {
                $arr['quantity'] = $j;
                if($arr['end_product'] == 'E') {break;} // End item should stop after finding due array no.
            }
        }
        
        // $arr['bom_type'] = ($arr['end_product'] == 'E') ? 'product' : 'material';
        $arr['bom_code'] = $arr['bom_code'] ?: $arr['end_product'];
        $arr['bom_type'] = ($arr['bom_code']) ? $bom_type_arr[$arr['bom_code']] : 'material';

        // bom 생성
        $ar['table']  = 'g5_1_bom';
        $ar['com_idx']  = $_SESSION['ss_com_idx'];
        $ar['bom_part_no'] = $arr['bom_part_no'];
        $ar['bom_name'] = $arr['bom_name'];
        $ar['bct_idx'] = $arr['bct_idx'];
        $ar['bom_type'] = $arr['bom_type'];
        $ar['bom_code'] = $arr['bom_code'];
        $ar['bom_status'] = 'ok';
        $arr['bom_idx'] = update_bom2($ar);
        unset($ar);
        // 최상위 부모 코드는 배열번호 할당 - 나중에 자식제품들이 나올 때 매칭해야 함
        if($arr['end_product'] == 'E') {
            $parent[$arr['quantity']] = $arr['bom_idx'];
            // print_r3($arr['quantity'].'-'.$parent[$arr['quantity']]);
        }
        // for child materials.
        else {
            // 자식(material)이었다가 부모(End product)를 만나면 $level_qty 초기화 해서 중복을 방지해야 함
            // if($old_bom_code=='' AND $arr['bom_code']=='E') {
            //     $parent = array();
            //     $level_qty = array();
            // }
            // Quantity 추출
            for($j=1;$j<=36;$j++) {
                if($arr['q'.$j]) {
                    // print_r3('parent bom_idx = '.$parent[$j]); // parent item bom_idx
                    // print_r3($j.'번째 - level='.$arr['level'].', qty='.$arr['q'.$j]);  // parent_array_no - item count
                    // $arr['quantity'][] = $j;
                    // make variables for hierachical structure.
                    $level_qty[$parent[$j]][] = array('part_no'=>$arr['bom_part_no'],'bom_idx'=>$arr['bom_idx'],'level'=>$arr['level'],'qty'=>$arr['q'.$j]);
                    // print_r3($level_qty);
                }
            }
            // print_r3('--------------');
        }
        
        // update bom_customer table
        if($arr['cst_idx_provider']) {
            $ar['bom_idx'] = $arr['bom_idx'];
            $ar['cst_idx'] = $arr['cst_idx_provider'];
            $ar['boc_type'] = 'provider';
            update_bom_customer($ar);
            unset($ar);
        }
        if($arr['cst_idx_customer1']) {
            $ar['bom_idx'] = $arr['bom_idx'];
            $ar['cst_idx'] = $arr['cst_idx_customer1'];
            $ar['boc_type'] = 'customer';
            update_bom_customer($ar);
            unset($ar);
        }
        if($arr['cst_idx_customer2']) {
            $ar['bom_idx'] = $arr['bom_idx'];
            $ar['cst_idx'] = $arr['cst_idx_customer2'];
            $ar['boc_type'] = 'customer';
            update_bom_customer($ar);
            unset($ar);
        }
        
        // Parent(end product) or material save for compare.
        $old_bom_code = $arr['bom_code'];   // E or Null

        $idx++; 
    }
    else {continue;}


    // 메시지 보임
    if(preg_match("/^[-0-9A-Z]+$/",$arr['bom_part_no'])) {
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
// for($i=0;$i<=sizeof($allData[1]);$i++) {
//     print_r3($allData[1][$i]);
// }


// update hierachy(계층구조) and parts count(구성품수) of bom
// print_r3($level_qty);
if(is_array($level_qty)) {
    foreach($level_qty as $k1=>$v1) {
        // print_r3($k1.'-------------------------'); // End product bom_idx
        $reply = '';
        // reset DB bit_reply for child material products.
        $sql = "UPDATE {$g5['bom_item_table']} SET bit_num = '0', bit_reply = ''
                WHERE bom_idx = '".$k1."'
        ";
        sql_query($sql,1);
        // if($demo) {print_r3($sql);}

        // print_r3($v1);
        for($i=0;$i<sizeof($v1);$i++) {
            // print_r3($v1[$i]);
            // $len1 = 2*($v1[$i]['level']-1) - 1; // 2*(1)-1
            // $len2 = 2*($v1[$i]['level']-2);   // 2*(0)
            $len1 = 2*($v1[$i]['level']) - 1; // 2*(1)-1
            $len2 = 2*($v1[$i]['level']-1);   // 2*(0)
            // if($demo) {print_r3($len2.' ----> '.$v1[$i]['level'].' level');}
            // if($demo) {print_r3($reply.' ----');}
            $reply_pre = substr($reply,0,$len2);   // prior 0digit letters for level 2, 2digit for level 3, 4digit for level 4
            // if($demo) {print_r3($reply_pre.' << ');}
            // 2*(1)-1, 2*(0).. ()부분이 내가 있는 레벨
            $sql = "SELECT MAX(SUBSTRING(bit_reply,{$len1},2)) AS max_2digit
                    FROM {$g5['bom_item_table']}
                    WHERE bom_idx = '".$k1."' AND SUBSTRING(bit_reply,1,{$len2}) = '".$reply_pre."'
            ";
            // if($demo) {print_r3($sql);}
            $bit = sql_fetch($sql,1);
            $cat_arr_next = array_search($bit['max_2digit'],$cat_arr)+1;
            // if($demo) {print_r3('cat_arr_next = '.$cat_arr_next);}
            $reply_char = (!$bit['max_2digit']) ? '10' : $reply_char = $cat_arr[$cat_arr_next];
            $reply = $reply_pre.$reply_char;    // $reply (prev $reply value should be compared every time.)
            // if($demo) {print_r3('reply = '.$reply);}
    
            // bom_bcj_json in bom table update
            update_bom_bct_json($k1);
            
            // make hierachy structure.
            $ar['bom_idx'] = $k1;
            $ar['bom_idx_child'] = $v1[$i]['bom_idx'];
            $ar['bit_num'] = $num; // 사용안함
            $ar['bit_reply'] = stripslashes($reply);
            $ar['bit_count'] = $v1[$i]['qty'];
            // if($demo) {print_r3($ar);}
            // 구조 입력
            update_bom_item($ar);
            unset($ar);
        }
    }
}



//계층구조를 확인할 수 있는 뷰테이블을 기존테이블 있으면 삭제하고 다시 생성
$drop_v_sql = " DROP VIEW {$g5['v_bom_item_table']} ";
@sql_query($drop_v_sql);

$create_v_sql = " CREATE VIEW IF NOT EXISTS {$g5['v_bom_item_table']} 
    AS
    SELECT bom.bom_idx
        , cst_idx_provider
        , bom.bom_name
        , bom_part_no
        , bom_type
        , bom_price
        , bom_status
        , cst_name
        , bit.bit_idx
        , bit.bom_idx AS bom_idx_product
        , bit.bit_main_yn
        , bit.bom_idx_child
        , bit.bit_reply
        , bit.bit_count
    FROM {$g5['bom_item_table']} AS bit
        LEFT JOIN {$g5['bom_table']} bom ON bom.bom_idx = bit.bom_idx_child
        LEFT JOIN {$g5['customer_table']} cst ON cst.cst_idx = bom.cst_idx_provider
    ORDER BY bit.bom_idx, bit.bit_reply
";
@sql_query($create_v_sql);


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