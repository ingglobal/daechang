<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style2.css">', 0);
// print_r3($write);
?>
<style>
    .towhom_info .fa {cursor:pointer;}
    .ui-widget-shadow {opacity: 0.8;}
    .btn_mb_report {padding: 0px 10px !important;}
</style>

<section id="bo_w">
    <h2 class="sound_only"><?php echo $g5['title'] ?></h2>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="ser_com_idx" value="<?php echo $ser_com_idx ?>">
    <input type="hidden" name="ser_wr_5" value="<?php echo $ser_wr_5 ?>">
    <input type="hidden" name="ser_wr_6" value="<?php echo $ser_wr_6 ?>">
    <input type="hidden" name="ser_wr_10" value="<?php echo $ser_wr_10 ?>">
    <div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup>
        <col class="grid_2">
        <col>
        <col class="grid_2">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">설비</th>
        <td colspan="3">
            <input type="hidden" name="com_idx" value="<?=(($w == '')?$_SESSION['ss_com_idx']:$write['wr_1'])?>"><!-- 업체번호 -->
            <input type="hidden" name="mms_idx" value="<?=$write['wr_2']?>"><!-- 설비번호 -->
            <input type="hidden" name="com_name" value="<?=$write['com_name']?>"><!-- 업체명 -->
            <input type="text" name="mms_name" value="<?=$write['mms_name']?>" id="mms_name" required class="frm_input required" style="width:200px;" readonly>
            <div style="display:<?=($write['mms_idx']&&$member['mb_manager_yn']) ? 'none':'inline-block';?>;">
                <button type="button" class="btn btn_b01" id="btn_mms">설비찾기</button>
                <span id="mms_info"><?=$write['mms_info']?></span>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">업체명</th>
        <td colspan="3">
            <div id="autosave_wrapper write_div">
                <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" maxlength="255" style="width:474px;">
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">주소</th>
        <td colspan="3">
            <label for="wr_content" class="sound_only">주소<strong>필수</strong></label>
            <input type="text" name="wr_content" value="<?php echo $write['wr_content'] ?>" id="wr_content" required class="frm_input full_input required" maxlength="255" style="width:474px;">
        </td>
    </tr>
    <tr>
        <th scope="row">담당자명</th>
        <td colspan="3">
            <input type="text" name="wr_5" value="<?=$write['wr_5']?>" class="frm_input" style="width:474px;">
        </td>
    </tr>
    <tr>
        <th scope="row">전화번호</th>
        <td colspan="3">
            <input type="text" name="wr_6" value="<?=$write['wr_6']?>" class="frm_input" style="width:474px;">
        </td>
    </tr>
    <tr>
        <th scope="row">이메일</th>
        <td colspan="3">
            <input type="text" name="wr_7" value="<?=$write['wr_7']?>" class="frm_input" style="width:474px;">
        </td>
    </tr>
    <tr>
        <th scope="row">취급품목</th>
        <td colspan="3">
            <input type="text" name="wr_8" value="<?=$write['wr_8']?>" class="frm_input" style="width:474px;">
        </td>
    </tr>
    <tr>
        <th scope="row">기타메모</th>
        <td colspan="3">
            <textarea rows="5" name="wr_9" class="frm_input" style="width:474px;"><?=$write['wr_9']?></textarea>
        </td>
    </tr>
    </tbody>
    </table>
    </div><!--//.tbl_frm01.tbl_wrap-->
    <?php for ($i=10; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
    <div class="bo_w_link write_div">
        <label for="wr_link<?php echo $i ?>"><i class="fa fa-link" aria-hidden="true"></i><span class="sound_only"> 링크  #<?php echo $i ?></span></label>
        <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo$write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="frm_input full_input" size="50">
    </div>
    <?php } ?>
    <?php for ($i=10; $is_file && $i<$file_count; $i++) { ?>
    <div class="bo_w_flie write_div">
        <div class="file_wr write_div">
            <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only"> 파일 #<?php echo $i+1 ?></span></label>
            <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
        </div>
        <?php if ($is_file_content) { ?>
        <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="full_input frm_input" size="50" placeholder="파일 설명을 입력해주세요.">
        <?php } ?>

        <?php if($w == 'u' && $file[$i]['file']) { ?>
        <span class="file_del">
            <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
        </span>
        <?php } ?>
        
    </div>
    <?php } ?>


    <?php if ($is_use_captcha) { //자동등록방지  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
    <?php } ?>

    <div class="btn_fixed_top" style="top:57px;">
        <a href="javascript:history.back();" class="btn_cancel btn">취소</a>
        <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>

</section>
<!-- } 게시물 작성/수정 끝 -->


<script>
$("#wr_6").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", minDate: "+0d" });


var g5_user_admin_url = "<?php echo G5_USER_ADMIN_URL;?>";
$(function(){
	// 설비찾기 버튼 클릭
	$("#btn_mms").click(function(e) {
		e.preventDefault();
        // alert('ok');
		var url = g5_user_admin_url+"/mms_select.php?frm=fwrite&file_name=<?php echo $g5['file_name']?>";
		win_mms_select = window.open(url, "win_mms_select", "left=300,top=150,width=550,height=600,scrollbars=1");
        win_mms_select.focus();
	});
    
    // 작업자 검색
    $("#mb_name_worker").click(function() {
        var href = $(this).attr("href");
        memberwin = window.open(href, "memberwin", "left=100,top=100,width=520,height=600,scrollbars=1");
        memberwin.focus();
        return false;
    });
    
});


<?php if($write_min || $write_max) { ?>
// 글자수 제한
var char_min = parseInt(<?php echo $write_min; ?>); // 최소
var char_max = parseInt(<?php echo $write_max; ?>); // 최대
check_byte("wr_content", "char_count");

$(function() {
    $("#wr_content").on("keyup", function() {
        check_byte("wr_content", "char_count");
    });
});

<?php } ?>

    $('.bf_file').on('change',function(){
        //console.log($(this).val());
        var fle = $(this).val();
        if(fle != ''){
            var fleExt = fle.substring(fle.lastIndexOf(".") + 1);
            //php,php3,asp,jsp,cgi,inc,pl,exe
            var reg = /php|php3|asp|jsp|cgi|inc|pl|exe/i; //업로드 불가능 확장자
            if(reg.test(fleExt) == true){
                alert($(this).val()+"파일은 업로드 할 수 없는 파일입니다.\r\n(확장자가 php,php3,asp,jsp,cgi,inc,pl,exe등의 파일은 파일첨부가 불가능합니다.)");
                $(this).val('').focus();
            }
        }
    });

function html_auto_br(obj)
{
    if (obj.checked) {
        result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
        if (result)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}

function fwrite_submit(f)
{
    <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": f.wr_subject.value,
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (subject) {
        alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined")
            ed_wr_content.returnFalse();
        else
            f.wr_content.focus();
        return false;
    }

    if (document.getElementById("char_count")) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(check_byte("wr_content", "char_count"));
            if (char_min > 0 && char_min > cnt) {
                alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                return false;
            }
            else if (char_max > 0 && char_max < cnt) {
                alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }
    }
    
    if(f.com_name.value=='') {
        alert("사이트 찾기를 통해 업체를 선택해 주세요.");
        return false;
    }
    

    <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

</script>
