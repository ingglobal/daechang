<?php
$sub_menu = "922145";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');

$g5['title'] = '불량엑셀등록';
include_once('./_top_menu_quality.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">
<input type="hidden" name="excel_type" value="01">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>엑셀로 파일을 관리할 때는 <span style="color:darkorange;">표준엑셀 양식</span>을 지켜주셔야 합니다.</p>
    <p>구조가 복잡한 엑셀파일은 등록할 수 없습니다. (ex. <span style="color:darkorange;">복잡한 수식, 참조시트, 하단 여러탭</span>)</p>
    <p>엑셀파일에서 첫번째 탭만 사용됩니다. 업로드 시간이 많이 걸린다면 파일 내부 처리가 복잡한 문서이므로 등록할 수 없는 문서입니다.</p>
    <p>표준 양식과 다른 구조를 가진 엑셀 파일을 사용하면 안 됩니다. 기존 데이터 구조를 깨뜨릴 수 있습니다. 반드시 주의해 주세요.</p>
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
        <th scope="row">표준엑셀</th>
        <td>
            <a href="https://docs.google.com/spreadsheets/d/1AvkBjs3fQofkCDuZ_TI6zh_FcTYpXjSx_wbL8eAN7Jc/edit?usp=sharing" target="_blank">1. 23년_공정 품질문제 관리현황(08월)</a>
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
