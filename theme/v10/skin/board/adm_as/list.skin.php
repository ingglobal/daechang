<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once($board_skin_path.'/_common.php');

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 11;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style2.css">', 0);

$listall = '<a href="'.$board_adm_basic_url.'" class="ov_listall">전체목록</a>';
?>
<!-- 게시판 페이지 정보 및 버튼 시작 { -->
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총</span><span class="ov_num"> <?php echo number_format($total_count) ?></span>건,&nbsp;&nbsp;&nbsp;<?php echo $page ?> 페이지</span>
</div>
<!-- } 게시판 페이지 정보 및 버튼 끝 -->
<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>">

    <!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>
    <!-- } 게시판 카테고리 끝 -->

    <!-- 게시판 검색 시작 { -->
    <fieldset id="bo_sch">
    <legend>게시물 검색</legend>
    <form name="fsearch" method="get">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sop" value="and">
        <label for="sfl" class="sound_only">검색대상</label>
        
        <div class="bo_sch_group">
            <?php if(false){?>
            <select name="ser_wr_10" id="ser_wr_10" style="display:no ne;">
                <option value="">상태값전체 &nbsp;</option>
                <?php echo $board['bo_10_key_options'];?>
            </select>
            <script>$('#ser_wr_10').val('<?php echo $ser_wr_10;?>').attr('selected','selected');</script>
            <?php } ?>

            <select name="sfl" id="sfl">
                <option value="wr_subject"<?php echo get_selected($sfl, 'wr_subject', true); ?>>업체명</option>
                <option value="wr_5"<?php echo get_selected($sfl, 'wr_5', true); ?>>담당자명</option>
                <option value="wr_content"<?php echo get_selected($sfl, 'wr_content', true); ?>>업체주소</option>
                <option value="mms_name"<?php echo get_selected($sfl, 'mms_name', true); ?>>설비명</option>
                <option value="wr_2"<?php echo get_selected($sfl, 'wr_2', true); ?>>설비번호</option>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" class="sch_input" size="25" maxlength="20" placeholder="검색어를 입력해주세요">
            <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
        <div>
    </form>
    </fieldset>
    <!-- } 게시판 검색 끝 -->

    <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $board['bo_subject'] ?> 목록</caption>
        <thead>
        <tr>
            <?php if ($is_checkbox) { ?>
            <th scope="col">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
                <input type="hidden" name="chk[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_<?php echo $i; ?>">
            </th>
            <?php } ?>
            <th scope="col">번호</th>
            <th scope="col" style="width:180px;">설비명</th>
            <th scope="col">업체명</th>
            <th scope="col">주소</th>
            <th scope="col">담당자명</th>
            <th scope="col">전화번호</th>
            <th scope="col">이메일</th>
            <th scope="col" style="width:110px;">등록자</th>
            <th scope="col" style="width:50px;">수정</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
            // print_r2($list[$i]);
            $mb1 = get_member($list[$i]['mb_id'],'mb_name');

        ?>
        <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?> status_<?php echo $list[$i]['wr_10'];?>">
            <?php if ($is_checkbox) { ?>
            <td class="td_chk">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            </td>
            <?php } ?>
            <td class="td_num2"><!-- 번호 -->
            <?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong class="notice_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i><span class="sound_only">공지</span></strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\">열람중</span>";
            else
                echo $list[$i]['num'];
             ?>
            </td>
            <td class="td_mms_name"><?=$list[$i]['mms_name']?></td><!-- 설비명 -->
            <!-- 회사명 -->
            <td class="td_center">
                <?php echo $list[$i]['subject'] ?>
            </td>
            <td class="td_as_addr" style="text-align:left;"><?=$list[$i]['wr_content']?></td><!-- 주소 -->
            <td class="td_as_name"><?=$list[$i]['wr_5']?></td><!-- 담당자명 -->
            <td class="td_mms_name"><?=$list[$i]['wr_6']?></td><!-- 전화번호 -->
            <td class="td_email"><?=$list[$i]['wr_7']?></td><!-- 이메일 -->
            <td class="td_status"><?=$mb1['mb_name']?></td><!-- 등록자 -->
            <td class="td_modify sv_use"><!-- 수정 -->
                <a href="<?php echo G5_USER_ADMIN_BBS_URL?>/write.php?w=u&bo_table=<?php echo $bo_table?>&wr_id=<?php echo $list[$i]['wr_id']?>&<?php echo $qstr?>">수정</a>
            </td>
        </tr>
        <?php } ?>
        <?php if (count($list) == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>

    <?php if ($list_href || $is_checkbox || $write_href) { ?>
    <div class="btn_fixed_top" style="top:57px;">
        <?php if ($list_href || $write_href) { ?>
        <ul class="btn_bo_user">
            <?php if ($is_checkbox) { ?>
            <li style="display:none;"><button type="submit" name="btn_submit" value="선택수정" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-edit" aria-hidden="true"></i> 선택수정</button></li>
            <?php if ($admin_href) { ?><li style="display:<?php if(auth_check($auth[$sub_menu],"d",1)) echo 'none';?>"><a href="<?php echo $admin_href ?>" class="btn_admin btn"><i class="fa fa-user-circle" aria-hidden="true"></i> 관리자</a></li><?php } ?>
            <li style="display:<?php if(auth_check($auth[$sub_menu],"d",1)) echo 'none';?>"><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-trash-o" aria-hidden="true"></i> 선택삭제</button></li>
            <?php if(false){ ?>
            <li style="display:<?php if(auth_check($auth[$sub_menu],"d",1)) echo 'none';?>"><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-files-o" aria-hidden="true"></i> 선택복사</button></li>
            <li style="display:<?php if(auth_check($auth[$sub_menu],"d",1)) echo 'none';?>"><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-arrows" aria-hidden="true"></i> 선택이동</button></li>
            <?php } ?>
            <?php } ?>
            <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01 btn"><i class="fa fa-list" aria-hidden="true"></i> 목록</a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 등록</a></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <?php } ?>

    </form>
     
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $write_pages;  ?>


<script>
// 상단 제목 수정
$('#container_title').text('<?php echo $board['bo_subject']?>');

$("#fr_date, #to_date, #pl_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });

$(document).on('click','.btn_company',function(e){
    e.preventDefault();
    var href = "<?=G5_USER_ADMIN_URL?>/company_select.popup.php?file_name=list.skin";
    winCompany = window.open(href, "winCompany", "left=70,top=70,width=520,height=600,scrollbars=1");
    winCompany.focus();
    return false;
})
</script>


<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }
    if(document.pressed == "선택수정") {
        if (!confirm("선택한 게시물을 수정하시겠습니까?"))
            return false;

        f.removeAttribute("target");
        f.action = "<?php echo $board_skin_url?>/list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
