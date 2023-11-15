<form name="form01" id="form01" action="./mms_form_update.php" onsubmit="return form01_submit(this);" method="post" enctype="multipart/form-data">
    <input type="text" name="mms_serial_no" value="<?php echo $mms['mms_serial_no'] ?>" id="mms_serial_no" class="frm_input" onkeyup="chk_Serial(this)">
    <span id="sp_ex">(예) DC0012</span>
    <span id="sp_notice"></span>
</form>

<script>
    function chk_Serial(object) {
        var ex = /[\{\}\[\]\/?.,;:|\)*~`!^\+┼<>@\#$%&\'\"\\\(\=ㄱ-ㅎㅏ-ㅣ가-힣]*/g;
        var hx = /[A-Za-z]{2}\d{4}$/;
        //var pt = /^[^-_][a-zA-Z0-9]+[-_]?[a-zA-Z0-9]+[-_]?[a-zA-Z0-9]+[^-_]$/;
        //var hx = /^[^-_][a-zA-Z0-9]+[-][a-zA-Z0-9]+[-][a-zA-Z0-9]+[^-_]$/; //한국수지만의 패턴
        object.value = object.value.replace(ex, ""); //-_제외한 특수문자,한글입력 불가
        var str = object.value;

        if (hx.test(str)) {
            var mms_idx = '<?= $mms_idx ?>';
            var mms_chk_url = './ajax/mms_serial_no_overlap_chk.php';
            var st = $.trim(str.toUpperCase());
            object.value = st;
            // var msg = '등록 가능한 시리얼번호입니다.';
            // document.getElementById('sp_notice').textContent = msg;
            // $('#sp_notice').removeClass('sp_error');
            //디비에 mms_serial_no가 존재하는지 확인하고 존재하면 에러를 발생
            //console.log(st);
            $.ajax({
                type: 'POST',
                url: mms_chk_url,
                dataType: 'text',
                data: {
                    'mms_idx': mms_idx,
                    'mms_serial_no': st
                },
                success: function(res) {
                    //console.log(res);
                    if (res == 'ok') {
                        document.getElementById('sp_notice').textContent = '등록 가능한 시리얼번호입니다.';
                        $('#sp_notice').removeClass('sp_error');
                    } else if (res == 'overlap') {
                        document.getElementById('sp_notice').textContent = '이미 등록된 시리얼번호입니다.';
                        $('#sp_notice').removeClass('sp_error');
                        $('#sp_notice').addClass('sp_error');
                    } else if (res == 'same') {
                        document.getElementById('sp_notice').textContent = '시리얼번호 설정완료';
                        $('#sp_notice').removeClass('sp_error');
                    }
                },
                error: function(xmlReq) {
                    document.getElementById('sp_notice').textContent = '에러가 발생했습니다.';
                    $('#sp_notice').removeClass('sp_error');
                    $('#sp_notice').addClass('sp_error');
                    // alert('Status: ' + xmlReq.status + ' \n\rstatusText: ' + xmlReq.statusText + ' \n\rresponseText: ' + xmlReq.responseText);
                }
            });
        } else {
            document.getElementById('sp_notice').textContent = '코드규칙에 맞지않습니다.';
            $('#sp_notice').removeClass('sp_error');
            $('#sp_notice').addClass('sp_error');
            if (!str.length) {
                $('#sp_notice').removeClass('sp_error').text('');
            }
        }
    }


    function form01_submit(f) {
        if (!f.mms_serial_no.value) {
            alert('설비시리얼번호를 입력해 주세요.');
            $('input[name="mms_serial_no"]').focus();
            return false;
        }

        if ($('#sp_notice').hasClass('sp_error')) {
            alert('올바른 설비시리얼번호를 입력해 주세요.');
            $('input[name="mms_serial_no"]').val('').focus();
            $('#sp_notice').removeClass('sp_error').text('');
            return false;
        }

        return true;
    }
</script>

<!--#############################-->
<!--mms_serial_no_overlap_chk.php-->
<!--#############################-->
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
?>