<?php
$sub_menu = "940120";
include_once('./_common.php');


auth_check($auth[$sub_menu],'w');


$g5['title'] = 'BOM 엑셀관리';
include_once('./_top_menu_bom.php');
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>엑셀 등록 시 시간이 5분 이상(또는 더 많이) 걸리는 경우는 등록이 불가능한 경우입니다. (참조 파일로 복잡하게 얽혀 있거나 파일 크기가 너무 큰 경우입니다.)</p>
    <p><span style="color:darkorange;">참조가 없는 단순 파일</span>로 등록하시거나 <span style="color:darkorange;">파일 크기를 나누어서</span>등록해 주시기 바랍니다.</p>
    <p>엑셀등록 안 될 때 새 문서를 만드는 방법은 동영상을 참고하세요. <a href="https://youtu.be/fu3zsiemJPc" target="_blank">[바로가기]</a></p>
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
            <?=help('엑셀 종류를 선택하세요. 다른 파일을 선택하시면 <span style="color:darkorange;">덮어쓰기</span>되므로 주의하세요.')?>
            <select name="excel_type" id="excel_type">
                <option value="">엑셀 선택</option>
                <option value="01">대창공업 ITEM LIST_REV1(22.12.22)-개발이범희GJ_REV6.xlxs</option>
                <option value="02">대창공업</option>
            </select>
            <script>$('#excel_type').val('01')</script>
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
