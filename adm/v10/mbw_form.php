<?php

?>
<form id="fmbw" name="fmbw" action="./mbw_form_update.php" onsubmit="return fmbw_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sfl2" value="<?php echo $sfl2 ?>">
<input type="hidden" name="stx2" value="<?php echo $stx2 ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="file_name" value="<?=$g5['file_name']?>">
<div class="local_desc01 local_desc" style="display:no ne;">
    <p>
        아래의 정보로 "제품-설비-지그"와 "설비-제품-작업자"를 한 번에 등록할 수 있습니다.<br>
        한 설비에 "주간/야간"은 한 개씩만 등록되고 나머지는 "서브"로 등록됩니다.<br>
        "테스트"로 등록된 데이터는 추후에 한 번에 삭제 가능합니다.
    </p>
</div>
<div class="tbl_frm01 tbl_wrap">
<table>
	<colgroup>
		<col class="grid_4" style="width:5%;">
		<col style="width:15%;">
		<col class="grid_4" style="width:7%;">
		<col style="width:15%;">
		<col class="grid_4" style="width:5%;">
		<col style="width:30%;">
		<col class="grid_4" style="width:5%;">
		<col style="width:20%;">
	</colgroup>
	<tbody>
        <tr>
            <th scope="row">설비</th>
            <td>
                <select name="mms_idx" id="mms_idx">
                    <?=$g5['mms_options_no']?>
                </select>
            </td>
            <th scope="row">지그</th>
            <td>
                <select name="jig_code" id="jig_code">
                    <?=$g5['jig_options']?>
                </select>
            </td>
            <th scope="row">제품</th>
            <td>
                <input type="hidden" name="bom_idx" value="">
                <input type="text" name="bom_name" value="" class="frm_input readonly" readonly>
                <span class="span_bom_part_no font_size_8"></span>
                <a href="./bom_select.php?file_name=<?=$g5['file_name']?>" class="btn btn_02" id="btn_bom">찾기</a>
            </td>
            <th scope="row">작업자</th>
            <td>
                <select name="mb_id" id="mb_id">
                    <?=$g5['mbw_options_no']?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">타입</th>
            <td>
                <select name="bmw_type" id="bmw_type">
                    <?=$g5['set_bmw_type_options']?>
                </select>
            </td>
            <th scope="row">테스트여부</th>
            <td>
                <select name="test_yn" id="test_yn">
                    <?=$g5['set_test_yn_value_options']?>
                </select>&nbsp;<span>테스트데이터여부</span>
            </td>
            <td colspan="5" style="text-align:right;">
                <input type="submit" value="등록" name="act_button" class="btn_submit btn" accesskey='s' onclick="document.pressed=this.value">
                <input type="submit" value="테스트데이터전부삭제" name="act_button" class="btn_delete btn" accesskey='s' onclick="document.pressed=this.value">
            </td>
        </tr>
    </tbody>
</table>
</div><!--//.tbl_frm01-->
</form>
<script>
$("#btn_bom").click(function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    winBom = window.open(href, "winBom", "left=300,top=150,width=750,height=600,scrollbars=1");
    winBom.focus();
});

function fmbw_submit(f){
    if(document.pressed == "등록" && !f.bom_idx.value){
        alert('제품을 선택해 주세요');
        f.bom_name.focus();
        return false;
    }
    // if(document.pressed == "등록") {
	// 	;// alert('등록');
	// }
    if(document.pressed == "테스트데이터전부삭제") {
		if(!confirm('정말로 테스트데이터 전부를 삭제하시겠습니까?'))
            return false;
	}

    return true;
}
</script>