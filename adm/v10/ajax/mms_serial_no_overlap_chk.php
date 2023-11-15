<?php
include_once('./_common.php');

$mms_idx = $_POST['mms_idx'];
$mms_serial_no = trim($_POST['mms_serial_no']);
$msg = '';
$sql = "select COUNT(*) AS cnt, mms_idx
        from {$g5['mms_table']}
        where mms_status NOT IN ('delete','del','trash','cancel') AND com_idx ='".$_SESSION['ss_com_idx']."'  AND mms_serial_no = '".$mms_serial_no."' 
";
$row = sql_fetch($sql);
/*
echo $mms_idx;
echo gettype($mms_idx);
echo $row['mms_idx'];
echo gettype($row['mms_idx']);exit;
*/
//
if($row['cnt'] == '1'){
    if($mms_idx == $row['mms_idx']){
        $msg = 'same';
    }
    else{
        $msg = 'overlap';
    }
}
else{
   $msg = 'ok'; 
}

echo $msg;
