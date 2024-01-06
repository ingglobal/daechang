<?php
$sub_menu = "940130";
include_once('./_common.php');


auth_check($auth[$sub_menu],'w');


$g5['title'] = '설비별작업자 엑셀등록';
include_once('./_top_menu_mms.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>표준 엑셀 양식과 동일한 구조의 엑셀로 등록하셔야 합니다. 표준엑셀양식 <a href="https://docs.google.com/spreadsheets/d/1L5akSn_9n6VA3RuK9pUJs4dSq8ELs7kHbXOzWUrs59U/edit?usp=sharing" target="_blank">바로가기</a></p>
</div>

<div class="tbl_frm01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4" style="width:15%;">
		<col style="width:85%;">
    </colgroup>
	<tbody>
	<tr>
        <th scope="row">엑셀파일</th>
        <td>
            <?=help('엑셀은 표준 양식으로 등록해 주셔야 합니다.')?>
            <input type="file" name="file_excel_k1" class="frm_input">
        </td>
    </tr>
	</tbody>
	</table>
</div>

<div class="btn_fixed_top">
    <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
</div>
</form>

<script>
$(function() {
    $("input[name=ori_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});

});

function form01_submit(f) {
    if(!f.file_excel_k1.value){
        alert('엑셀파일을 반드시 선택해 주세요');
        f.file_excel_k1.focus();
        return false;
    }
    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
