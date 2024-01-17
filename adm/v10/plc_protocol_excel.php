<?php
$sub_menu = "940120";
include_once('./_common.php');


auth_check($auth[$sub_menu],'w');


$g5['title'] = 'PLC프로토콜 엑셀관리';
<<<<<<< HEAD
include_once('./_top_menu_bom.php');
=======
include_once('./_top_menu_mms.php');
>>>>>>> 6648baf8549918dacbf80998f646bf2e62ddea83
include_once('./_head.php');
echo $g5['container_sub_title'];
?>
<style>
</style>

<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="w">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc" style="display:no ne;">
    <p>엑셀 등록 시 시간이 1분 이상(또는 더 많이) 걸리는 경우는 등록이 불가능한 경우입니다. (참조 파일로 복잡하게 얽혀 있거나 파일 크기가 너무 큰 경우입니다.)</p>
    <p>엑셀등록 안 될 때 새 문서를 만드는 방법은 동영상을 참고하세요. <a href="https://youtu.be/fu3zsiemJPc" target="_blank">[동영상보기]</a></p>
    <p>엑셀등록 시 첫번째 탭에 있는 문서만 등록합니다. (하단 두번째 탭부터는 의미 없음)</a></p>
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
<<<<<<< HEAD
            <a href="https://docs.google.com/spreadsheets/d/1BDxTudbNcYy8Uuwds45uQtep-lCN5fEF/edit?usp=sharing&ouid=103655811572310865604&rtpof=true&sd=true" target="_blank">대창공업_EPCS_DATA_2023_12_30_이병구</a>
=======
            <a href="https://docs.google.com/spreadsheets/d/1DaJBt-FaGHLRYBzTRSUj2iyWENKgQZqQXbRXUDLVgd0/edit?usp=sharing" target="_blank">대창공업_EPCS_DATA_2023_12_30_이병구</a>
>>>>>>> 6648baf8549918dacbf80998f646bf2e62ddea83
        </td>
    </tr>
	<tr style="display:none;">
        <th scope="row">설비선택</th>
        <td>
            <input type="hidden" name="mms_idx" value=""><!-- 설비번호 -->
			<input type="text" name="mms_name" value="" id="mms_name" class="frm_input" readonly>
            <a href="./mms_select.php?file_name=<?php echo $g5['file_name']?>" class="btn btn_02" id="btn_mms">찾기</a> 부모설비를 선택하세요.
        </td>
    </tr>
	<tr>
        <th scope="row">PLC IP</th>
        <td>
            <input type="text" name="ip" class="frm_input required" required style="width:130px;" value="192.168.100.128">
        </td>
    </tr>
	<tr>
        <th scope="row">포트</th>
        <td>
            <input type="text" name="port" class="frm_input required" required style="width:65px;" value="20480"> 포트번호를 입력하세요.
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
    // 설비찾기 버튼 클릭
	$("#btn_mms").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winShftSelect = window.open(href, "winShftSelect", "left=300,top=150,width=550,height=600,scrollbars=1");
        winShftSelect.focus();
	});


});

function form01_submit(f) {


    return true;
}

</script>

<?php
include_once ('./_tail.php');
?>
