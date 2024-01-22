<?php
$sub_menu = "922110";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');


foreach($_REQUEST as $key => $value ) {
    if(substr($key,0,4)=='ser_') {
        if(is_array($value)) {
            foreach($value as $k2 => $v2 ) {
                $qstr .= '&'.$key.'[]='.$v2;
                $form_input .= '<input type="hidden" name="'.$key.'[]" value="'.$v2.'" class="frm_input">'.PHP_EOL;
            }
        }
        else {
            $qstr .= '&'.$key.'='.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value);
            $form_input .= '<input type="hidden" name="'.$key.'" value="'.(($key == 'ser_stx')?urlencode(cut_str($value, 40, '')):$value).'" class="frm_input">'.PHP_EOL;
        }
    }
}


if ($w == '') {

}
else if ($w == 'u') {

    $prm = get_table('production_main','prm_idx',$prm_idx);
    if($prm['prm_status'] == 'trash' || !$prm['prm_idx']){
        alert('해당 생산계획이 존재하지 않습니다.','./production_list.php?'.$qstr);
    }
    // print_r3($prd);//exit;
    $bom = get_table('bom','bom_idx',$prm['bom_idx']);
    

}
// print_r2($pri);exit;

// if(!$prm['prm_order_no']) {
//     // $tdcode = preg_replace('/[ :-]*/','',G5_TIME_YMDHIS);
//     // $prm_order_no = "PRD-".strtoupper(wdg_uniqid());
//     $tdcode = preg_replace('/[ :-]*/','',$d[$k]);
//     $prm_order_no = "PRD-".$tdcode.wdg_get_random_string('09',10);
//     $prm['prm_order_no'] = $prm_order_no;
// }

$html_title = ($w=='')?'추가':'수정';
$html_title = ($w=='c')?'복제':$html_title;
$g5['title'] = '생산계획 '.$html_title;

$readonly = ' readonly';
$required = ' required';

include_once('./_head.php');
?>
<style>
.btn_del {background-color:#5e2902 !important;}
</style>
<form name="form01" id="form01" action="./<?=$g5['file_name']?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" >
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="prm_idx" value="<?php echo $prm["prm_idx"] ?>">
<?=$form_input?>
<div class="local_desc01 local_desc" style="display:no ne;">
    <p>'지시량' 항목은 수정 불가능합니다.(최초 등록 시에만 가능함) 하단 리스트중에서 최상위 완제품의 지시수량을 변경해 주시면 자동으로 계산됩니다.</p>
    <p>상단의 [초기화] 버튼을 클릭하면 완제품 기준으로 모든 정보가 초기화됩니다. 초기 상태에서 새롭게 설정하시면 됩니다.</p>
    <p>설비별 작업자 정보 또는 생산 제품 정보가 추가되거나 변경된 경우 관련 설정 정보가 제대로 안 보일 수 있습니니다. 그럴 경우는 [초기화] 버튼을 클릭하시고 다시 설정해 주세요.</p>
</div>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?></caption>
	<colgroup>
		<col class="grid_4" style="width:10%;">
		<col style="width:40%;">
		<col class="grid_4" style="width:13%;">
		<col style="width:37%;">
	</colgroup>
	<tbody>
        <tr>
            <th scope="row">품명/품번</th>
            <td>
                <input type="hidden" name="bom_idx" id="bom_idx" value="<?=$prm['bom_idx']?>">
                <input type="hidden" name="boc_idx" id="boc_idx" value="<?=$prm['boc_idx']?>">
                <input type="text" name="bom_name" value="<?=$bom['bom_name']?>" class="frm_input required readonly" required readonly style="width:300px;">&nbsp;
                <span class="span_bom_part_no font_size_8"><?=$bom['bom_part_no']?></span>
                <?php if($w == ''){ ?>
                    <a href="./bom_select.php?file_name=<?=$g5['file_name']?>" class="btn btn_02" id="btn_bom">찾기</a>
                    <button type="button" class="btn btn_04 bom_cancel">취소</button>
                <?php } ?>
            </td>
            <th scope="row">수주(수주일/ID)</th>
            <td>
                <?php
                $prd = get_table('production','prd_idx',$prm['prd_idx']);
                ?>
                <input type="text" name="prd_date" id="prd_date" placeholder="수주날짜" value="<?=$prd['prd_date']?>"<?=$readonly?> class="frm_input<?=$readonly?>" style="width:90px;"> /
                <input type="text" name="prd_idx" id="prd_idx" placeholder="수주ID" value="<?=$prd['prd_idx']?>"<?=$readonly?> class="frm_input<?=$readonly?>" style="width:80px;">
                <?php if($w == ''){ ?>
                <a href="<?=G5_USER_ADMIN_WIN_URL?>/ordprd_select.php?file_name=<?=$g5['file_name']?>" class="btn btn_02" id="btn_prd">찾기</a>
                <button type="button" class="btn btn_04 prd_cancel">취소</button>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">지시코드</th>
            <td>
                <input type="text" name="prm_order_no" value="<?=$prm['prm_order_no']?>" class="frm_input readonly" readonly style="width:400px;">
            </td>
            <th scope="row">지시량</th>
            <td>
                <input type="text" name="prm_value" value="<?=number_format($prm['prm_value'])?>" id="prm_value" onclick="javascript:numtoprice(this);" class="frm_input" style="text-align:right;width:90px;">
                <span>(완제품기준)</span>
            </td>
        </tr>
        <tr>
            <th scope="row">생산일</th>
            <td>
                <input type="text" name="prm_date" id="prm_date" value="<?=(($prm['prm_date'] && $prm['prm_date'] != '0000-00-00')?$prm['prm_date']:'')?>" readonly class="readonly tbl_input" style="width:90px;background:#333 !important;text-align:center;">
            </td>
            <th scope="row">상태</th>
            <td>
                <select name="prm_status" id="prm_status">
                    <?=$g5['set_prm_status_options']?>
                </select>
                <script>
                $('#prm_status').val('<?=(($w=='')?'confirm':$prm['prm_status'])?>');   
                </script>
            </td>
        </tr>
        <tr>
            <th scope="row">메모</th>
            <td colspan="3">
                <input type="text" name="prm_memo" id="prm_memo" class="frm_input" value="<?=$prm['prm_memo']?>" style="width:100%;">
            </td>
        </tr>
    </tbody>
    </table>
    <!--########### BOM 구조목록 : 시작 ###########-->
    <style>
    .th_mb_id,.td_mb_id{width:70px;}
    .th_btns,.td_btns{width:0 !important;}
    .tbl_head01 thead tr th {
        white-space: normal;
    }
    </style>

    <div class="tbl_head01" style="padding-top:20px;">
        <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
        <tr>
            <th class="th_bom_name">품번/품명</th>
            <th class="th_mms_mb" style="width:60px;">제품-설비-작업자</th>
            <th class="th_mms_idx">설비</th>
            <th class="th_mb_id">작업자</th>
            <th class="th_pri_value">지시량</th>
            <th class="th_pri_status">작업상태</th>
            <th class="th_btns">계획관리</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $bom_prev2 = 0;
        //BOM 구조를 따라서 계층구조 추출
        // 맨 처음 항목 추출을 위해서 UNION ALL
        $sql1 = " SELECT * FROM (
                    ( SELECT bom.bom_idx
                            , bom.bom_name
                            , bom_part_no
                            , bom_type
                            , bom_price
                            , bom_status
                            , 'MIP' AS cst_name
                            , 0 AS bit_idx
                            , 0 AS bom_idx_parent
                            , 0 AS bit_main_yn
                            , 0 AS bom_idx_child
                            , '' AS bit_reply
                            , bom_usage AS bit_count
                        FROM {$g5['bom_table']} bom
                            LEFT JOIN {$g5['bom_customer_table']} boc ON bom.bom_idx = boc.bom_idx AND boc.boc_type = 'customer'
                        WHERE bom.bom_idx = '{$prm['bom_idx']}'
                    )
                    UNION ALL
                    ( SELECT bom.bom_idx
                            , bom.bom_name
                            , bom_part_no
                            , bom_type
                            , bom_price
                            , bom_status
                            , cst_name
                            , bot.bit_idx
                            , bot.bom_idx AS bom_idx_parent
                            , bot.bit_main_yn
                            , bot.bom_idx_child
                            , bot.bit_reply
                            , bot.bit_count
                        FROM {$g5['bom_item_table']} bot
                            LEFT JOIN {$g5['bom_table']} bom ON bot.bom_idx_child = bom.bom_idx
                            LEFT JOIN {$g5['bom_customer_table']} boc ON bom.bom_idx = boc.bom_idx AND boc.boc_type = 'provider'
                            LEFT JOIN {$g5['customer_table']} cst ON boc.cst_idx = cst.cst_idx
                        WHERE bot.bom_idx = '{$prm['bom_idx']}'
                        ORDER BY bot.bit_reply
                    )
        ) db1
        ORDER BY bit_reply
        ";
        // echo $sql1.BR;
        $rs1 = sql_query($sql1,1);
        $row['rows'] = sql_num_rows($rs1);
        $row['rows_text'] = $row['rows'] ? '<span class="font_size_8 ml_10">(구성품수: '.$row['rows'].')</span>' : '';
        for ($i=0; $row1=sql_fetch_array($rs1); $i++) {
            $row1['bit_main_class'] = $row1['bit_main_yn'] ? 'bit_main' : ''; // 대표제품 색상
            $len = strlen($row1['bit_reply'])/2+1;
            $row1['len'] = '<span class="btn_number">'.$len.'</span>';
            for ($k=1; $k<$len; $k++) { $row1['nbsp'] .= '&nbsp;&nbsp;&nbsp;'; } // 들여쓰기공백

            // 설비별 작업자 연결 정보 추출 ---------------------------------------------------------------
            $sql2 = " SELECT bmw_idx
                            , bmw.mms_idx AS mms_idx
                            , mms_name
                            , bmw.mb_id AS mb_id
                            , mb_name
                            , bmw_type, bmw_sort
                        FROM {$g5['bom_mms_worker_table']} AS bmw
                            LEFT JOIN {$g5['mms_table']} AS mms ON mms.mms_idx = bmw.mms_idx
                            LEFT JOIN {$g5['member_table']} AS mb ON mb.mb_id = bmw.mb_id
                        WHERE bmw_status NOT IN ('trash','delete') AND bom_idx = '".$row1['bom_idx']."'
            ";
            $rs2 = sql_query($sql2,1);
            $row1['bmw_rows'] = sql_num_rows($rs2); // 몇 개
            // echo $row1['bmw_rows'];
            for ($k=0; $row2=sql_fetch_array($rs2); $k++) {
                // print_r2($row2);
                $row1['mms_option_arr'][$k]['mms_idx'] = $row2['mms_idx'];
                $row1['mms_option_arr'][$k]['mms_name'] = $row2['mms_name'];
                $row1['mb_id_arr'][$k]['mb_id'] = $row2['mb_id'];
                $row1['mb_id_arr'][$k]['mb_name'] = $row2['mb_name'];
                $row1['mb_id_arr'][$k]['bmw_type'] = $row2['bmw_type'];
            }
            // 설비 중복 제거하고 하나씩만 표현
            $row1['mms_options_ar'] = @array_unique($row1['mms_option_arr'],SORT_REGULAR);
            $row1['mms_options_ar'] = @array_values(array_filter($row1['mms_options_ar']));
            // print_r2($row1['mms_options_ar']);
            for ($y=0; $y<@sizeof($row1['mms_options_ar']); $y++) {
                // print_r2($row1['mms_options_ar'][$y]);
                $row1['mms_options'] .= '<option value="'.$row1['mms_options_ar'][$y]['mms_idx'].'">'.$row1['mms_options_ar'][$y]['mms_name'].'</option>';
            }
            // 작업자 중복 제거하고 하나씩만 표현
            $row1['mb_ids_ar'] = @array_unique($row1['mb_id_arr'],SORT_REGULAR);
            $row1['mb_ids_ar'] = @array_values(array_filter($row1['mb_ids_ar']));
            // print_r2($row1['mb_ids_ar']);
            for ($y=0; $y<@sizeof($row1['mb_ids_ar']); $y++) {
                // print_r2($row1['mb_ids_ar'][$y]);
                $row1['mb_ids_ar'][$y]['mb_type'] = $g5['set_bmw_type_value'][$row1['mb_ids_ar'][$y]['bmw_type']];
                $row1['mb_id_options'] .= '<option value="'.$row1['mb_ids_ar'][$y]['mb_id'].'">'.$row1['mb_ids_ar'][$y]['mb_name'].'('.$row1['mb_ids_ar'][$y]['mb_type'].')</option>';
            }
            
            // 설비별 작업자 할당 정보 추출 ---------------------------------------------------------------
            $sql3 = "   SELECT *
                        FROM {$g5['production_item_table']} AS pri
                            LEFT JOIN {$g5['bom_table']} AS bom USING(bom_idx)
                        WHERE prm_idx = '".$prm['prm_idx']."' 
                            AND pri.bom_idx = '".$row1['bom_idx']."' 
                            AND bom.bom_type IN ('product','half','material')
                        ORDER BY pri_idx
            ";
            $rs3 = sql_query($sql3,1);
            for ($x=0; $row3=sql_fetch_array($rs3); $x++) {
                // print_r2($row3);
                $ix = (int)$i.$x;
                // 복제, 삭제 버튼
                $s_copy = '<a href="javascript:" bom_idx="'.$row3['bom_idx'].'" class="btn btn_05 btn_copy">복제</a>';
                $s_edit = '<a href="'.G5_USER_ADMIN_WIN_URL.'/bom_mms_mb_edit.php?file_name='.$g5['file_name'].'&bom_idx='.$row3['bom_idx'].'" bom_idx="'.$row3['bom_idx'].'" class="btn btn_03 btn_edit">편집</a>';
                $s_del = '<a href="javascript:" class="btn btn_05 btn_del">삭제</a>';

                // // bom_usage 임의 변경(테스트)
                // $row3['bom_usage'] = ($row3['bom_idx']==55)? 2:$row3['bom_usage'];               
                ?>
                <tr bom_idx="<?=$row3['bom_idx']?>" bom_part_no="<?=$row1['bom_part_no']?>" bom_usage="<?=$row3['bom_usage']?>" pri_idx="<?=$row3['pri_idx']?>" len="<?=$len?>">
                    <td class="td_bom_name" style="padding-left:<?=$padding_left?>px;">
                        <input type="hidden" name="chk[<?=$ix?>]" value="<?=$row3['pri_idx']?>">
                        <input type="hidden" name="pri_idxs[<?=$ix?>]" value="<?=$row3['pri_idx']?>">
                        <span class="nbsp" style="visibility:<?=($bom_prev==$row3['bom_idx'])?'hidden':''?>;"><?=$row1['nbsp']?><?=$row1['len']?></span>
                        <?php if($bom_prev != $row3['bom_idx']) { // 이전 bom과 같으면 품번/품명 숨김 ?>
                            <span class="font_size_7 <?=$row1['bit_main_class']?>"><?=$row1['bom_part_no']?></span>
                            <?=$row1['bom_name']?>
                            <span class="span_cst_name font_size_7"><?=$g5['set_bom_type_value'][$row1['bom_type']]?></span>
                            <span class="span_cst_name font_size_8"><?=$row1['cst_name']?></span>
                            <span class="span_bit_count font_size_8"><?=$row1['bit_count']?>개</span>
                        <?php } else { ?>
                            <span class="font_size_7 <?=$row1['bit_main_class']?>">ㄴ <?=$row1['bom_part_no']?></span>
                            <span class="span_cst_name font_size_8">동일 제품 생산</span>
                        <?php } ?>
                    </td>
                    <td class="td_mms_mb">
                        <?php if($bom_prev!=$row3['bom_idx']) echo $s_edit; ?>
                    </td>
                    <td class="td_mms_idx"><!-- 설비 -->
                        <select name="mms_idxs[<?=$ix?>]" id="mms_idx_<?=$ix?>" sync="mmw_idx_<?=$ix?>" class="mms_idx" onchange="obj_change(this);">
                            <?=$row1['mms_options']?>
                        </select>
                        <script>$('#mms_idx_<?=$ix?>').val('<?=$row3['mms_idx']?>');</script>
                    </td>
                    <td class="td_mb_id"><!-- 작업자 -->
                        <select name="mb_ids[<?=$ix?>]" id="mb_id_<?=$ix?>" sync="mb_id_<?=$ix?>" class="mb_id" onchange="obj_change(this);">
                            <?=$row1['mb_id_options']?>
                        </select>
                        <script>$('#mb_id_<?=$ix?>').val('<?=$row3['mb_id']?>');</script>
                    </td>
                    <td class="td_pri_value"><!-- 지시량 -->
                        <input type="text" name="pri_values[<?=$ix?>]" class="frm_input pri_value" value="<?=number_format($row3['pri_value'])?>" onclick="numtoprice(this);" bit_count="<?=$row3['bit_count']?>" oninput="obj_change(this);">
                    </td>
                    <td class="td_pri_ing"><!-- 작업상태 (비작업중일 때는 변경불가) -->
                        <!-- 작업자가 QR코드를 찍지 않는 상황에서 관리자가 임의로 바꿔줄 수 있도록 일단 변경합니다. -->
                        <select name="pri_ing[<?=$ix?>]" id="pri_ing_<?=$ix?>" class="pri_ing" onchange="obj_change(this);">
                            <option value="0">비작업</option>
                            <option value="1">작업중</option>
                        </select>
                        <script>
                        $('#pri_ing_<?=$ix?>').val('<?=(($w=='')?'0':$row3['pri_ing'])?>');   
                        </script>
                    </td>
                    <td class="td_btns">
                        <!--input type="submit" name="act_button" value="문자" class="btn btn_05"-->
                        <?=($bom_prev==$row3['bom_idx'])?$s_del:$s_copy?>
                    </td>
                </tr>
                <?php
                // 기존 bom 정보 저장
                $bom_prev = $row3['bom_idx'];
            }
            // 생산아이템(production_item)이 없는 경우는 단순 자재인 경우로 봐야 함 (설정값 없음)
            if($x<=0 && $bom_prev2 != $row1['bom_idx']) {
                $row1['bit_count'] = ($row1['bit_count']) ? $row1['bit_count'] : 1;
                $row1['pri_value'] = $prm['prm_value'] * $row1['bit_count'];
                // print_r2($row1['mms_options_ar']);
                $s_edit = '<a href="'.G5_USER_ADMIN_WIN_URL.'/bom_mms_mb_edit.php?file_name='.$g5['file_name'].'&bom_idx='.$row1['bom_idx'].'" bom_idx="'.$row1['bom_idx'].'" class="btn btn_03 btn_edit">편집</a>';
                $s_reg = '<a href="'.G5_USER_ADMIN_WIN_URL.'/pri_reg.php?file_name='.$g5['file_name'].'&bom_idx='.$row1['bom_idx'].'&boc_idx='.$prm['boc_idx'].'&prd_idx='.$prm['prd_idx'].'&prm_idx='.$prm['prm_idx'].'&bom_idx_parent='.$prm['bom_idx'].'&pri_date='.$prm['prm_date'].'&pri_value='.$row1['pri_value'].'" class="btn btn_06 btn_reg">생성</a>';
                ?>
                <tr>
                    <td class="td_bom_name" style="padding-left:<?=$padding_left?>px;">
                        <?=$row1['nbsp']?><?=$row1['len']?>
                        <span class="font_size_7 <?=$row1['bit_main_class']?>"><?=$row1['bom_part_no']?></span>
                        <?=$row1['bom_name']?>
                        <span class="span_cst_name font_size_7"><?=$g5['set_bom_type_value'][$row1['bom_type']]?></span>
                        <span class="span_cst_name font_size_8"><?=$row1['cst_name']?></span>
                        <span class="span_bit_count font_size_8"><?=$row1['bit_count']?>개</span>
                    </td>
                    <td class="td_mms_mb"><!-- 설비-작업자(수정) -->
                    <?php echo $s_edit; ?>
                    </td>
                    <td class="td_mms_idx"><!-- 설비 -->
                    </td>
                    <td class="td_mb_id"><!-- 작업자 -->
                    </td>
                    <td class="td_pri_value">
                    </td>
                    <td class="td_pri_ing"><!-- 상태 -->
                    </td>
                    <td class="td_btns">
                    <?php echo $s_reg; ?>
                    </td>
                </tr>
                <?php
                $bom_prev2 = $row1['bom_idx'];
            }
        }
        if($i<=0) { 
            echo '<tr><td colspan="9" class="empty_table">기본 정보를 등록한 후 설정하세요.</td></tr>';
        }
        ?>
        </tbody>
        </table>
    </div>
</div><!-- //.tbl_frm01 tbl_wrap -->

<div class="btn_fixed_top">
    <?php
    $production_url = ($calendar) ? './order_out_practice_calendar_list.php?'.$qstr:'./production_list.php?'.$qstr;
    ?>
    <?php if($w == 'u'){ ?>
    <!-- <input type="submit" name="act_button" value="문자발송" onclick="document.pressed=this.value" class="btn btn_05"> -->
    <input type="submit" name="act_button" value="초기화" onclick="document.pressed=this.value" class="btn btn_05 mr_30">
    <?php } ?>
    <a href="<?=$production_url?>" class="btn btn_02">목록</a>
    <input type="submit" name="act_button" value="확인" onclick="document.pressed=this.value" class="btn_submit btn" accesskey='s'>
</div>
</form>
<table id="tr_reg" style="display:none;">
<tr bom_idx="" bom_usage="" pri_idx="">
    <td class="td_bom_name" style="">
        <span class="nbsp" style=""></span>
        <span class="font_size_7"></span>
        <span class="span_cst_name font_size_8">동일 제품 생산</span>
    </td>
    <td class="td_mms_mb"></td><!-- 설비-작업자(수정) -->
    <td class="td_mms_idx"></td><!-- 설비 -->
    <td class="td_mb_id"></td><!-- 작업자 -->
    <td class="td_pri_value"></td>
    <td class="td_pri_ing"></td><!-- 상태 -->
    <td class="td_btns"><a href="javascript:" bom_idx="" mms_idx="" mb_id="" prd_idx="" prm_idx="" boc_idx="" bom_idx_parent="" pri_value="" pri_ing="" pri_date="" class="btn btn_03 btn_reg" onclick="pri_reg(this);">등록</a></td>
</tr>
</table>
<script>
let prd_idx = '<?=$prm['prd_idx']?>';
let prm_idx = '<?=$prm['prm_idx']?>';
let prm_date = '<?=$prm['prm_date']?>';
let boc_idx = '<?=$prm['boc_idx']?>';
let bom_idx_parent = '<?=$prm['bom_idx']?>';
$(function(){
    <?php if($w == ''){ ?>
    $("input[name$=_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
    <?php } ?>
    // 제품-설비-작업자 편집버튼 클릭
	$(".btn_edit").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winBomMmsMbEditSelect = window.open(href, "winBomMmsMbEditSelect", "left=300,top=150,width=850,height=700,scrollbars=1");
        winBomMmsMbEditSelect.focus();
	});
    // 생산계획제품 새롭게 pri 생성버튼 클릭
	$(".btn_reg").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winPriReg = window.open(href, "winPriReg", "left=300,top=150,width=550,height=300,scrollbars=1");
        winPriReg.focus();
	});

    // 복제, ajax로 복제 작업 후 새로고침
    $(document).on('click','.btn_copy',function(e){
        e.preventDefault();
        let len = Number($(this).closest('tr').attr('len'));
        let bom_idx = $(this).closest('tr').attr('bom_idx');
        let bom_part_no = 'ㄴ ' + $(this).closest('tr').attr('bom_part_no');
        let pri_date = prm_date;
        let nbsp = '';
        for(k=0;k<=len;k++){
            nbsp += '&nbsp;&nbsp;&nbsp;';
        }
        let trLastIdx = $('tr[bom_idx="'+bom_idx+'"]').length - 1;
        let trLastObj = $('tr[bom_idx="'+bom_idx+'"]').eq(trLastIdx);
        let reg_tr = $('#tr_reg').find('tr').clone();
        let reg_btn = reg_tr.find('.btn_reg');

        reg_tr.attr('bom_idx',bom_idx);
        
        let mmsSelect = $(this).closest('tr').find('select[name^="mms_idx"]').clone();
        mmsSelect.removeAttr('name').removeAttr('id').removeAttr('sync');
        let mbSelect = $(this).closest('tr').find('select[name^="mb_id"]').clone();
        mbSelect.removeAttr('name').removeAttr('id').removeAttr('sync');
        let valInput = $(this).closest('tr').find('input[name^="pri_value"]').clone();
        valInput.removeAttr('name');
        let ingSelect = $(this).closest('tr').find('select[name^="pri_ing"]').clone();
        ingSelect.removeAttr('name').removeAttr('id');

        reg_tr.find('.nbsp').html(nbsp);
        reg_tr.find('.font_size_7').text(bom_part_no);
        mmsSelect.appendTo(reg_tr.find('.td_mms_idx'));
        mbSelect.appendTo(reg_tr.find('.td_mb_id'));
        valInput.appendTo(reg_tr.find('.td_pri_value'));
        ingSelect.appendTo(reg_tr.find('.td_pri_ing'));

        reg_btn.attr({
            'bom_idx': bom_idx,
            'mms_idx': mmsSelect.val(),
            'mb_id': mbSelect.val(),
            'prd_idx': prd_idx,
            'prm_idx': prm_idx,
            'boc_idx': boc_idx,
            'bom_idx_parent': bom_idx_parent,
            'pri_value': valInput.val(),
            'pri_ing': ingSelect.val(),
            'pri_date': pri_date
        });

        reg_tr.insertAfter(trLastObj);
    });



    // 삭제, ajax로 작업 후 새로고침
    $(document).on('click','.btn_del',function(e){
        e.preventDefault();
        if(confirm('해당 항목을 삭제하시겠습니까?\n삭제 후 지시수량 확인하세요.')) {
            //-- 디버깅 Ajax --//
            var pri_idx = $(this).closest('tr').attr('pri_idx');

            $.ajax({
                url:g5_user_admin_url+'/ajax/production.json.php',
                data:{"aj":"d1","pri_idx":pri_idx},
                dataType:'json', timeout:10000, beforeSend:function(){}, success:function(res){
            //$.getJSON(g5_user_admin_url+'/ajax/company.json.php',{"aj":"c1","com_idx":com_idx},function(res) {
                //alert(res.sql);
                    if(res.result == true) {
                        self.location.reload();
                    }
                    else {
                        alert(res.msg);
                    }
                },
                error:function(xmlRequest) {
                    alert('Status: ' + xmlRequest.status + ' \n\rstatusText: ' + xmlRequest.statusText 
                        + ' \n\rresponseText: ' + xmlRequest.responseText);
                }
            });
        }
    });

    // 품명 버튼 클릭
	$("#btn_bom").click(function(e) {
		e.preventDefault();
        var href = $(this).attr('href');
		winBOM = window.open(href, "winBOM", "left=300,top=150,width=750,height=600,scrollbars=1");
        winBOM.focus();
	});


    // 수주ID찾기 버튼 클릭
	$("#btn_prd").click(function(e) {
		e.preventDefault();
        if(!$('#bom_idx').val()){
            alert('상품을 먼저 선택해 주세요.');
            $('#prd_idx').val('');
            $('#prd_date').val('');
            $('#prm_value').val('');
            return false;
        }
        
        var href = $(this).attr('href')+'&boc_idx='+$('#boc_idx').val();
		var winOrderSelect = window.open(href, "winOrderSelect", "left=300,top=150,width=850,height=700,scrollbars=1");
        winOrderSelect.focus();
        return false;
	});

    //제품ID 취소는 수주ID도 취소 & 출하ID도 취소한다.
    $('.bom_cancel').on('click',function(){
        $('#bom_idx').val('');
        $('#boc_idx').val('');
        $('input[name=bom_name]').val('');
        $('.span_bom_part_no').text('');

        $('#prd_date').val('');
        $('#prd_idx').val('');
        $('#prm_value').val('');
    });
    //수주ID 취소는 출하ID도 취소한다.
    $('.prd_cancel').on('click',function(){
        $('#prd_idx').val('');
        $('#prd_date').val('');
        $('#prm_value').val('');
    });

});

function obj_change(obj){
    let mmsSel = $(obj).closest('tr').find('.mms_idx');
    let mbSel = $(obj).closest('tr').find('.mb_id');
    let priInput = $(obj).closest('tr').find('.pri_value');
    let ingSel = $(obj).closest('tr').find('.pri_ing');
    let btn = $(obj).closest('tr').find('.btn_reg');

    let bom_idx = Number($(obj).closest('tr').attr('bom_idx'));
    let mms_idx = mmsSel.val();
    let mb_id = mbSel.val();
    let pri_value = priInput.val();
    let pri_ing = ingSel.val();
    let pri_date = prm_date;
    if($(obj).hasClass('mms_idx')){
        // console.log(bom_idx,mms_idx);
        let aurl = '<?=G5_USER_ADMIN_AJAX_URL?>/bom_mms_mb.php';
        // console.log(aurl);
        $.ajax({
            url: aurl,
            type: 'POST',
            dataType: 'html',
            data: {'bom_idx': bom_idx, 'mms_idx': mms_idx},
            success: function (res){
                mbSel.empty();
                mbSel.html(res);
                mb_id = mbSel.val();
                if(btn.hasClass('btn_reg')){
                    btn.attr({
                        'bom_idx': bom_idx,
                        'mms_idx': mms_idx,
                        'mb_id': mb_id,
                        'prd_idx': prd_idx,
                        'prm_idx': prm_idx,
                        'boc_idx': boc_idx,
                        'bom_idx_parent': bom_idx_parent,
                        'pri_value': pri_value.replace(/,/g,''),
                        'pri_date': pri_date,
                        'pri_ing': pri_ing
                    });
                }
                return;
            },
            error: function(xre){
                alert('Status: ' + xre.status + ' \n\rstatusText: ' + xre.statusText + ' \n\rresponseText: ' + xre.responseText);
            }
        });
    }

    if(btn.hasClass('btn_reg')){
        btn.attr({
            'bom_idx': bom_idx,
            'mms_idx': mms_idx,
            'mb_id': mb_id,
            'prd_idx': prd_idx,
            'prm_idx': prm_idx,
            'boc_idx': boc_idx,
            'bom_idx_parent': bom_idx_parent,
            'pri_value': pri_value.replace(/,/g,''),
            'pri_date': pri_date,
            'pri_ing': pri_ing
        });
    }
}

function pri_reg(regBtn){
    let reg_btn = $(regBtn);
    let bom_idx = reg_btn.attr('bom_idx');
    let mms_idx = reg_btn.attr('mms_idx');
    let mb_id = reg_btn.attr('mb_id');
    let prd_idx = reg_btn.attr('prd_idx');
    let prm_idx = reg_btn.attr('prm_idx');
    let boc_idx = reg_btn.attr('boc_idx');
    let bom_idx_parent = reg_btn.attr('bom_idx_parent');
    let pri_value = reg_btn.attr('pri_value');
    let pri_ing = reg_btn.attr('pri_ing');
    let pri_date = reg_btn.attr('pri_date');
    let aurl = '<?=G5_USER_ADMIN_AJAX_URL?>/pri_insert.php';
    
    if(mms_idx == ''){
        alert('설비데이터가 없습니다.');
        return;
    }
    if(mb_id == ''){
        alert('작업자데이터가 없습니다.');
        return;
    }
    if(pri_value == '' || pri_value == '0'){
        alert('지시량데이터가 없습니다.');
        return;
    }

    // console.log(aurl);
    $.ajax({
        url: aurl,
        type: 'POST',
        dataType: 'text',
        data: {
            'bom_idx': bom_idx 
            ,'mms_idx': mms_idx
            ,'mb_id': mb_id
            ,'prd_idx': prd_idx
            ,'prm_idx': prm_idx
            ,'boc_idx': boc_idx
            ,'bom_idx_parent': bom_idx_parent
            ,'pri_value': pri_value
            ,'pri_date': pri_date
            ,'pri_ing': pri_ing
        },
        success: function (res){
            if(res == 'ok'){
                location.reload();
            }
            else{
                alert(res);
            }
        },
        error: function(xre){
            alert('Status: ' + xre.status + ' \n\rstatusText: ' + xre.statusText + ' \n\rresponseText: ' + xre.responseText);
        }
    });
}


function form01_submit(f){
    if(document.pressed == "초기화"){
        if(confirm('생산계획을 초기화하시겠습니까?')){
            return true;
        }
        else {
            return false;
        }
    }
    
    //생산할 상품을 선택하세요
    if(!f.bom_idx.value){
        alert('생산할 상품을 선택해 주세요.');
        f.bom_name.focus();
        return false;
    }

    //생산시작일을 설정해 주세요
    if(f.prd_date.value == '' || f.prd_date.value == '0000-00-00'){
        alert('생산일을 선택해 주세요.');
        f.prd_date.focus();
        return false;
    }
    
    //지시수량을 설정하세요.
    if(!f.prm_value.value){
        alert('지시수량을 설정해 주세요.');
        f.prm_value.focus();
        return false;
    }
    //상태값을 설정해 주세요
    if(!f.prm_status.value){
        alert('상태값을 선택해 주세요.');
        f.prm_status.focus();
        return false;
    }

    var mms_idx_error = 0;
    $('.mms_idx').each(function(){
        if(!$(this).val()){
            mms_idx_error++;
        }
    });
    if(mms_idx_error>0) {
        alert('설비선택을 확인해 주세요.');
        return false;
    }

    var mb_id_error = 0;
    $('.mb_id').each(function(){
        if(!$(this).val()){
            mb_id_error++;
        }
    });
    if(mb_id_error>0) {
        alert('작업자 선택을 확인해 주세요.');
        return false;
    }



    var pri_value_empty_error = 0;
    var pri_value_arr = [];
    $('.pri_value').each(function(){
        if(!$(this).val()){
            pri_value_empty_error++;
        }
        // console.log( $('#prd_count').val() );
        // console.log( $(this).closest('tr').attr('pri_idx') );
        // console.log( $(this).val() );
        var bom_idx = parseInt($(this).closest('tr').attr('bom_idx'));
        var bom_usage = parseInt($(this).closest('tr').attr('bom_usage'));
        var pri_idx = parseInt($(this).closest('tr').attr('pri_idx'));
        var prd_val = parseInt($(this).val().replace(/[^0-9|-]/g,""));
        pri_value_arr.push({bom_idx:bom_idx,bom_usage:bom_usage,pri_idx:pri_idx,prd_val:prd_val});
    });
    if(pri_value_empty_error>0) {
        alert('지시량이 없는 항목이 있습니다.');
        return false;
    }
    console.log(pri_value_arr);
    // 각 요소별 항목을 개별 합계 추출 reduce 함수 사용
    const pri_values = Object.values(pri_value_arr.reduce((acc, cur) => {
        const {bom_idx, bom_usage} = cur;
        const key = `${bom_idx}_${bom_usage}`;
        if (acc[key]) {
            acc[key].prd_val += parseInt(cur.prd_val);
        } else {
            acc[key] = {...cur};
        }
        return acc;
    }, {}));
    console.log(pri_values);
    // const result = pri_values.find((item)=>item.bom_idx=== 114).prd_val;
    // console.log(result); // 130
    // 지시량과 맞지 않는 항목이 있으면 수정 불가!!
    var pri_value_count_error = 0;
    var prd_value = pri_values.find((item)=>item.bom_idx===parseInt($('#bom_idx').val())).prd_val;
    // console.log(prd_value);
    for (const i in pri_values) {
    //    console.log(`pri_values[${i}] = ${pri_values[i]}`);
        // console.log(pri_values[i]);
        // console.log(pri_values[i]['bom_usage']);
        // console.log(pri_values[i]['prd_val']);
        var prd_value2 = pri_values[i]['bom_usage']*prd_value;
        // console.log(prd_value2);
        if(pri_values[i]['prd_val'] != prd_value2 ) {
            pri_value_count_error++;
        }
    }
    // console.log(pri_value_count_error);
    if(false){ //(pri_value_count_error>0) {
        alert('완제품의 지시수량과 각 하위제품의 지시수량 합계가 일치하지 않습니다.\n제품의 지시 수량을 확인하세요.');
        return false;
    }


    return true;
    // return false;
}
</script>

<?php
include_once ('./_tail.php');