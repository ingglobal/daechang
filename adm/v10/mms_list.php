<?php
$sub_menu = "940130";
include_once('./_common.php');

auth_check($auth[$sub_menu],"r");

$g5['title'] = '설비(iMMS)관리';
include_once('./_top_menu_mms.php');
include_once('./_head.php');
echo $g5['container_sub_title'];


$sql_common = " FROM {$g5['mms_table']} AS mms
                    LEFT JOIN {$g5['company_table']} AS com ON com.com_idx = mms.com_idx
                    LEFT JOIN {$g5['imp_table']} AS imp ON imp.imp_idx = mms.imp_idx
                    LEFT JOIN {$g5['mms_group_table']} AS mmg ON mmg.mmg_idx = mms.mmg_idx
";

$where = array();
$where[] = " mms_status NOT IN ('trash','delete') ";   // 디폴트 검색조건

// com_idx 조건
$where[] = " mms.com_idx IN (".$_SESSION['ss_com_idx'].") ";


if (isset($stx)&&$stx!='') {
    switch ($sfl) {
		case ( $sfl == 'mms.com_idx' || $sfl == 'mms_idx' ) :
            $where[] = " ({$sfl} = '{$stx}') ";
            break;
		case ($sfl == 'mb_hp') :
            $where[] = " REGEXP_REPLACE(mb_hp,'-','') LIKE '".preg_replace("/-/","",$stx)."' ";
            break;
		case ($sfl == 'mb_id_saler' || $sfl == 'mb_name_saler' ) :
            $where[] = " (mb_id_salers LIKE '%^{$stx}^%') ";
            break;
		case ($sfl == 'mb_name' || $sfl == 'mb_nick' ) :
            $where[] = " ({$sfl} LIKE '{$stx}%') ";
            break;
        default :
            $where[] = " ({$sfl} LIKE '%{$stx}%') ";
            break;
    }
}

// 최종 WHERE 생성
if ($where)
    $sql_search = ' WHERE '.implode(' AND ', $where);


if (!$sst) {
    $sst = "mms_sort";
    $sod = "";
}

if (!$sst2) {
    $sst2 = ", mms_idx";
    $sod2 = "DESC";
}
$sql_order = " ORDER BY {$sst} {$sod} {$sst2} {$sod2} ";

$rows = 200;//$config['cf_page_rows'];
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " SELECT SQL_CALC_FOUND_ROWS DISTINCT *
            , com.com_idx AS com_idx
        {$sql_common}
		{$sql_search}
        {$sql_order}
		LIMIT {$from_record}, {$rows} 
";
// echo $sql;
$result = sql_query($sql,1);
$count = sql_fetch_array( sql_query(" SELECT FOUND_ROWS() as total ") ); 
$total_count = $count['total'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

// arr0:name, arr1:colspan, arr2:rowspan, arr3: sort
$items1 = array(
    "mms_image"=>array("이미지",0,2,0)
    ,"mms_name"=>array("설비명",0,0,1)
    ,"trm_idx_category"=>array("설비분류",0,0,0)
    ,"mms_model"=>array("모델명",0,0,1)
    ,"mms_parts"=>array("부속품수",0,0,0)
    ,"mms_maintain"=>array("정비횟수",0,0,0)
    ,"mms_graph_tag"=>array("태그수",0,0,0)
    ,"mms_idx"=>array("DB고유번호",0,0,1)
    ,"mms_reg_dt"=>array("등록일",0,0,1)
);
$items2 = array(
    "mms_idx2"=>array("관리번호",0,0,1)
    ,"mmg_name"=>array("그룹",0,0,0)
    ,"mms_price"=>array("도입가격",0,0,1)
    ,"mms_install_date"=>array("도입날짜",0,0,1)
    ,"mms_item"=>array("생산기종수",0,0,0)
    ,"imp_name"=>array("IMP명",0,0,0)
    ,"mms_set_output"=>array("생산통계기준",0,0,0)
    ,"mms_status"=>array("상태",0,0,1)
);
$items = array_merge($items1,$items2);
$colspan = 13;
?>
<style>
.tbl_head01 thead.sticky th{position:sticky;top:134px;z-index:1;}
.td_mms_image {width:120px;}
.no_img{display:inline-block;width:100px;height:80px;text-align:center;
line-height:80px;}
.sp_notice{color:yellow;margin-left:10px;}
.sp_notice.sp_error,#sp_ex_notice.sp_error{color:red;}
</style>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총</span><span class="ov_num"> <?php echo number_format($total_count) ?></span></span>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <?php
    $skips = array('mms_idx','mms_status','mms_set_output','mms_image','trm_idx_category','mms_idx2','mms_price','mms_parts','mms_maintain','com_idx','mmg_idx','mms_checks','mms_item', 'mms_graph_tag','mms_reg_dt','mmg_name','mms_install_date');
    if(is_array($items)) {
        foreach($items as $k1 => $v1) {
            if(in_array($k1,$skips)) {continue;}
            echo '<option value="'.$k1.'" '.get_selected($sfl, $k1).'>'.$v1[0].'</option>';
        }
    }
    ?>
    <?php if($member['mb_manager_yn']) { ?>
	<option value="mms.mms_idx"<?php echo get_selected($_GET['sfl'], "mms.mms_idx"); ?>>설비고유번호</option>
	<option value="mms.mms_serial_no"<?php echo get_selected($_GET['sfl'], "mms.mms_serial_no"); ?>>시리얼번호</option>
	<!-- <option value="mms.mmg_idx"<?php echo get_selected($_GET['sfl'], "mms.mmg_idx"); ?>>그룹번호</option>
	<option value="mms.com_idx"<?php echo get_selected($_GET['sfl'], "mms.com_idx"); ?>>업체번호</option> -->
    <?php } ?>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>

<div class="local_desc01 local_desc" style="display:none;">
    <p>프레스, 트랜스퍼, 인덕션히트와 같은 설비장치들(iMMS)들을 관리하는 페이지입니다. 설비를 최대 <?=$g5['setting']['set_imp_count']?>개씩 묶어서 iMP로 관리합니다.</p>
</div>

<form name="form01" id="form01" action="./mms_list_update.php" onsubmit="return form01_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sst2" value="<?php echo $sst2 ?>">
<input type="hidden" name="sod2" value="<?php echo $sod2 ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="w" value="">

<div class="tbl_head01 tbl_wrap">
	<table class="table table-bordered table-condensed">
	<caption><?php echo $g5['title']; ?> 목록</caption>
	<thead class="sticky">
    <!-- 테이블 항목명 1번 라인 -->
	<tr>
		<th scope="col" style="display:<?=(!$member['mb_manager_yn'])?'none':''?>;">
			<label for="chkall" class="sound_only">항목 전체</label>
			<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
		</th>
        <th scope="col" id="th_mms_image">이미지</th>
        <th scope="col" id="th_mms_idx">DB고유번호</th>
        <th scope="col" id="th_mms_idx">시리얼번호</th>
        <th scope="col" id="th_mms_name">설비명</th>
        <th scope="col" id="th_mms_name">모바일접속<br>QR코드</th>
        <th scope="col" id="th_trm_idx_category">설비분류</th>
        <th scope="col" id="th_mms_manual_yn">카운트<br>수동입력여부</th>
        <th scope="col" id="th_mms_call_yn">설비호출<br>상태</th>
        <th scope="col" id="th_mms_item_check_yn">설비생산품질<br>확인여부</th>
        <th scope="col" id="th_mms_testmanual_yn">테스트카운트<br>수동입력여부</th>
        <th scope="col" id="th_mms_sort">순서</th>
		<th scope="col" id="th_list_mng">관리</th>
	</tr>
	</thead>
	<tbody>
    <?php
    $fle_width = 100;
    $fle_height = 80;
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // mms_img 타입중에서 대표 이미지 한개만
        $sql = "SELECT * FROM {$g5['file_table']}
                WHERE fle_db_table = 'mms' AND fle_db_id = '".$row['mms_idx']."'
                    AND fle_type = 'mms_img'
                    AND fle_sort = 0
        ";
        $rs1 = sql_query($sql,1);
        for($j=0;$row1=sql_fetch_array($rs1);$j++) {
//            print_r2($row1);
            $thumb_flag = false;
            if( $row1['fle_name'] && is_file(G5_PATH.$row1['fle_path'].'/'.$row1['fle_name']) ) {
                $thumb_flag = true;
                $row['img'] = $row[$row1['fle_type']][$row1['fle_sort']]; // 변수명 좀 짧게
                $row['img']['thumbnail'] = thumbnail($row1['fle_name'], G5_PATH.$row1['fle_path'], G5_PATH.$row1['fle_path'], $fle_width, $fle_width, false, true, 'center', true, $um_value='85/3.4/15');	// is_create, is_crop, crop_mode
                
            }
            
            $row['img']['thumbnail_img'] = '<a href="./mms_view_popup.php?&mms_idx='.$row['mms_idx'].'" class="btn_image"><img src="'.G5_URL.$row1['fle_path'].'/'.$row['img']['thumbnail'].'" width="'.$fle_width.'" height="'.$fle_height.'"></a>';
        }

        if(!$row['img']['thumbnail_img']){
            $row['img']['thumbnail_img'] = '<span style="display:inline-block;width:'.$fle_width.'px;height:'.$fle_height.'px;line-height:'.$fle_height.'px;border:1px solid #fff;">No Image</span>';
        }

        // 관리 버튼
        $s_mod = '<a href="./mms_form.php?'.$qstr.'&amp;w=u&amp;mms_idx='.$row['mms_idx'].'&amp;ser_mms_type='.$ser_mms_type.'&amp;ser_trm_idx_salesarea='.$ser_trm_idx_salesarea.'">수정</a>';

        $row['trm_idx_category'] = ($row['trm_idx_category'])?$g5['mms_type_name'][$row['trm_idx_category']] : '-';

        $bg = 'bg'.($i%2);
    ?>
    <tr>
        <td class="td_chk" style="display:<?=(!$member['mb_manager_yn'])?'none':''?>;">
            <input type="hidden" name="mms_idx[<?php echo $i ?>]" value="<?php echo $row['mms_idx'] ?>" id="mms_idx_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mms_name']); ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_mms_image">
            <?=$row['img']['thumbnail_img']?>
        </td>
        <td class="td_mms_idx"><?=$row['mms_idx']?></td>
        <td class="td_mms_serial_no"><?=(($row['mms_serial_no'])?$row['mms_serial_no']:'-')?></td>
        <td class="td_mms_name"><?=$row['mms_name']?></td>
        <td class="td_mms_qr">
            <!-- <img src="https://chart.googleapis.com/chart?chs=80x80&cht=qr&chl=<?=G5_USER_ADMIN_MOBILE_URL?>/production_list.php?mms_idx=<?=$row['mms_idx']?>"> -->
            <a href="./mms_view_qr_popup.php?&mms_idx=<?=$row['mms_idx']?>" class="btn_qr">
            <i class="fa fa-qrcode" aria-hidden="true" style="font-size:2em;"></i>
        </td>
        <td class="td_trm_idx_category"><?=$row['trm_idx_category']?></td>
        <td class="td_mms_manual_yn">
            <label for="mms_manual_yn_<?=$i?>" class="sound_only">수동카운트여부</label>
            <input type="checkbox" name="mms_manual_yn[<?=$i?>]" <?=(($row['mms_manual_yn'])?'checked':'')?> value="1" id="mms_manual_yn_<?=$i?>">
        </td>
        <td class="td_mms_call_yn">
            <label for="mms_call_yn_<?=$i?>" class="sound_only">설비호출여부</label>
            <input type="checkbox" name="mms_call_yn[<?=$i?>]" <?=(($row['mms_call_yn'])?'checked':'')?> value="1" id="mms_call_yn_<?=$i?>">
        </td>
        <td class="td_mms_item_check_yn">
            <label for="mms_item_check_yn_<?=$i?>" class="sound_only">설비생산품질확인여부</label>
            <input type="checkbox" name="mms_item_check_yn[<?=$i?>]" <?=(($row['mms_item_check_yn'])?'checked':'')?> value="1" id="mms_item_check_yn_<?=$i?>">
        </td>
        <td class="td_mms_testmanual_yn">
            <label for="mms_testmanual_yn_<?=$i?>" class="sound_only">테스트카운트입력여부</label>
            <input type="checkbox" name="mms_testmanual_yn[<?=$i?>]" <?=(($row['mms_testmanual_yn'])?'checked':'')?> value="1" id="mms_testmanual_yn_<?=$i?>">
        </td>
        <td class="td_mms_sort">
            <input type="text" name="mms_sort[<?=$i?>]" value="<?=number_format($row['mms_sort'])?>" onclick="javascript:numtoprice(this)" class="frm_input mmms_sort wg_wdx60 wg_right">
        </td>
        <td class="td_list_mng"><?=$s_mod?></td>
    </tr>
    <?php
    }
	if ($i == 0)
		echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<script>

</script>
<div class="btn_fixed_top">
    <?php ;//if($member['mb_manager_yn']) { ?>
    <?php if(!auth_check($auth[$sub_menu],'w',1)) { ?>
        <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn_02 btn">
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
        <a href="./mms_form.php" id="btn_add" class="btn_01 btn">추가하기</a>
    <?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;ser_mms_type='.$ser_mms_type.'&amp;page='); ?>

<script>
$(function(e) {
    // 마우스 hover 설정
    $(".tbl_head01 tbody tr").on({
        mouseenter: function () {
            //stuff to do on mouse enter
            //console.log($(this).attr('od_id')+' mouseenter');
            //$(this).find('td').css('background','red');
            $('tr[tr_id='+$(this).attr('tr_id')+']').find('td').css('background','#232323 ');
            
        },
        mouseleave: function () {
            //stuff to do on mouse leave
            //console.log($(this).attr('od_id')+' mouseleave');
            //$(this).find('td').css('background','unset');
            $('tr[tr_id='+$(this).attr('tr_id')+']').find('td').css('background','unset');
        }    
    });

    // 장비보기 클릭
	$(document).on('click','.btn_view, .btn_image',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winMMSView = window.open(href, "winMMSView", "left=100,top=100,width=700,height=450,scrollbars=1");
        winMMSView.focus();
        return false;
    });

    // 모바일접속 QR코드보기 클릭
	$(document).on('click','.btn_view, .btn_qr',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winMMSView = window.open(href, "winMMSView", "left=100,top=100,width=700,height=1000,scrollbars=1");
        winMMSView.focus();
        return false;
    });

    // 부속품 클릭
	$(document).on('click','.btn_parts',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winParts = window.open(href, "winParts", "left=100,top=100,width=520,height=600,scrollbars=1");
        winParts.focus();
        return false;
    });

    // 태그수
	$(document).on('click','.btn_graph_tag',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winGraphTag = window.open(href, "winGraphTag", "left=100,top=100,width=520,height=600,scrollbars=1");
        winGraphTag.focus();
        return false;
    });

    // 기종 클릭
	$(document).on('click','.btn_item',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winItem = window.open(href, "winItem", "left=100,top=100,width=520,height=600,scrollbars=1");
        winItem.focus();
        return false;
    });

    // 정비 클릭
	$(document).on('click','.btn_maintain',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winMaintain = window.open(href, "winMaintain", "left=100,top=100,width=520,height=600,scrollbars=1");
        winMaintain.focus();
        return false;
    });

    // 점검기준 클릭
	$(document).on('click','.btn_checks',function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        winChecks = window.open(href, "winChecks", "left=100,top=100,width=520,height=600,scrollbars=1");
        winChecks.focus();
        return false;
    });

    // 담당자 클릭
    $(".btn_manager").click(function(e) {
        var href = "./mms_member_list.php?mms_idx="+$(this).attr('mms_idx');
        winCompanyMember = window.open(href, "winCompanyMember", "left=100,top=100,width=520,height=600,scrollbars=1");
        winCompanyMember.focus();
        return false;
    });

	// 코멘트 클릭 - 모달
	$(document).on('click','.btn_mms_comment',function(e){
        e.preventDefault();
        var this_href = $(this).attr('href');
        //alert(this_href);
        win_mms_board = window.open(this_href,'win_mms_board','left=100,top=100,width=770,height=650');
        win_mms_board.focus();
	});
	
});

function form01_submit(f)
{
	if(document.pressed == "테스트입력") {
		window.open('<?=G5_URL?>/device/code/form.php');
        return false;
	}

    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	if(document.pressed == "선택수정") {
		$('input[name="w"]').val('u');
	}
	if(document.pressed == "선택삭제") {
		if (!confirm("선택한 항목(들)을 정말 삭제 하시겠습니까?\n복구가 어려우니 신중하게 결정 하십시오.")) {
			return false;
		}
		else {
			$('input[name="w"]').val('d');
		} 
	}
    return true;
}


</script>

<?php
include_once ('./_tail.php');
?>