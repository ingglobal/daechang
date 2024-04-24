<?php
// 이병구 대표가 준 알람 코드 protocol에 카피
// 실행주소: http://daechang.epcs.co.kr/adm/v10/convert/alarm_code_copy2.php
include_once('./_common.php');

$g5['title'] = '알람코드카피';
include_once(G5_PATH.'/head.sub.php');
?>
<div class="" style="padding:10px;">
	<span style=''>
		작업 시작~~ <font color=crimson><b>[끝]</b></font> 이라는 단어가 나오기 전 중간에 중지하지 마세요.
	</span><br><br>
	<span id="cont"></span>
</div>
<?php
include_once(G5_PATH.'/tail.sub.php');
?>


<?php
//-- 화면 표시
$countgap = 10; // 몇건씩 보낼지 설정
$sleepsec = 200;  // 백만분의 몇초간 쉴지 설정
$maxscreen = 50; // 몇건씩 화면에 보여줄건지?

// 변수 설정
$table1 = 'g5_1_code';
$fields1 = sql_field_names($table1);
$pre1 = substr($fields1[0],0,strpos($fields1[0],'_'));

$table2 = 'g5_1_plc_protocol';
$fields2 = sql_field_names($table2);
$pre2 = substr($fields2[0],0,strpos($fields2[0],'_'));

// 대체필드: 정의한 필드 대체 (table1 에서 1:1 관계로 대체할 필드를 배열로 선언)
$fields21 = array();





// 대상 디비 전체 추출
$sql = " SELECT * FROM {$table1} ";
// echo $sql.BR;
$result = sql_query($sql,1);


flush();
ob_flush();

$cnt=0; // 카운터를 세는 이유가 있네 (이거 안 하니까 자꾸 두번째부터 보임!)
for($i=0;$row=sql_fetch_array($result);$i++) {
	$cnt++;
    $arr = array();
	// if($i > 5)
	// 	break;
	
    // 변수 추출
    for($j=0;$j<sizeof($fields1);$j++) {
        // 공백 제거 및 따옴표 처리
        $arr[$fields1[$j]] = addslashes(trim($row[$fields1[$j]]));
        // 천단위 제거
        if(preg_match("/_price$/",$fields1[$j]))
            $arr[$fields1[$j]] = preg_replace("/,/","",$arr[$fields1[$j]]);
    }
    // print_r2($arr);
    
    // 공통쿼리
    $sql_commons[$i] = " ppr_name = '".addslashes($arr['cod_name'])."',
        ppr_ip = '".$arr['cod_plc_ip']."',
        ppr_port_no = '".$arr['cod_plc_port']."',
        ppr_no = '".$arr['cod_plc_no']."',
        ppr_bit = '".$arr['cod_plc_bit']."',
        ppr_update_dt = '".G5_TIME_YMDHIS."'
    ";

    // 중복체크
    $sql2 = " SELECT ppr_idx FROM {$table2} WHERE cod_idx = '".$arr['cod_idx']."' ";
    //echo $sql2.BR;
    $row2 = sql_fetch($sql2,1);
    // 정보 업데이트
    if($row2['ppr_idx']) {

        $sql = "UPDATE {$table2} SET 
                    {$sql_commons[$i]}
                WHERE ppr_idx = '".$row2['ppr_idx']."'
        ";
        // echo $sql.BR;
        sql_query($sql,1);

    }
    // 정보 입력
    else{

        $sql = "INSERT INTO {$table2} SET
                    com_idx = '".$arr['com_idx']."',
                    mms_idx = '".$arr['mms_idx']."',
                    cod_idx = '".$arr['cod_idx']."',
                    ppr_data_type = 'alarm',
                    {$sql_commons[$i]},
                    ppr_reg_dt = '".G5_TIME_YMDHIS."'
        ";
        // echo $sql.BR;
        sql_query($sql,1);

    }
	

    
    // 메시지 보임
	echo "<script> document.all.cont.innerHTML += '".$cnt.". ".$row['od_id']." 처리됨<br>'; </script>".PHP_EOL;
	
	flush();
	ob_flush();
	ob_end_flush();
	usleep($sleepsec);
	
	// 보기 쉽게 묶음 단위로 구분 (단락으로 구분해서 보임)
	if ($cnt % $countgap == 0)
		echo "<script> document.all.cont.innerHTML += '<br>'; </script>\n";
	
	// 화면 정리! 부하를 줄임 (화면 싹 지움)
	if ($cnt % $maxscreen == 0)
		echo "<script> document.all.cont.innerHTML = ''; </script>\n";
	
}
?>
<script>
	document.all.cont.innerHTML += "<br><br>총 <?php echo number_format($i) ?>건 완료<br><br><font color=crimson><b>[끝]</b></font>";
</script>
