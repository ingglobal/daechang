<?php
$sub_menu = "940130";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'plc_protocol';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form/","",$g5['file_name']); // _form을 제외한 파일명
$qstr .= '&ser_mms_idx='.$ser_mms_idx; // 추가로 확장해서 넘겨야 할 변수들

// print_r3($member);
// print_r3($_SESSION);

if ($w == '') {
    $sound_only = '<strong class="sound_only">필수</strong>';
    $w_display_none = ';display:none';  // 쓰기에서 숨김
    
    ${$pre}['com_idx'] = $_SESSION['ss_com_idx'];
    ${$pre}['ppr_data_type'] = 'alarm';
    ${$pre}[$pre.'_status'] = 'ok';
}
else if ($w == 'u' || $w == 'c') {
    $u_display_none = ';display:none;';  // 수정에서 숨김

	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
	// print_r3(${$pre});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');
    // 설비정보 추출
	$mms = get_table('mms', 'mms_idx', ${$pre}['mms_idx']);
	$cod = get_table('code', 'cod_idx', ${$pre}['cod_idx']);
	$bom = get_table('bom', 'bom_idx', ${$pre}['bom_idx']);
	// print_r3($cod);

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 라디오&체크박스 선택상태 자동 설정 (필드명 배열 선언!)
$check_array=array('mb_gender');
for ($i=0;$i<sizeof($check_array);$i++) {
	${$check_array[$i].'_'.${$pre}[$check_array[$i]]} = ' checked';
}

$html_title = ($w=='')?'추가':'수정'; 
$html_title = ($w=='c')?'복제':$html_title; 
$g5['title'] = 'PLC통신규약 '.$html_title;
//include_once('./_top_menu_facility.php');
include_once ('./_head.php');
//echo $g5['container_sub_title'];

?>
<style>
.frm_date {width:75px;}
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="<?=$pre?>_idx" value="<?php echo ${$pre."_idx"} ?>">
<input type="hidden" name="com_idx" value="<?=$_SESSION['ss_com_idx']?>">
<input type="hidden" name="ser_mms_idx" value="<?php echo $ser_mms_idx ?>">
<input type="hidden" name="ser_ppr_ip" value="<?php echo $ser_ppr_ip ?>">

<div class="local_desc01 local_desc" style="display:none;">
    <p>각종 고유번호(업체번호, IMP번호..)들은 내부적으로 다른 데이타베이스 연동을 통해서 정보를 가지고 오게 됩니다.</p>
</div>

<div class="tbl_frm01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?></caption>
	<colgroup>
		<col class="grid_4" style="width:15%;">
		<col style="width:35%;">
		<col class="grid_4" style="width:15%;">
		<col style="width:35%;">
	</colgroup>
	<tbody>
	<tr> 
		<th scope="row">설비선택</th>
		<td>
            <input type="hidden" name="mms_idx" value="<?=${$pre}['mms_idx']?>"><!-- 설비번호 -->
			<input type="text" name="mms_name" value="<?php echo $mms['mms_name'] ?>" id="mms_name" class="frm_input required" required readonly>
            <a href="./mms_select.php?file_name=<?php echo $g5['file_name']?>" class="btn btn_02" id="btn_mms">찾기</a>
		</td>
		<th scope="row">PLC데이터타입</th>
		<td>
			<select name="ppr_data_type" id="ppr_data_type">
				<?=$g5['set_ppr_data_type_options']?>
			</select>
			<script>$('select[name="ppr_data_type"]').val('<?=${$pre}['ppr_data_type']?>');</script>
		</td>
    </tr>
	<tr> 
        <th scope="row">태그명</th>
		<td>
			<input type="text" name="ppr_name" value="<?=${$pre}['ppr_name']?>" class="frm_input" style="width:360px;">
		</td>
        <th scope="row">알람코드</th>
		<td>
            <input type="hidden" name="cod_idx" value="<?=${$pre}['cod_idx']?>">
			<input type="text" name="cod_name" value="<?=$cod['cod_name']?>" class="frm_input">
            <span class="span_alc_code font_size_8"><?=$cod['alc_code']?></span>
            <a href="./alarm_code_select.php?file_name=<?=$g5['file_name']?>" class="btn btn_02" id="btn_cod">찾기</a>
        </td>
    </tr>
	<tr> 
        <th scope="row">제품선택</th>
		<td colspan="3">
            <input type="hidden" name="bom_idx" value="<?=${$pre}['bom_idx']?>">
			<input type="text" name="bom_name" value="<?=$bom['bom_name']?>" class="frm_input">
            <span class="span_bom_part_no font_size_8"><?=$bom['bom_part_no']?></span>
            <a href="./bom_select.php?file_name=<?=$g5['file_name']?>" class="btn btn_02" id="btn_bom">찾기</a>
        </td>
    </tr>
	<tr> 
        <th scope="row">PLC IP</th>
		<td>
			<input type="text" name="ppr_ip" value="<?=${$pre}['ppr_ip']?>" class="frm_input">
		</td>
        <th scope="row">PLC Port</th>
		<td>
			<input type="text" name="ppr_port_no" value="<?=${$pre}['ppr_port_no']?>" class="frm_input" style="width:60px;">
		</td>
    </tr>
	<tr> 
        <th scope="row">배열번호</th>
		<td>
			<input type="text" name="ppr_no" value="<?=${$pre}['ppr_no']?>" class="frm_input" style="width:40px;">
		</td>
        <th scope="row">비트번호</th>
		<td>
			<input type="text" name="ppr_bit" value="<?=${$pre}['ppr_bit']?>" class="frm_input" style="width:40px;">
		</td>
    </tr>
	<tr> 
        <th scope="row">지그코드</th>
		<td>
			<input type="text" name="ppr_jig_code" value="<?=${$pre}['ppr_jig_code']?>" class="frm_input" style="width:40px;">
		</td>
    </tr>
	<tr> 
        <th scope="row">소수점표현</th>
		<td>
			<input type="text" name="ppr_decimal" value="<?=${$pre}['ppr_decimal']?>" class="frm_input" style="width:40px;">
		</td>
        <th scope="row">시간구분</th>
		<td>
			<input type="text" name="ppr_set_time" value="<?=${$pre}['ppr_set_time']?>" class="frm_input" style="width:40px;">
		</td>
    </tr>
	<tr> 
        <th scope="row">관련부모idx</th>
		<td colspan="3">
			<?=help('카운터체크인 경우 어떤 설비 카운터를 체크해야 할 지 연결을 해 줘야 합니다. 고유번호를 입력하세요.')?>
			<?php
			$one = get_table('plc_protocol','ppr_idx',${$pre}['ppr_idx_parent']);
			// print_r2($one);
			$ppr_idx_related = '';
			if($one['ppr_idx']) {
				$ppr_idx_related = '<span>'.$one['ppr_name'].' (Word No: <b style="color:yellow;">'.($one['ppr_no']+1).'</b>)</span>';
			}
			?>
			<input type="text" name="ppr_idx_parent" value="<?=${$pre}['ppr_idx_parent']?>" class="frm_input" style="width:40px;"> <?=$ppr_idx_related?>
		</td>
    </tr>
	<tr>
		<th scope="row"><label for="ppr_memo">메모</label></th>
		<td colspan="3"><textarea name="ppr_memo" id="ppr_memo"><?php echo ${$pre}['ppr_memo'] ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_fixed_top">
    <a href="./<?=$fname?>_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
$(function() {
    // 설비찾기 버튼 클릭
	$("#btn_mms").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winShftSelect = window.open(href, "winShftSelect", "left=300,top=150,width=550,height=600,scrollbars=1");
        winShftSelect.focus();
	});

	$("#btn_cod").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winAlarm = window.open(href, "winAlarm", "left=300,top=150,width=550,height=600,scrollbars=1");
        winAlarm.focus();
	});

	$("#btn_bom").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winBom = window.open(href, "winBom", "left=300,top=150,width=550,height=600,scrollbars=1");
        winBom.focus();
	});



});

function form01_submit(f) {

    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
