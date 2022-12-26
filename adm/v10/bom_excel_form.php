<?php
$sub_menu = "940120";
include_once('./_common.php');


auth_check($auth[$sub_menu],'w');


$g5['title'] = 'BOM 엑셀관리';
include_once ('./_head.php');
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>각 부서에서 관리하는 엑셀 문서를 해당 위치에 업로드해 주세요. 다른 위치에 입력하시면 혼란이 발생할 수 있습니다.</p>
    <p><span style="color:darkorange;">기존 정보가 존재하는 경우 덮어쓰기 변경</span>됩니다. 주의해 주시기 바랍니다.</p>
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
        <th scope="row">엑셀종류</th>
        <td>
            <?=help('엑셀을 선택하세요.')?>
            <select name="excel_type">
                <option value="">엑셀 선택</option>
                <option value="01">대창공업 ITEM LIST_REV1(22.12.22)-개발이범희GJ_REV6.xlxs</option>
                <option value="02">대창공업</option>
            </select>
        </td>
    </tr>
	<tr>
        <th scope="row">엑셀 파일</th>
        <td>
            <input type="file" name="file_excel" class="frm_input">
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

});

function form01_submit(f) {


    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
