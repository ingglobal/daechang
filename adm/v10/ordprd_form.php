<?php
$sub_menu = "918110";
include_once('./_common.php');
auth_check($auth[$sub_menu],'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'production';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form/","",$g5['file_name']); // _form을 제외한 파일명

// 추가 변수 생성
foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
    //    print_r3($key.'='.$value);
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
//                print_r3($key.$k2.'='.$v2);
                $qstr .= '&'.$key.'[]='.$v2;
                $form_input .= '<input type="hidden" name="'.$key.'[]" value="'.$v2.'" class="frm_input">'.PHP_EOL;
            }
        }
        else {
            $qstr .= '&'.$key.'='.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value);
            $form_input .= '<input type="hidden" name="'.$key.'" value="'.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value).'" class="frm_input">'.PHP_EOL;
        }
    }
}


if ($w == '') {
    $sound_only = '<strong class="sound_only">필수</strong>';
    $w_display_none = ';display:none';  // 쓰기에서 숨김

    ${$pre}[$pre.'_date'] = G5_TIME_YMD;
    ${$pre}[$pre.'_status'] = 'ok';
}
else if ($w == 'u') {
    $u_display_none = ';display:none;';  // 수정에서 숨김

	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');
    // print_r3(${$pre});
    $bom = get_table('bom','bom_idx',${$pre}['bom_idx']);
    $cst = get_table('customer','cst_idx',boc2cst(${$pre}['boc_idx']));

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


// 라디오&체크박스 선택상태 자동 설정 (필드명 배열 선언!)
$check_array=array('mb_field');
for ($i=0;$i<sizeof($check_array);$i++) {
	${$check_array[$i].'_'.${$pre}[$check_array[$i]]} = ' checked';
}

$html_title = ($w=='')?'추가':'수정';
$g5['title'] = '수주 '.$html_title;
// print_r2($g5['line_reverse']['1라인']);
// exit;
include_once ('./_head.php');
// print_r2(${$pre});
?>
<style>
.bop_price {font-size:0.8em;color:#a9a9a9;margin-left:10px;}
.btn_bop_delete {color:#0c55a0;cursor:pointer;margin-left:20px;}
a.btn_price_add {color:#3a88d8 !important;cursor:pointer;}
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="<?=$pre?>_idx" value="<?php echo ${$pre."_idx"} ?>">
<input type="hidden" name="sca" value="<?php echo $sca ?>">
<input type="hidden" name="ori_type" value="normal">
<?=$form_input?>

<div class="local_desc01 local_desc" style="display:none;">
    <p>가격 변경 이력을 관리합니다. (가격 변동 날짜 및 가격을 지속적으로 기록하고 관리합니다.)</p>
    <p>가격이 변경될 미래 날짜를 지정해 두면 해당 날짜부터 변경될 가격이 적용됩니다.</p>
</div>
<?php //echo $rowb['bom_bomf1'][0]['file'];//print_r3($rowb['bom_bomf1']); ?>
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
        <th scope="row">수주일</th>
		<td>
			<input type="text" name="prd_date" id="prd_date" value="<?=${$pre}['prd_date']?>" class="frm_input" style="width:90px;">
		</td>
        <th scope="row">품명/품번</th>
        <td>
            <input type="hidden" name="bom_idx" value="<?=${$pre}['bom_idx']?>">
            <input type="text" name="bom_name" value="<?=$bom['bom_name']?>" class="frm_input required readonly" required readonly>
            <span class="span_bom_part_no font_size_8" id="bom_part_no"><?=$bom['bom_part_no']?></span>
            <a href="./bom_select.php?file_name=<?=$g5['file_name']?>&item=customer" class="btn btn_02 btn_bom">찾기</a>
        </td>
    </tr>
    <tr>
        <th scope="row">수량</th>
        <td>
            <input type="text" name="prd_value" id="prd_value" value="<?=number_format(${$pre}['prd_value'])?>" class="frm_input" style="width:60px;text-align:right;">
        </td>
        <th scope="row">고객사</th>
        <td>
            <input type="hidden" name="boc_idx" value="<?=${$pre}['boc_idx']?>">
            <input type="text" id="cst_name" value="<?=$cst['cst_name']?>" readonly class="frm_input readonly">
        </td>
    </tr>
	<tr>
        <th scope="row">단가</th>
		<td>
			<input type="text" name="ori_price" id="ori_price" value="<?=number_format(${$pre}['ori_price'])?>" class="frm_input" style="width:100px;text-align:right;"> 원
		</td>
        <th scope="row">상태</th>
        <td>
            <select name="<?=$pre?>_status" id="<?=$pre?>_status"
                <?php if (auth_check($auth[$sub_menu],"d",1)) { ?>onFocus='this.initialSelect=this.selectedIndex;' onChange='this.selectedIndex=this.initialSelect;'<?php } ?>>
                <?=$g5['set_prd_status_options']?>
            </select>
            <script>$('select[name="<?=$pre?>_status"]').val('<?=${$pre}[$pre.'_status']?>');</script>
        </td>
    </tr>
    <tr>
        <th scope="row">생산시작일</th>
		<td>
            <input type="text" name="prd_start_date" id="prd_start_date" value="<?=${$pre}['prd_start_date']?>" class="frm_input" style="width:90px;">
        </td>
        <th scope="row">생산완료일</th>
		<td>
            <input type="text" name="prd_done_date" id="prd_done_date" value="<?=${$pre}['prd_done_date']?>" class="frm_input" style="width:90px;">
        </td>
    </tr>
    <tr>
        <th scope="row">수주유형</th>
        <td>
            <select name="<?=$pre?>_type" id="<?=$pre?>_type">
                <?=$g5['set_prd_type_options']?>
            </select>
            <script>$('select[name="<?=$pre?>_type"]').val('<?=${$pre}[$pre.'_type']?>');</script>
        </td>
        <th scope="row">등록일시</th>
        <td>
            <?=${$pre}['prd_reg_dt']?>
        </td>
    </tr>
    <?php
    $ar['id'] = 'prd_memo';
    $ar['name'] = '메모';
    $ar['type'] = 'textarea';
    $ar['value'] = ${$pre}[$ar['id']];
    $ar['colspan'] = 3;
    echo create_tr_input($ar);
    unset($ar);
    ?>
    
    
	</tbody>
	</table>
</div>

<div class="btn_fixed_top">
    <a href="./<?=$fname?>_list.php?<?=$qstr?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
$(function() {
    $("input[name$=_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });

    // 제품찾기 버튼 클릭
	$(".btn_bom").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winBomSelect = window.open(href, "winBomSelect", "left=300,top=150,width=750,height=700,scrollbars=1");
        winBomSelect.focus();
	});

    // 가격정보 보임 숨김
	$(".btn_price_add").click(function(e) {
        if( $('.tr_price').is(':hidden') ) {
            $('.tr_price').show();
        }
        else
           $('.tr_price').hide();
	});

    // 가격 입력 쉼표 처리
	$(document).on( 'keyup','input[name$=_price], #prd_value',function(e) {
        var price = thousand_comma($(this).val().replace(/[^0-9]/g,""));
        price = (price == '0') ? '' : price;
        $(this).val(price);
	});

});

function form01_submit(f) {
    if(f.ori_count.value <= 0) {
        alert('수량을 입력하세요.');
        f.ori_count.focus();
        return false;
    }

    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
