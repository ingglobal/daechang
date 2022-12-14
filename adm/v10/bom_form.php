<?php
$sub_menu = "940120";
include_once('./_common.php');
include_once(G5_USER_ADMIN_LIB_PATH.'/category.lib.php');
auth_check($auth[$sub_menu],'w');

// 변수 설정, 필드 구조 및 prefix 추출
$table_name = 'bom';
$g5_table_name = $g5[$table_name.'_table'];
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form/","",$g5['file_name']); // _form을 제외한 파일명
$qstr .= '&sca='.$sca.'&ser_bom_type='.$ser_bom_type; // 추가로 확장해서 넘겨야 할 변수들


if ($w == '') {
    $sound_only = '<strong class="sound_only">필수</strong>';
    $w_display_none = ';display:none';  // 쓰기에서 숨김

    ${$pre}[$pre.'_count'] = 1;
    ${$pre}[$pre.'_moq'] = 1;
    ${$pre}[$pre.'_start_date'] = G5_TIME_YMD;
    ${$pre}[$pre.'_status'] = 'ok';
}
else if ($w == 'u') {
    $u_display_none = ';display:none;';  // 수정에서 숨김

	${$pre} = get_table_meta($table_name, $pre.'_idx', ${$pre."_idx"});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');
    // print_r3(${$pre});
    $com = get_table_meta('company','com_idx',$bom['com_idx_customer']);
    $com2 = get_table_meta('company','com_idx',$bom['com_idx_provider']);

    // 가격 (오늘날짜 기준가격)
    ${$pre}['bom_price'] = get_bom_price(${$pre."_idx"});

    //완성품만 이미지를 등록한다.
    if(${$pre}['bom_type'] == 'product'){
        //관련파일 추출
        $flesql = " SELECT * FROM {$g5['file_table']}
        WHERE fle_db_table = 'bom'
        AND fle_type IN ('bomf1','bomf2','bomf3','bomf4','bomf5','bomf6')
        AND fle_db_id = '".${$pre."_idx"}."' ORDER BY fle_reg_dt,fle_idx ";
        //print_r3($flesql);
        $fle_rs = sql_query($flesql,1);

        $rowb['bom_bomf1'] = array();//1번째 파일그룹
        $rowb['bom_bomf1_idxs'] = array();//(fle_idx) 목록이 담긴 배열
        $rowb['bom_bomf2'] = array();//2번째 파일그룹
        $rowb['bom_bomf2_idxs'] = array();//(fle_idx) 목록이 담긴 배열
        $rowb['bom_bomf3'] = array();//3번째 파일그룹
        $rowb['bom_bomf3_idxs'] = array();//(fle_idx) 목록이 담긴 배열
        $rowb['bom_bomf4'] = array();//4번째 파일그룹
        $rowb['bom_bomf4_idxs'] = array();//(fle_idx) 목록이 담긴 배열
        $rowb['bom_bomf5'] = array();//5번째 파일그룹
        $rowb['bom_bomf5_idxs'] = array();//(fle_idx) 목록이 담긴 배열
        $rowb['bom_bomf6'] = array();//6번째 파일그룹
        $rowb['bom_bomf6_idxs'] = array();//(fle_idx) 목록이 담긴 배열

        for($i=0;$flerow=sql_fetch_array($fle_rs);$i++){
            //print_r3($flerow);
            $file_del = (is_file(G5_PATH.$flerow['fle_path'].'/'.$flerow['fle_name'])) ? $flerow['fle_name_orig'].'&nbsp;&nbsp;<a href="'.G5_USER_ADMIN_URL.'/lib/download.php?file_fullpath='.urlencode(G5_PATH.$flerow['fle_path'].'/'.$flerow['fle_name']).'&file_name_orig='.$flerow['fle_name_orig'].'" file_path="'.$flerow['fle_path'].'">[파일다운로드]</a>&nbsp;&nbsp;'.$flerow['fle_reg_dt'].'&nbsp;&nbsp;<label for="del_'.$flerow['fle_idx'].'" style="position:relative;top:-3px;cursor:pointer;"><input type="checkbox" name="'.$flerow['fle_type'].'_del['.$flerow['fle_idx'].']" id="del_'.$flerow['fle_idx'].'" value="1"> 삭제</label><br><img src="'.G5_URL.$flerow['fle_path'].'/'.$flerow['fle_name'].'" style="width:200px;height:auto;">':''.PHP_EOL;
            @array_push($rowb['bom_'.$flerow['fle_type']],array('file'=>$file_del));
            @array_push($rowb['bom_'.$flerow['fle_type'].'_idxs'],$flerow['fle_idx']);
        }
        //print_r3($rowb['bom_bomf1']);
    }
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


// 라디오&체크박스 선택상태 자동 설정 (필드명 배열 선언!)
$check_array=array('mb_field');
for ($i=0;$i<sizeof($check_array);$i++) {
	${$check_array[$i].'_'.${$pre}[$check_array[$i]]} = ' checked';
}

$html_title = ($w=='')?'추가':'수정';
$g5['title'] = '제품(BOM) '.$html_title;
// print_r2($g5['line_reverse']['1라인']);
// exit;
include_once ('./_head.php');
?>
<script src="<?=G5_USER_ADMIN_JS_URL?>/multifile/jquery.MultiFile.min.js" type="text/javascript" language="javascript"></script>
<style>
.bop_price {font-size:0.8em;color:#a9a9a9;margin-left:10px;}
.btn_bop_delete {color:#0c55a0;cursor:pointer;margin-left:20px;}
a.btn_price_add {color:#3a88d8 !important;cursor:pointer;}
/*멀티파일관련*/
input[type="file"]{position:relative;width:250px;height:80px;border-radius:10px;overflow:hidden;cursor:pointer;border:1px solid #333;}
input[type="file"]::before{display:block;content:'';position:absolute;left:0;top:0;width:100%;height:100%;background:#000;opacity:1;z-index:3;}
input[type="file"]::after{display:block;content:'파일선택\A(드래그앤드롭 가능)';position:absolute;z-index:4;left:50%;top:50%;transform:translate(-50%,-50%);text-align:center;}
.MultiFile-wrap ~ ul{margin-top:10px;}
.MultiFile-wrap ~ ul > li{margin-top:10px;}
.MultiFile-wrap .MultiFile-list{}
.MultiFile-wrap .MultiFile-list > .MultiFile-label{position:relative;padding-left:25px;margin-top:10px;}
.MultiFile-wrap .MultiFile-list > .MultiFile-label .MultiFile-remove{position:absolute;top:0;left:0;font-size:0;}
.MultiFile-wrap .MultiFile-list > .MultiFile-label .MultiFile-remove::after{content:'×';display:block;position:absolute;left:0;top:0;width:20px;height:20px;border:1px solid #ccc;border-radius:50%;font-size:14px;line-height:20px;text-align:center;}
.MultiFile-wrap .MultiFile-list > .MultiFile-label > span{}
.MultiFile-wrap .MultiFile-list > .MultiFile-label > span span.MultiFile-label{display:inline-block;font-size:14px;border:1px solid #444;background:#333;padding:2px 5px;border-radius:3px;line-height:1.2em;margin-top:5px;}
#sp_notice,#sp_ex_notice{color:orange;margin-left:10px;}
#sp_notice.sp_error,#sp_ex_notice.sp_error{color:red;}
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="<?=$pre?>_idx" value="<?php echo ${$pre."_idx"} ?>">
<input type="hidden" name="sca" value="<?php echo $sca ?>">
<input type="hidden" name="ser_bom_type" value="<?php echo $ser_bom_type ?>">

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
        <?php
        $ar['id'] = 'bom_name';
        $ar['name'] = '품명';
        $ar['type'] = 'input';
        $ar['width'] = '100%';
        $ar['value'] = ${$pre}[$ar['id']];
        $ar['required'] = 'required';
        $ar['placeholder'] = '제품명 or 자재명';
        echo create_td_input($ar);
        unset($ar);
        ?>
        <th scope="row">타입</th>
		<td>
            <select name="bom_type" id="bom_type">
                <option value="">선택하세요</option>
                <?=$g5['set_bom_type_options']?>
            </select>
            <script>
                $('select[name="<?=$pre?>_type"]').val('<?=${$pre}[$pre.'_type']?>');
            </script>
		</td>
    </tr>
	<tr>
        <th scope="row">품번 (P/NO)</th>
        <td>
            <input type="text" name="bom_part_no" value="<?php echo ${$pre}['bom_part_no'] ?>" id="bom_part_no" required class="frm_input required" style="width:150px;" onkeyup="javascript:chk_Code(this)">
            <span id="sp_notice"></span>
        </td>
		<th scope="row">분류</th>
		<td>
            <?php
            $csql = " SELECT bct_idx,bct_name FROM {$g5['bom_category_table']} WHERE com_idx = '{$_SESSION['ss_com_idx']}' AND LENGTH(bct_id) = 2 ORDER BY bct_order, bct_id ";
            // echo $csql;
            $cresult = sql_query($csql,1);
            if($cresult->num_rows){
                echo '<select name="bct_idx" id="bct_idx" class="frm_input">'.PHP_EOL;
                    echo '<option value="">카테고리 선택</option>'.PHP_EOL;
                    for($i=0;$row=sql_fetch_array($cresult);$i++){
                    ?>
                    <option value="<?=$row['bct_idx']?>"><?=$row['bct_name']?></option>
                    <?php
                    }
                echo '</select>'.PHP_EOL;
                if($w == 'u'){
                ?>
                <script>
                $('#bct_id').val('<?=${$pre}['bct_idx']?>');
                </script>
                <?php
                }
            }
            ?>
		</td>
    </tr>
    <?php
    $ar['id'] = 'bom_memo';
    $ar['name'] = '메모';
    $ar['type'] = 'textarea';
    $ar['value'] = ${$pre}[$ar['id']];
    $ar['colspan'] = 3;
    echo create_tr_input($ar);
    unset($ar);
    ?>
    <tr>
        <th scope="row">상태</th>
        <td colspan="3">
            <select name="<?=$pre?>_status" id="<?=$pre?>_status"
                <?php if (auth_check($auth[$sub_menu],"d",1)) { ?>onFocus='this.initialSelect=this.selectedIndex;' onChange='this.selectedIndex=this.initialSelect;'<?php } ?>>
                <?=$g5['set_bom_status_options']?>
            </select>
            <script>$('select[name="<?=$pre?>_status"]').val('<?=${$pre}[$pre.'_status']?>');</script>
        </td>
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
    // 코드중복 체크
    chk_Code(document.getElementById('bom_part_no'));

    $("input[name$=_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });

    // 가격정보 보임 숨김
	$(".btn_price_add").click(function(e) {
        if( $('.tr_price').is(':hidden') ) {
            $('.tr_price').show();
        }
        else
           $('.tr_price').hide();
	});


    // 가격 입력 쉼표 처리
	$(document).on( 'keyup','input[name$=_price], #bom_moq, #bom_lead_time',function(e) {
//        console.log( $(this).val() )
//		console.log( $(this).val().replace(/,/g,'') );
        if(!isNaN($(this).val().replace(/,/g,'')))
            $(this).val( thousand_comma( $(this).val().replace(/,/g,'') ) );
	});

});

// 숫자만 입력
function chk_Number(object){
    $(object).keyup(function(){
        $(this).val($(this).val().replace(/[^0-9|-]/g,""));
    });
}

function chk_Code(object){
    var bom_idx = '<?=${$pre."_idx"}?>';
    var com_chk_url = './ajax/bom_part_no_overlap_chk.php';
    var st = $.trim(str.toUpperCase());
    var msg = '등록 가능한 코드입니다.';
    object.value = st;
    document.getElementById('sp_notice').textContent = msg;
    $('#sp_notice').removeClass('sp_error');
    //디비에 bom_part_no가 존재하는지 확인하고 존재하면 에러를 발생
    //console.log(st);
    $.ajax({
        type : 'POST',
        url : com_chk_url,
        dataType : 'text',
        data : {'bom_idx' : bom_idx,'bom_part_no' : st},
        success : function(res){
            //console.log(res);
            if(res == 'ok'){
                document.getElementById('sp_notice').textContent = '등록 가능한 코드입니다.';
                $('#sp_notice').removeClass('sp_error');
            }
            else if(res == 'overlap'){
                document.getElementById('sp_notice').textContent = '이미 등록된 코드입니다.';
                $('#sp_notice').removeClass('sp_error');
                $('#sp_notice').addClass('sp_error');
            }
            else if(res == 'same'){
                document.getElementById('sp_notice').textContent = '품번 설정완료';
                $('#sp_notice').removeClass('sp_error');
            }
        },
        error : function(xmlReq){
            alert('Status: ' + xmlReq.status + ' \n\rstatusText: ' + xmlReq.statusText + ' \n\rresponseText: ' + xmlReq.responseText);
        }
    });
}

function form01_submit(f) {

    if($('#sp_notice').hasClass('sp_error')){
        alert('올바른 품번를 입력해 주세요.');
        $('input[name="bom_part_no"]').focus();
        return false;
    }

    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
