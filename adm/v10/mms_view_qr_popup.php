<?php
// 호출페이지들
// /adm/v10/mms_parts_form.php: 설비검색
include_once('./_common.php');

if($member['mb_level']<4)
	alert_close('접근할 수 없는 메뉴입니다.');

$mms = get_table_meta('mms','mms_idx',$mms_idx);
if (!$mms['mms_idx'])
    alert_close('존재하지 않는 자료입니다.');


$g5['title'] = $mms['mms_name'].' 모바일접속 QR코드';
include_once('./_head.sub.php');

$qr_width = 500;
$qr_height = 500;

?>
<script>
// 윈도우 크기 재설정
window.onload = reSize;
window.onresize = reSize;
function reSize() {
	resizeTo(700, 1000);
}
</script>

<div id="qr_view" class="new_win qr_new_win" style="padding-bottom:0;">
    <h1><?php echo $g5['title'];?></h1>

    <div id="qr_in_view" class="view01">
        <div class="in_box">
            <img src="https://chart.googleapis.com/chart?chs=<?=$qr_width?>x<?=$qr_height?>&cht=qr&chl=<?=G5_USER_ADMIN_MOBILE_URL?>/production_list.php?mms_idx=<?=$mms_idx?>">
            <h2>설비명 : <?=$mms['mms_name']?></h2>
            <h3>No. <?=$mms['mms_idx']?></h3>
        </div>
    </div>

    <div class="btn_fixed_top">
        <!-- <button type="button" id="btn_print" class="btn btn_03 btn_print">프린트</button> -->
        <a href="javascript:window.close();" id="member_add" class="btn btn_02">창닫기</a>
    </div>
</div>

<script>
var printBtn = document.getElementById("btn_print");
function handlePrint() {
    var prtCtnt = document.getElementById('qr_in_view').innerHTML;
    var orgCtnt = document.body.innerHTML;
    document.body.innerHTML = prtCtnt;
    document.body.print();
    document.body.innerHTML = orgCtnt;
    window.location.reload();
}
// printCnt.addEventListener("click", countInput);
printBtn.addEventListener("click", handlePrint);

// 페이지 로드가 완료되면 이벤트 리스너를 등록
window.addEventListener('load', init);
</script>
<?php
include_once('./_tail.sub.php');
?>