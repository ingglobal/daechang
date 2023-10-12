<?php
$sub_menu = "910146";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');

if(!$config['cf_faq_skin']) $config['cf_faq_skin'] = "basic";
if(!$config['cf_mobile_faq_skin']) $config['cf_mobile_faq_skin'] = "basic";

$g5['title'] = '생기팀관리자설정';
include_once('./_top_menu_manager.php');
include_once('./_head.php');
// echo $g5['container_sub_title'];

// 개별 업체 설정은 별도로 가지고 와야 합니다.
// $sql = "SELECT com_idx, set_name, set_value
// 		FROM {$g5['setting_table']}
// 		WHERE com_idx = '".$_SESSION['ss_com_idx']."'
// 			AND set_key = 'manager'
// ";
// $result = sql_query($sql,1);
// for ($i=0; $row=sql_fetch_array($result); $i++) {
// 	// print_r3($row);
//     $g5['setting'][$_SESSION['ss_com_idx'].'_'.$row['set_name']] = $row['set_value'];
// 	${$row['set_name'].'_check'} = 1;
// 	// 원래 공통 변수는 따로 가지고 와야 함
// 	$one = sql_fetch("SELECT set_value FROM {$g5['setting_table']} WHERE com_idx = '0' AND set_name = '".$row['set_name']."' ",1);
//     $g5['setting'][$row['set_name']] = $one['set_value'];

// }



$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_cf_default">기본설정</a></li>
</ul>';

if (!$config['cf_icode_server_ip'])   $config['cf_icode_server_ip'] = '211.172.232.124';
if (!$config['cf_icode_server_port']) $config['cf_icode_server_port'] = '7295';

if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw']) {
    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
}
?>
<style>
.check_company {position:absolute;top:10px;right:5px;}
.mbt,.mct {padding:6px 10px;border:2px solid #70a5cd;background:#a3d8ff;color:#555;border-radius:3px;}
.mbt.focus,.mct.focus {border:2px solid skyblue;background:#095d9d;color:#fff;}
</style>
<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
<input type="hidden" name="token" value="" id="token">

<section id="anc_cf_default">
	<h2 class="h2_frm">기본설정</h2>
	<?php echo $pg_anchor ?>	
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<caption>기본설정</caption>
		<colgroup>
			<col class="grid_4">
			<col>
		</colgroup>
		<tbody>
        <tr>
            <th scope="row">설비고장대응을 위한<br>작업자 호출(Call)시 문자 받을 인원</th>
            <td colspan="3">
                <?php
                    $s_arr = $g5['setting']['mng_mms_stop_checker']?explode(',',$g5['setting']['mng_mms_stop_checker']):array();
                    // print_r2($s_arr);
                    $q_arr = array();
                    $q_sql = " SELECT mb_id, mb_name 
                                    FROM {$g5['member_table']} 
                                WHERE mb_leave_date = ''
                                    AND mb_intercept_date = ''
                                    AND mb_9 = 'admin_tech' ";
                    $q_res = sql_query($q_sql,1);
                    for($i=0;$qow=sql_fetch_array($q_res);$i++){
                        $q_arr[$qow['mb_id']]=$qow['mb_name'];
                    }
                ?>
                <input type="hidden" name="mng_mms_stop_checker" value="<?=$g5['setting']['mng_mms_stop_checker']?>" id="mng_mms_stop_checker" readonly class="readonly frm_input" style="width:60%;">
                <div class="mb_box" style="padding:5px 0;">
                    <?php foreach($q_arr as $qk => $qv){ ?>
                    <button type="button" class="mct<?=(in_array($qk,$s_arr)?' focus':'')?>" mb_id="<?=$qk?>"><?=$qv?><br>(<?=$qk?>)</button>
                    <?php } ?>
                </div>
                <script>
                $('.mct').on('click',function(){
                    if($(this).hasClass('focus')) {
                        $(this).removeClass('focus');
                    }
                    else {
                        $(this).addClass('focus');
                    }
                    mct_select_string();
                });
                function mct_select_string(){
                    $('#mng_mms_stop_checker').val('');
                    var mstr = '';
                    $('.mct').each(function(){
                        if($(this).hasClass('focus')) mstr += ','+$(this).attr('mb_id');
                    });
                    mstr = mstr.substr(1);
                    $('#mng_mms_stop_checker').val(mstr);
                }
                </script>
            </td>
        </tr>
        </tbody>
		</table>
	</div>
</section>

<div class="btn_fixed_top btn_confirm">
    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
</div>

</form>

<script>
$(function(){

});

function fconfigform_submit(f) {

    <?php ;//echo get_editor_js("mng_msg_content"); ?>
    <?php ;//echo chk_editor_js("mng_msg_content"); ?>

    f.action = "./config_tech_manager_form_update.php";
    return true;
}
</script>

<?php
include_once ('./_tail.php');
?>
