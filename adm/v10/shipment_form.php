<?php
$sub_menu = "918120";
include_once('./_common.php');
auth_check($auth[$sub_menu],'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'shipment';
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

    ${$pre}[$pre.'_dt'] = G5_TIME_YMD.' 15:00:00';
    ${$pre}[$pre.'_status'] = 'pending';
}
else if ($w == 'u' || $w == 'c') {
    $u_display_none = ';display:none;';  // 수정에서 숨김

    $sql = " SELECT * FROM {$g5['shipment_table']} shp
            LEFT JOIN {$g5['production_table']} prd ON shp.prd_idx = prd.prd_idx
            LEFT JOIN {$g5['bom_table']} bom ON prd.bom_idx = bom.bom_idx
            WHERE shp_idx = '{$shp_idx}'
    ";
    ${$pre} = sql_fetch($sql);
	// ${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');

 }
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 수주 정보가 있는 경우 상세내용 표시
${$pre}['prd_detail'] = cst2name(boc2cst(${$pre}['boc_idx'])).', <b>'.${$pre}['bom_part_no'].'</b> (품명:'.${$pre}['bom_name'].'), 수량:<b>'.${$pre}['prd_value'].'</b>';



// 라디오&체크박스 선택상태 자동 설정 (필드명 배열 선언!)
$check_array=array('mb_field');
for ($i=0;$i<sizeof($check_array);$i++) {
	${$check_array[$i].'_'.${$pre}[$check_array[$i]]} = ' checked';
}

$html_title = ($w=='')?'추가':'수정';
$html_title = ($w=='c')?'복제':$html_title; 
$g5['title'] = '출하 '.$html_title;
// print_r2($g5['line_reverse']['1라인']);
// exit;
include_once ('./_head.php');
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
<input type="hidden" name="sst2" value="<?php echo $sst2 ?>">
<input type="hidden" name="sod2" value="<?php echo $sod2 ?>">
<input type="hidden" name="sst3" value="<?php echo $sst3 ?>">
<input type="hidden" name="sod3" value="<?php echo $sod3 ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="<?=$pre?>_idx" value="<?php echo ${$pre."_idx"} ?>">
<?=$form_input?>

<div class="local_desc01 local_desc" style="display:<?=($w!='c')?'none':''?>;">
    <p>각 항목 내용을 입력(수정)하시고 오른편 상단 [확인]버튼을 클릭하세요.</p>
    <p style="display:<?=($w!='c')?'none':''?>;"><span class="color_red">항목을 복제</span>하는 경우 수량 분산이 제대로 되었는지 확인해 주세요.</p>
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
        <th scope="row">수주 선택</th>
		<td colspan="3">
			<input type="text" name="prd_idx" value="<?=${$pre}['prd_idx']?>" class="frm_input required readonly" style="width:90px" required readonly>
            <a href="./order_item_select.php?file_name=<?=$g5['file_name']?>" class="btn btn_02 btn_order_item">찾기</a>
            <span class="span_prd_detail font_size_8"><?=${$pre}['prd_detail']?></span>
        </td>
    </tr>
	<tr>
        <th scope="row">출하처</th>
        <td>
            <input type="hidden" name="boc_idx" value="<?=${$pre}['boc_idx']?>">
            <input type="hidden" name="cst_idx" value="<?=boc2cst(${$pre}['boc_idx'])?>">
            <input type="text" name="cst_name" value="<?=cst2name(boc2cst(${$pre}['boc_idx']))?>" class="frm_input required readonly" required readonly>
        </td>
        <th scope="row">출하일시</th>
		<td>
			<input type="hidden" name="shp_dt" id="shp_dt" value="<?=${$pre}['shp_dt']?>">
			<input type="text" id="shp_date" value="<?=substr(${$pre}['shp_dt'],0,10)?>" class="frm_input" style="width:90px;">
            <select id="shp_time"></select>
            <script>
                timePicker($('#shp_time'),24,0,23);
                $('#shp_time').val('<?=substr(${$pre}['shp_dt'],11,5)?>');
            </script>
		</td>
    </tr>
    <?php
    $ar['id'] = 'shp_memo';
    $ar['name'] = '메모';
    $ar['type'] = 'textarea';
    $ar['value'] = ${$pre}[$ar['id']];
    $ar['colspan'] = 3;
    echo create_tr_input($ar);
    unset($ar);
    ?>
    <tr>
        <th scope="row">출하수량</th>
        <td>
            <input type="text" name="shp_count" id="shp_count" value="<?=number_format(${$pre}['shp_count'])?>" class="frm_input" style="width:60px;text-align:right;">
        </td>
        <th scope="row">상태</th>
        <td>
            <select name="<?=$pre?>_status" id="<?=$pre?>_status"
                <?php if (auth_check($auth[$sub_menu],"d",1)) { ?>onFocus='this.initialSelect=this.selectedIndex;' onChange='this.selectedIndex=this.initialSelect;'<?php } ?>>
                <?=$g5['set_shp_status_options']?>
            </select>
            <script>$('select[name="<?=$pre?>_status"]').val('<?=${$pre}[$pre.'_status']?>');</script>
        </td>
    </tr>
	</tbody>
	</table>
</div>

<div class="btn_fixed_top">
    <a href="./<?=$fname?>_form.php?<?=$qstr?>&w=c&<?=$pre?>_idx=<?=${$pre."_idx"}?>" class="btn btn_02" style="margin-right:50px;display:<?=($w=='')?'none':''?>;">복제</a>
    <a href="./<?=$fname?>_list.php?<?=$qstr?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
$(function() {
    $("input[id$=_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });

    $('#shp_date,#shp_time').on('change',function(){
        var dat = '';
        var tim = '';
        var dt = '';
        if($(this).attr('id') == 'shp_date'){
            dat = $(this).val();
            tim = ' '+$('#shp_time').val()+':00';
        } else {
            dat = $('#shp_date').val();
            tim = ' '+$(this).val()+':00';
        }
        dt = dat+tim;
        $('#shp_dt').val(dt);
    });

    // 수주 찾기
	$(".btn_order_item").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winOrderItem = window.open(href, "winOrderItem", "left=300,top=150,width=750,height=600,scrollbars=1");
        winOrderItem.focus();
	});
    // 거래처찾기
	$(".btn_customer").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winCustomerSelect = window.open(href, "winCustomerSelect", "left=300,top=150,width=550,height=600,scrollbars=1");
        winCustomerSelect.focus();
	});
    // 배송기사 찾기
	$(".btn_member").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winMember = window.open(href, "winMember", "left=300,top=150,width=550,height=600,scrollbars=1");
        winMember.focus();
	});

    // 가격정보 보임 숨김
	$(".btn_price_add").click(function(e) {
        if( $('.tr_price').is(':hidden') ) {
            $('.tr_price').show();
        }
        else
           $('.tr_price').hide();
	});

    // 가격 입력 쉼표 처리 숫자만입력 번호만입력
	$('#shp_count').on( 'keyup',function(e) {
        var price = thousand_comma($(this).val().replace(/[^0-9]/g,""));
        price = (price == '0') ? '' : price;
        $(this).val(price);
	});

});

function form01_submit(f) {
    if(f.shp_count.value <= 0) {
        alert('수량을 입력하세요.');
        f.shp_count.focus();
        return false;
    }

    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
