<?php
$sub_menu = "940110";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

$mb_id = trim($_POST['mb_id']);

if ($mb_password)
    $sql_password = " , mb_password = '".get_encrypt_string($mb_password)."' ";
else
    $sql_password = "";

if ($passive_certify)
    $sql_certify = " , mb_email_certify = '".G5_TIME_YMDHIS."' ";
else
    $sql_certify = "";

$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3);

$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';
$mb_nick = isset($_POST['mb_nick']) ? trim(strip_tags($_POST['mb_nick'])) : '';

if ($msg = valid_mb_nick($mb_nick))     alert($msg, "", true, true);

$sql_common = "  mb_name = '{$_POST['mb_name']}',
                 mb_nick = '{$mb_nick}',
                 mb_email = '{$mb_email}',
                 mb_homepage = '{$_POST['mb_homepage']}',
                 mb_birth = '{$_POST['mb_birth']}',
                 mb_tel = '{$_POST['mb_tel']}',
                 mb_hp = '{$_POST['mb_hp']}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$_POST['mb_addr1']}',
                 mb_addr2 = '{$_POST['mb_addr2']}',
                 mb_addr3 = '{$_POST['mb_addr3']}',
                 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
                 mb_memo = '{$_POST['mb_memo']}',
                 mb_1 = '{$_POST['mb_1']}',
                 mb_5 = '{$_POST['mb_5']}',
                 mb_6 = '{$_POST['mb_6']}',
                 mb_8 = '{$_POST['mb_8']}'
";

if ($w == '') {
   $mb = get_member($mb_id);
   if (!$mb['mb_id']) {
        $sql = "INSERT INTO {$g5['member_table']} SET
                    mb_id = '{$mb_id}', 
                    mb_password = '".get_encrypt_string($mb_password)."', 
                    mb_datetime = '".G5_TIME_YMDHIS."', 
                    mb_ip = '{$_SERVER['REMOTE_ADDR']}', 
                    mb_level = 4, 
                    mb_email_certify = '".G5_TIME_YMDHIS."',
                    mb_4 = '{$_SESSION['ss_com_idx']}', 
                    {$sql_common}
        ";
        sql_query($sql,1);
        //echo $sql;
   }
   else {
        $sql = "UPDATE {$g5['member_table']} SET
                    mb_level = 4, 
                    mb_4 = '{$_SESSION['ss_com_idx']}', 
                    {$sql_common}
                WHERE mb_id = '".$mb_id."'
        ";
        sql_query($sql,1);
   }

    // ?????? ?????? ?????? ??????
    $set_values = explode("\n", $g5['setting']['set_employee_auth']);
    foreach ($set_values as $set_value) {
        list($key, $value) = explode('=', trim($set_value));
        if($key&&$value) {
            // echo $key.' / '.$value.'<br>';

            $au1 = sql_fetch(" SELECT * FROM {$g5['auth_table']} WHERE mb_id = '".$mb_id."' AND au_menu = '".$key."' ",1);
            // ???????????? ????????????
            if($au1['au_menu']) {
                $sql = "UPDATE {$g5['auth_table']} SET
                            au_auth = '".$value."'
                        WHERE mb_id = '".$mb_id."' AND au_menu = '".$key."'
                ";
                //echo $sql.'<br>';
                sql_query($sql,1);
            }
            // ????????? ??????
            else {
                $sql = "INSERT INTO {$g5['auth_table']} SET
                            mb_id = '".$mb_id."'
                            , au_menu = '".$key."'
                            , au_auth = '".$value."'
                ";
                //echo $sql.'<br>';
                sql_query($sql,1);
            }

        }
    }
    unset($set_values);unset($set_value);

}
else if ($w == 'u') {
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('???????????? ?????? ?????????????????????.');

    $sql = " update {$g5['member_table']}
                set {$sql_common}
                    {$sql_password}
                    {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql,1);
    //echo $sql;

}
else
    alert('????????? ??? ?????? ???????????? ???????????????.');


// ?????? ????????????
$sql_common = " com_idx = '".$_SESSION['ss_com_idx']."'
                , cmm_title = '".$_POST['mb_3']."'
";
$sql = "SELECT * FROM {$g5['company_member_table']}
        WHERE mb_id = '".$mb_id."'
            AND com_idx = '".$_SESSION['ss_com_idx']."'
            AND cmm_status = 'ok'
";
$cmm1 = sql_fetch($sql,1);
if($cmm1['cmm_idx']) {
    $sql = "UPDATE {$g5['company_member_table']} SET
                cmm_update_dt = '".G5_TIME_YMDHIS."'
                , {$sql_common}
            WHERE cmm_idx = '".$cmm1['cmm_idx']."'
    ";
    // echo $sql.'<br>';
    sql_query($sql,1);
}
// ????????? ??????
else {
    $sql = " INSERT INTO {$g5['company_member_table']} SET
                    mb_id = '{$mb_id}'
                    , cmm_status = 'ok'
                    , cmm_reg_dt = '".G5_TIME_YMDHIS."'
                , {$sql_common}
    ";
    // echo $sql.'<br>';
    sql_query($sql,1);
}


// // ?????? ??????????????? ???????????? ?????????????????? ?????? ????????? ?????? ?????????.
// if($_REQUEST['mb_first_page']=='manual_quality_input.php') {
//     $set_input_auth[] = '960650=r,w';
//     $set_input_auth[] = '960660=r,w';
// }
// if($_REQUEST['mb_first_page']=='manual_offwork_input.php') {
//     $set_input_auth[] = '960650=r,w';
//     $set_input_auth[] = '960670=r,w';
// }
// // ?????? ?????? ?????? ??????
// for($i=0;$i<sizeof($set_input_auth);$i++) {
//     list($key, $value) = explode('=', trim($set_input_auth[$i]));
//     if($key&&$value) {
//         // echo $key.' / '.$value.'<br>';

//         $au1 = sql_fetch(" SELECT * FROM {$g5['auth_table']} WHERE mb_id = '".$mb_id."' AND au_menu = '".$key."' ",1);
//         // ???????????? ????????????
//         if($au1['au_menu']) {
//             $sql = "UPDATE {$g5['auth_table']} SET
//                         au_auth = '".$value."'
//                     WHERE mb_id = '".$mb_id."' AND au_menu = '".$key."'
//             ";
//             //echo $sql.'<br>';
//             sql_query($sql,1);
//         }
//         // ????????? ??????
//         else {
//             $sql = "INSERT INTO {$g5['auth_table']} SET
//                         mb_id = '".$mb_id."'
//                         , au_menu = '".$key."'
//                         , au_auth = '".$value."'
//             ";
//             //echo $sql.'<br>';
//             sql_query($sql,1);
//         }

//     }
// }

// ?????? ?????? ?????? ?????????
if($auth_reset) {
    // ?????? ?????? ??????(?????????)
    $sql = "DELETE FROM {$g5['auth_table']} WHERE mb_id = '".$mb_id."' ";
    //echo $sql.'<br>';
    sql_query($sql,1);

    // ?????? ?????? ?????? ??????
    $set_values = explode("\n", $g5['setting']['set_'.$mb_8.'_auth']);
    foreach ($set_values as $set_value) {
        list($key, $value) = explode('=', trim($set_value));
        if($key&&$value) {
            // echo $key.' / '.$value.'<br>';
            $au1 = sql_fetch(" SELECT * FROM {$g5['auth_table']} WHERE mb_id = '".$mb_id."' AND au_menu = '".$key."' ",1);
            // ???????????? ????????????
            if($au1['au_menu']) {
                $sql = "UPDATE {$g5['auth_table']} SET
                            au_auth = '".$value."'
                        WHERE mb_id = '".$mb_id."' AND au_menu = '".$key."'
                ";
                //echo $sql.'<br>';
                sql_query($sql,1);
            }
            // ????????? ??????
            else {
                $sql = "INSERT INTO {$g5['auth_table']} SET
                            mb_id = '".$mb_id."'
                            , au_menu = '".$key."'
                            , au_auth = '".$value."'
                ";
                //echo $sql.'<br>';
                sql_query($sql,1);
            }
        }
    }
    unset($set_values);unset($set_value);
}




//-- ????????? ?????? mb_ ??? ?????? ????????? 3??? ?????? --//
$r = sql_query(" desc {$g5['member_table']} ");
while ( $d = sql_fetch_array($r) ) {$db_fields[] = $d['Field'];}
$db_prefix = substr($db_fields[0],0,3);

//-- ???????????? ?????? ??? ???????????? ?????? ????????? ??????, ?????? ??????????????? ?????? ????????? ????????? ?????????.
$checkbox_array=array();
for ($i=0;$i<sizeof($checkbox_array);$i++) {
	if(!$_REQUEST[$checkbox_array[$i]])
		$_REQUEST[$checkbox_array[$i]] = 0;
}

//-- ?????? ?????? (????????? ?????? ????????? ?????? ???????????? ?????????.) --//
$db_fields[] = "mb_2_old";	// ????????? ???????????? ????????? ????????? ??????.
foreach($_REQUEST as $key => $value ) {
	//-- ?????? ???????????? ?????? ?????? ???????????? ????????? prefix ??? ???????????? ???????????? ???????????? --//
	if(!in_array($key,$db_fields) && substr($key,0,3)==$db_prefix) {
		//echo $key."=".$_REQUEST[$key]."<br>";
		meta_update(array("mta_db_table"=>"member","mta_db_id"=>$mb_id,"mta_key"=>$key,"mta_value"=>$value));
	}
}


// ????????? ??????
$qstr .= $qstr.'&ser_trm_idxs='.$ser_trm_idxs;

// exit;
goto_url('./employee_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$mb_id, false);
// goto_url('./employee_list.php?'.$qstr, false);
?>