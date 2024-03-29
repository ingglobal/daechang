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
    <div class="write_div">
        <select name="wr_7" id="wr_7">
            <option value="0">분류를 선택하세요.</option>
            <?=$mms_type_form_options?>
        </select>
        <script>$('select[name="wr_7"]').val('<?=$wr_7?>');</script>
    </div>
    <div class="write_div">
        <label for="wr_cart" class="sound_only">설비</label>
        <input type="hidden" name="com_idx" value="<?=(($w == '')?$_SESSION['ss_com_idx']:$write['wr_1'])?>"><!-- 업체번호 -->
        <input type="hidden" name="com_name" value="<?=$write['com_name']?>"><!-- 업체명 -->
        
        <select name="mms_idx" id="mms_idx">
            <option value="">설비선택</option>
            <?php
            // 해당 범위 안의 모든 설비를 select option으로 만들어서 선택할 수 있도록 한다.
            // Get all the mms_idx values to make them optionf for selection.
            $sql2 = "SELECT mms_idx, mms_name
                    FROM {$g5['mms_table']}
                    WHERE com_idx = '".$_SESSION['ss_com_idx']."'
                    ORDER BY mms_idx       
            ";
            // echo $sql2.'<br>';
            $result2 = sql_query($sql2,1);
            for ($i=0; $row2=sql_fetch_array($result2); $i++) {
                // print_r2($row2);
                echo '<option value="'.$row2['mms_idx'].'" '.get_selected($mms_idx, $row2['mms_idx']).'>'.$row2['mms_name'].'</option>';
            }
            ?>
        </select>
        <script>$('select[name=mms_idx]').val("<?=$write['mms_idx']?>").attr('selected','selected');</script>        
    </div>

    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= "\n".'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'."\n".'<label for="notice">공지</label>';
        }

        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= "\n".'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="html">HTML</label>';
            }
        }

        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= "\n".'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'."\n".'<label for="secret">비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }

        if ($is_mail) {
            $option .= "\n".'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'."\n".'<label for="mail">답변메일받기</label>';
        }
    }

    echo $option_hidden;
    ?>

    <?php if ($is_category) { ?>
    <div class="bo_w_select write_div">
        <label for="ca_name"  class="sound_only">분류<strong>필수</strong></label>
        <select name="ca_name" id="ca_name" required>
            <option value="">분류를 선택하세요</option>
            <?php echo $category_option ?>
        </select>
    </div>
    <?php } ?>

    <div class="bo_w_info write_div" style="display:none;">
    <?php if ($is_name) { ?>
        <label for="wr_name" class="sound_only">이름<strong>필수</strong></label>
        <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required" placeholder="이름">
    <?php } ?>

    <?php if ($is_password) { ?>
        <label for="wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
        <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input <?php echo $password_required ?>" placeholder="비밀번호">
    <?php } ?>

    <?php if ($is_email) { ?>
            <label for="wr_email" class="sound_only">이메일</label>
            <input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input email " placeholder="이메일">
    <?php } ?>
    </div>

    <?php if ($is_homepage) { ?>
    <div class="write_div" style="display:none;">
        <label for="wr_homepage" class="sound_only">홈페이지</label>
        <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="frm_input full_input" size="50" placeholder="홈페이지">
    </div>
    <?php } ?>

    <?php if ($option) { ?>
    <div class="write_div" style="display:none;">
        <span class="sound_only">옵션</span>
        <?php echo $option ?>
    </div>
    <?php } ?>

    <div class="bo_w_tit write_div">
        <label for="wr_subject" class="sound_only">제목<strong>필수</strong></label>
        <div id="autosave_wrapper write_div">
            <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required" maxlength="255" placeholder="제목">
        </div>
    </div>
    <!-- 정비일, 며칠전부터, 반복주기, 몇시에 -->
    <div class="write_div">
        <input type="text" name="wr_3" value="<?=$write['wr_3']?>" id="wr_3" required class="frm_input required" style="width:90px;" placeholder="정비일자">
        기준 &nbsp;&nbsp;&nbsp;
        <input type="text" name="wr_4" value="<?=$write['wr_4']?>" id="wr_4" required class="frm_input required" style="width:40px;text-align:right;" placeholder="숫자" onclick="javascript:chk_Number(this)">
        일 전부터 &nbsp;
        <select name="wr_5" id="wr_5">
            <option value="">알림주기선택</option>
            <?php echo $board['bo_8_val_options'];?>
        </select>
        <script>$('#wr_5').val('<?=$write['wr_5']?>').attr('selected','selected');</script>
        <select name="wr_6" id="wr_6">
            <option value="">발송시간선택</option>
            <?php echo $board['bo_9_val_options'];?>
        </select>
        <script>$('#wr_6').val('<?=$write['wr_6']?>').attr('selected','selected');</script>
        에 발송
    </div>

    <div class="write_div">
        <label for="wr_content" class="sound_only">내용<strong>필수</strong></label>
        <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </div>
        
    </div>
    <div class="write_div towhom_wrapper">
        알림대상 설정
        <div class="towhom_form">
            <input type="text" name="mb_name" class="frm_input" style="width:100px;" placeholder="이름">
            <input type="text" name="mb_role" class="frm_input" style="width:80px;" placeholder="직책">
            <input type="text" name="mb_hp" class="frm_input" style="width:120px;" placeholder="휴대폰" onKeyUp="chk_Number(this);">
            <input type="email" name="mb_email" class="frm_input" style="width:200px;" placeholder="이메일">
            <a href="javascript:" class="btn btn_02 btn_mb_report" style="position:relative;">추가</a>
        </div>
        <div class="towhom_info">
            <ul>
                <?php
                for($i=0;$i<@sizeof($towhom_li);$i++) {
                    echo '<li>
                            <span><i class="fa fa-remove"></i></span>
                            <span class="r_name">'.$towhom_li[$i]['r_name'].'</span>
                            <span class="r_role">'.$towhom_li[$i]['r_role'].'</span>
                            <span class="r_hp">'.$towhom_li[$i]['r_hp'].'</span>
                            <span class="r_email">'.$towhom_li[$i]['r_email'].'</span>
                          </li>
                    ';
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="bo_w_tit write_div">
        <label for="wr_subject" class="sound_only">메시지발송설정</label>
        <div id="autosave_wrapper write_div">
        <?php
            $ar['prefix'] = 'wr';
            $ar['com_idx'] = $write['wr_1'];
            $ar['value'] = $write['wr_send_type'];
            echo set_send_type($ar);
            unset($ar);
			?>
        </div>
    </div>

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

    <div class="bo_user write_div">
        <label for="wr_10" class="sound_only">상태</label>
        <select name="wr_10" id="wr_10" class="frm_input">
            <?php echo $board['bo_10_key_options'] ?>
        </select>
        <script>
            <?php if($w == ''){ ?>
            // alert($('select[name=wr_10]').find('option').eq(0).val());
            $('select[name=wr_10]').val($('select[name=wr_10]').find('option').eq(0).val());
            <?php } else { ?>
            $('select[name=wr_10]').val('<?php echo $write['wr_10'] ?>').attr('selected','selected');
            <?php } ?>
            $('select[name=wr_10]').css('margin-right','0');
        </script>

    </div>

    <div class="btn_fixed_top" style="top:57px;">
        <a href="javascript:history.back();" class="btn_cancel btn">취소</a>
        <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit btn">
    </div>
    </form>

</section>
<!-- } 게시물 작성/수정 끝 -->


<script>
$("#wr_3").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", minDate: "+0d" });

function chk_Number(object){
    $(object).keyup(function(){
        $(this).val($(this).val().replace(/[^0-9|-]/g,""));
    });   
}

var g5_user_admin_url = "<?php echo G5_USER_ADMIN_URL;?>";
$(function(){
	// 설비찾기 버튼 클릭
	$("#btn_mms").click(function(e) {
		e.preventDefault();
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

    // 삭제하기
    $(document).on('click','.btn_delete',function(e){
        e.preventDefault();
        if(confirm("게시물을 정말 삭제하시겠습니까?")) {
            self.location = $(this).attr("href");
        }
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
    
    if(f.mms_idx.value=='') {
        alert("설비를 입력하세요.");
        return false;
    }


    $('.towhom_info span[class^="r_"]').each(function(e){
        // console.log( $(this).html() );
        var this_val = $(this).text();
        var this_name = $(this).attr('class');
        $(this).append( $('<input type="hidden" name="'+this_name+'[]" value="'+this_val+'">') );
    });
    

    <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
    // return false;
}

// wr_content height setting
$('#wr_content').css('height','100px');

// 알림대상 추가
$(document).on('click','.btn_mb_report',function(e){
    e.preventDefault();
    var mb_name = $('.towhom_form').find('input[name=mb_name]').val();
    var mb_role = $('.towhom_form').find('input[name=mb_role]').val();
    var mb_hp = $('.towhom_form').find('input[name=mb_hp]').val();
    var mb_email = $('.towhom_form').find('input[name=mb_email]').val();
    if(mb_name=='') {
        alert('이름을 입력하세요.');
        return false;
    }
    if(mb_role=='') {
        alert('직책을 입력하세요.');
        return false;
    }
    if(mb_hp==''&&mb_email=='') {
        alert('휴대폰 또는 이메일 중 하나는 입력해 주셔야 합니다.');
        return false;
    }
    if(mb_hp!='') {
        var rgEx = /(01[016789])[-]*(\d{4}|\d{3})[-]*\d{4}$/g;
        var chkFlg = rgEx.test(mb_hp);
        if(!chkFlg){
            alert("올바른 휴대폰번호 형식이 아닙니다.");
            return false; 
        }
    }
    if(mb_email!='') {
        // 검증에 사용할 정규식
        var regExp = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
        if (mb_email.match(regExp) != null) {
            // alert('Good!');
        }
        else {
            alert("올바른 이메일 주소가 아닙니다.");
            return false; 
        }
    }

    mb_dom = '<li>';
    mb_dom += ' <span><i class="fa fa-remove"></i></span>';
    mb_dom += ' <span class="r_name">'+mb_name+'</span>';
    mb_dom += ' <span class="r_role">'+mb_role+'</span>';
    mb_dom += ' <span class="r_hp">'+mb_hp+'</span>';
    mb_dom += ' <span class="r_email">'+mb_email+'</span>';
    mb_dom += '</li>';
    $('.towhom_info ul').append(mb_dom);
    $('.towhom_form input').val('');

});
// Enterkey action intercept
// $(document).on('keyup','input[name=mb_name], input[name=mb_role], input[name=mb_hp], input[name=mb_email]',function(e){
// $(document).on('keyup','input[name=mb_name]',function(e){
//     $('#fwrite').attr('onsubmit','return false;');
//     if(e.keyCode == 13 && $(this).val() ) {
//         $('.btn_mb_report').trigger('click');
//         $('#fwrite').attr('onsubmit','return true;');
//     }
// });
// report people remove 
$(document).on('click','.towhom_info .fa',function(e){
    $(this).closest('li').slideUp().remove();
});

</script>
