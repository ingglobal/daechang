<?php
$sub_menu = "940120";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');

// Define a function to compare files by modification time
function compare_by_mtime($file1, $file2) {
    $time1 = filemtime($file1);
    $time2 = filemtime($file2);
    if ($time1 == $time2) {
      return 0;
    }
    return ($time1 > $time2) ? -1 : 1;
}
  
// Get the files in the images folder
$dir = '/data/excels/bom';
$files = glob(G5_PATH.$dir."/*");

// Sort the files by modification time in descending order
usort($files, "compare_by_mtime");

// Get the first 10 files
$latest_files = array_slice($files, 0, 30);

// Delete the files that are not in the last 10 files
$last_files = array_slice($files, -30);
foreach ($files as $file) {
    if (!in_array($file, $latest_files)) {
        unlink($file);
    }
}


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
    <p>엑셀로 파일을 관리할 때는 <span style="color:darkorange;">표준 양식</span>을 지켜주셔야 합니다.</p>
    <p>표준 양식과 다른 구조를 가진 엑셀 파일을 사용하면 안 됩니다. 기존 데이터 구조를 깨뜨릴 수 있습니다. 반드시 주의해 주세요.</p>
    <p>BOM엑셀을 등록한 다음 차례대로 엑셀을 등록해서 주셔야 합니다. (BOM엑셀 -> 지그엑셀 -> PLC엑셀)</p>
</div>
<div class="local_desc01 local_desc" style="display:none;">
    <p>엑셀 등록 시 시간이 5분 이상(또는 더 많이) 걸리는 경우는 등록이 불가능한 경우입니다. (참조 파일로 복잡하게 얽혀 있거나 파일 크기가 너무 큰 경우입니다.)</p>
    <p><span style="color:darkorange;">참조가 없는 단순 파일</span>로 등록하시거나 <span style="color:darkorange;">파일 크기를 나누어서</span>등록해 주시기 바랍니다.</p>
    <p>엑셀등록 안 될 때 새 문서를 만드는 방법은 동영상을 참고하세요. <a href="https://youtu.be/fu3zsiemJPc" target="_blank">[동영상보기]</a></p>
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
            <input type="hidden" name="excel_type" value="01">
            <?=help('표준 엑셀과 동일한 구조의 파일을 등록해 주셔야 합니다. 표준엑셀은 아래에서 확인하세요.')?>
            <a href="https://docs.google.com/spreadsheets/d/1Lp2MOrDCxrt3N9jhHtCkKn6dViA9ebj57QaD29kLWiA/edit?usp=sharing" target="_blank">1. LX2 BOM(231123)</a>
            <!-- <select name="excel_type" id="excel_type">
                <option value="">엑셀 선택</option>
                <option value="01">대창공업 ITEM LIST_REV1(22.12.22)-개발이범희GJ_REV6.xlxs</option>
                <option value="03">대창공업</option>
            </select>
            <script>$('#excel_type').val('01')</script> -->
        </td>
    </tr>
	<tr>
        <th scope="row">엑셀 파일</th>
        <td>
            <input type="file" name="file_excel" class="frm_input">
        </td>
    </tr>
	<tr>
        <th scope="row">파일업로드기록</th>
        <td>
            <?php
            foreach ($latest_files as $file) {
                // echo $file.BR;
                $file_arr = explode("/",$file);
                $file_name = $file_arr[sizeof($file_arr)-1];
                // print_r2($file_arr);
                // echo $file_arr[sizeof($file_arr)-1].BR;
                $file_fullpath = $file;
                $file_name_orig = $file_name;
                echo '<a href="'.G5_USER_ADMIN_URL.'/lib/download.php?file_fullpath='.$file_fullpath.'&file_name_orig='.$file_name_orig.'">'.$file_arr[sizeof($file_arr)-1].'</a>'.BR;
            }
            ?>
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
