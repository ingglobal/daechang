<?php
$sub_menu = "925920";
include_once('./_common.php');

auth_check($auth[$sub_menu],'w');

// 변수 설정, 필드 구조 및 prefix 추출
$g5_table_name = 'g5_1_data_downtime';
$fields = sql_field_names($g5_table_name);
$pre = substr($fields[0],0,strpos($fields[0],'_'));
$fname = preg_replace("/_form/","",$g5['file_name']); // _form을 제외한 파일명
// $qstr .= "&st_date=$st_date&st_time=$st_time&en_date=$en_date&en_time=$en_time&ser_mms_idx=$ser_mms_idx";
// $qstr .= '&mms_idx='.$mms_idx; // 추가로 확장해서 넘겨야 할 변수들


if ($w == '') {
    $sound_only = '<strong class="sound_only">필수</strong>';
    $w_display_none = ';display:none';  // 쓰기에서 숨김

    ${$pre}['dta_start_dt'] = date("Y-m-d H:00:00", G5_SERVER_TIME);
    ${$pre}['dta_end_dt'] = date("Y-m-d H:00:00", G5_SERVER_TIME+7200);
}
else if ($w == 'u') {
    $u_display_none = ';display:none;';  // 수정에서 숨김

	$sql = "SELECT * FROM {$g5_table_name} WHERE dta_idx = '".${$pre."_idx"}."' ";
    ${$pre} = sql_fetch($sql,1);
    // print_r3(${$pre});
    if (!${$pre}[$pre.'_idx'])
		alert('존재하지 않는 자료입니다.');

    $imp = get_table_meta('imp','imp_idx',${$pre}['imp_idx']);
    $mms = get_table_meta('mms','mms_idx',${$pre}['mms_idx']);
    $mmg = get_table_meta('mms_group','mmg_idx',${$pre}['mmg_idx']);
    $mst = get_table_meta('mms_status','mst_idx',${$pre}['mst_idx']);

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


$html_title = ($w=='')?'추가':'수정'; 
$g5['title'] = '비가동정보 '.$html_title;
include_once('./_top_menu_setting.php');
include_once('./_head.php');
echo $g5['container_sub_title'];

?>
<style>
#sp_chk.sp_error{color:red;}
#sp_chk.sp_ok{color:darkgreen;}

#type_box{position:relative;}
#type_box:after{display:block;visibility:hidden;clear:both;content:'';}
#mst_idx{float:left;}
#add_type{margin-left:5px;border:1px solid #ddd;height:35px;line-height:30px;font-weight:400;float:left;}
#add_type span{margin-left:10px;font-size:1.3em;}
#add_box{display:none;width:500px;position:absolute;top:0px;left:150px;}
#add_box.focus{display:inline-block;}
</style>


<div class="local_desc01 local_desc" style="display:no ne;">
    <p>설비를 선택하시면 비가동 타입 정보를 설비 관련 정보로 다시 불러옵니다.</p>
    <p>입력하려는 불량 타입이 없는 경우 '직접입력'을 선택하시고 새로운 항목을 추가해 주시면 됩니다.</p>
</div>
<form name="form01" id="form01" action="./<?= $g5['file_name'] ?>_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">
    <input type="hidden" name="<?=$pre?>_idx" value="<?php echo ${$pre . "_idx"} ?>">
    <input type="hidden" name="dta_defect" value="1">
    <input type="hidden" name="dta_group" value="manual">
    <input type="hidden" name="st_date" value="<?php echo $st_date ?>">
    <input type="hidden" name="en_date" value="<?php echo $en_date ?>">
    <input type="hidden" name="st_time" value="<?php echo $st_time ?>">
    <input type="hidden" name="en_time" value="<?php echo $en_time ?>">
    <input type="hidden" name="ser_mms_idx" value="<?php echo $ser_mms_idx ?>">
    <input type="hidden" name="com_idx" value="<?php echo $_SESSION['ss_com_idx'] ?>">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?></caption>
            <colgroup>
                <col class="grid_4" style="width:15%;">
                <col style="width:35%;">
                <col class="grid_4" style="width:15%;">
                <col style="width:35%;">
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row">공장선택</th>
                    <td>
                        <select name="dta_factory" id="dta_factory" class="required" required>
                            <?=$g5['mng_factory_location_value_options']?>
                        </select>
                        <script>
                            $('select[name=dta_factory]')
                                .val("<?=${$pre}['dta_factory']?>")
                                .attr('selected', 'selected');
                        </script>
                    </td>
                    <th scope="row">라인선택</th>
                    <td>
                        <select name="dta_line" id="dta_line" class="required" required>
                            <?=$g5['mng_line_location_value_options']?>
                        </select>
                        <script>
                            $('select[name=dta_line]')
                                .val("<?=${$pre}['dta_line']?>");
                        </script>
                    </td>
                </tr>
                <tr>
                    <th scope="row">공정선택</th>
                    <td>
                        <select name="dta_process" id="dta_process" class="required" required>
                            <?=$g5['mng_process_category_value_options']?>
                        </select>
                        <script>
                            $('select[name=dta_process]')
                                .val("<?=${$pre}['dta_process']?>");
                        </script>
                    </td>
                    <th scope="row">하드웨어선택</th>
                    <td>
                        <select name="dta_hardware" id="dta_hardware" class="required" required>
                            <?=$g5['mng_hardware_category_value_options']?>
                        </select>
                        <script>
                            $('select[name=dta_hardware]')
                                .val("<?=${$pre}['dta_hardware']?>");
                        </script>
                    </td>
                </tr>
                <tr>
                    <th scope="row">설비선택</th>
                    <td>
                        <select name="mms_idx" id="mms_idx" class="required" required>
                            <option value="">설비선택</option>
                            <?php
                            // 해당 범위 안의 모든 설비를 select option으로 만들어서 선택할 수 있도록 한다.
                            $sql2 = "SELECT mms_idx, mms_name
                                    FROM {$g5['mms_table']}
                                    WHERE com_idx = '" . $_SESSION['ss_com_idx'] . "'
                                    ORDER BY mms_idx       
                            ";
                            // echo $sql2.'<br>';
                            $result2 = sql_query($sql2, 1);
                            for ($i = 0; $row2 = sql_fetch_array($result2); $i++) {
                                // print_r2($row2);
                                echo '<option value="' . $row2['mms_idx'] . '" ' . get_selected(${$pre}['mms_idx'], $row2['mms_idx']) . '>' . $row2['mms_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <script>
                            $('select[name=mms_idx]')
                                .val("<?=${$pre}['mms_idx']?>")
                                .attr('selected', 'selected');
                        </script>
                    </td>
                    <script>
                        // 설비 선택 시 관련항목 추출
                        $(document).on('change', '#mms_idx', function(e) {
                            mms_idx_change($(this).val());
                        });
                        // 설비 선택 시 관련 비가동 상태 추출 함수
                        function mms_idx_change(mms_idx) {

                            // 진행중 표시 아이콘
                            $('.btn_spinner').show();
                            $('#mst_idx').hide();

                            if (mms_idx) {

                                // iframe hide
                                $("#mst_idx").closest('td').find('iframe').hide();

                                $.ajax({
                                    url: '<?= G5_USER_ADMIN_AJAX_URL ?>/mms_downtime_select.php',
                                    data: {
                                        'mms_idx': mms_idx
                                    },
                                    dataType: 'json',
                                    timeout: 10000,
                                    beforeSend: function() {},
                                    success: function(res) {
                                        // console.log(res);
                                        //var prop1; for(prop1 in res.rows) { console.log( prop1 +': '+ res.rows[prop1] ); }
                                        $('#mst_idx').children('option:not(:first)').remove(); // 항목 초기화

                                        // 비가동 타입
                                        $.each(res.rows2, function(i, v) {
                                            // console.log(i+':'+v);
                                            $('#mst_idx').append(
                                                "<option value='" + v['mst_idx'] + "'>" + v['mst_name'] + "</option>"
                                            );
                                        });
                                        $('#mst_idx').append(
                                            "<option value='direct'>직접입력</option>"
                                        );
                                        // 기존값이 있었다면 선택상태로 설정
                                        if ($('#mst_idx').attr('mst_idx') != '') {
                                            $('#mst_idx').val($('#mst_idx').attr('mst_idx'));
                                        }

                                        // 진행중 표시 아이콘 숨김
                                        $('.btn_spinner').hide();
                                        $('#mst_idx').show();

                                    },
                                    error: function(xmlRequest) {
                                        alert('Status: ' + xmlRequest.status + ' \n\rstatusText: ' + xmlRequest.statusText +
                                            ' \n\rresponseText: ' + xmlRequest.responseText);
                                    }
                                });
                            } else {
                                $('#mst_idx').children('option:not(:first)').remove(); // 항목 초기화
                            }
                        };
                        <?php
                        // 설비선택이 있는 경우 함수 호출
                        if (${$pre}['mms_idx']) {
                            echo "mms_idx_change(".${$pre}['mms_idx'].");";
                        }
                        ?>
                    </script>
                    <th scope="row">비가동타입</th>
                    <td>
                        <i class="fa fa-spinner fa-spin fa-fw btn_spinner" style="display:none;"></i>
                        <select name="mst_idx" id="mst_idx" mst_idx="<?= ${$pre}['mst_idx'] ?>" class="required" required>
                            <option value="">비가동 타입을 선택하세요.</option>
                        </select>
                        <iframe id="iframe02" src="<?=G5_USER_ADMIN_URL?>/iframe.empty.php" frameborder="0" scrolling="no" style="display:none;border:solid 0px black;height:35px;"></iframe>
                    </td>
                    <script>
                        // 비가동 직접 입력
                        $(document).on('change', '#mst_idx', function(e) {
                            if ($(this).val() == 'direct') {
                                var mms_idx = $('#mms_idx').val();
                                $('#mst_idx').hide();
                                $('#mst_idx').closest('td').find('iframe').show();
                                $('#iframe02').attr('src','./manual_downtime_form.iframe.php?mms_idx='+mms_idx);
                            }
                        });
                    </script>
                </tr>
                <tr>
                    <th scope="row">비가동 시간</th>
                    <td>
                        <input type="hidden" name="dta_start_dt" value="<?=${$pre}['dta_start_dt']?>" id="dta_start_dt">
                        <input type="text" value="<?=substr(${$pre}['dta_start_dt'],0,10)?>" id="dta_start_date" class="frm_input required" required="required" style="width:90px;">
                        <select id="dta_start_time"></select>
                        ~
                        <input type="hidden" name="dta_end_dt" value="<?=${$pre}['dta_end_dt']?>" id="dta_end_dt">
                        <input type="text" name="dta_end_date" value="<?=substr(${$pre}['dta_end_dt'],0,10)?>" id="dta_end_date" class="frm_input required" required="required" style="width:90px;">
                        <select id="dta_end_time"></select>
                    </td>
                    <th scope="row">차종선택</th>
                    <td>
                        <select name="dta_category" id="dta_category">
                            <option value="">기타</option>
                            <?php foreach($g5['cats_key_val'] as $k => $v) { ?>
                            <option value="<?=$k?>" <?=get_selected(${$pre}['dta_category'], $k)?>><?=$v?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">교환부품</th>
                    <td>
                        <input type="text" name="dta_change_part" id="dta_change_part" class="frm_input" value="<?=${$pre}['dta_change_part']?>" style="width:60%;">
                    </td>
                    <th scope="row">메이커</th>
                    <td>
                        <input type="text" name="dta_maker" id="dta_maker" class="frm_input" value="<?=${$pre}['dta_maker']?>" style="width:60%;">
                    </td>
                </tr>
                <tr>
                    <th scope="row">규격</th>
                    <td colspan="3">
                        <input type="text" name="dta_standard" id="dta_standard" class="frm_input" value="<?=${$pre}['dta_standard']?>" style="width:40%;">
                    </td>
                </tr>
                <tr>
                    <th scope="row">수량</th>
                    <td>
                        <input type="text" name="dta_count" id="dta_count" class="frm_input input_count" value="<?=number_format(${$pre}['dta_count'])?>" style="width:110px;text-align:right;"> EA
                    </td>
                    <th scope="row">금액</th>
                    <td>
                        <input type="text" name="dta_price" id="dta_price" class="frm_input" value="<?=number_format(${$pre}['dta_price'])?>" style="width:110px;text-align:right;"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row">세부내용</th>
                    <td colspan="3">
                        <textarea name="dta_memo"><?php echo get_text(${$pre}['dta_memo']); ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <a href="./<?= $fname ?>_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
        <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
    </div>
</form>

<script>
$(function() {
    $("input[id$=_date]").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });

    timePicker($('#dta_start_time'),24,0,23);
    timePicker($('#dta_end_time'),24,0,23);
    $('#dta_start_time').val('<?=substr(${$pre}['dta_start_dt'],11,5)?>');
    $('#dta_end_time').val('<?=substr(${$pre}['dta_end_dt'],11,5)?>');

    $('#dta_start_date,#dta_start_time').on('change',function(){
        var dat = '';
        var tim = '';
        var dt = '';
        if($(this).attr('id') == 'dta_start_date'){
            dat = $(this).val();
            tim = ' '+$('#dta_start_time').val()+':00';
        } else {
            dat = $('#dta_start_date').val();
            tim = ' '+$(this).val()+':00';
        }
        dt = dat+tim;
        $('#dta_start_dt').val(dt);
    });

    $('#dta_end_date,#dta_end_time').on('change',function(){
        var dat = '';
        var tim = '';
        var dt = '';
        if($(this).attr('id') == 'dta_end_date'){
            dat = $(this).val();
            tim = ' '+$('#dta_end_time').val()+':00';
        } else {
            dat = $('#dta_end_date').val();
            tim = ' '+$(this).val()+':00';
        }
        dt = dat+tim;
        $('#dta_end_dt').val(dt);
    });
});

function form01_submit(f) {

    return true;
}
</script>

<?php
include_once('./_tail.php');
?>