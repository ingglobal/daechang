<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

include_once(G5_PATH.'/head.sub.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_MONITOR_URL.'/css/chart.css">', 2);
add_stylesheet('<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />', 0);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/highstock.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/highcharts-more.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/modules/data.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/modules/exporting.js"></script>
<script src="<?php echo G5_URL?>/lib/highcharts/Highstock/code/themes/high-contrast-dark.js"></script>
<!-- 다양한 시간 표현을 위한 플러그인 -->
<script src="<?php echo G5_URL?>/lib/highcharts/moment.js"></script>
<script>
$('#hd_login_msg').hide();
</script>
