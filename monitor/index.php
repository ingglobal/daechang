<?php
include_once('./_common.php');
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_monitor_path = G5_MONITOR_PATH;
$g5_monitor_url = G5_MONITOR_URL;

include_once('./_head.sub.php');

include_once('./content.php');

include_once('./_tail.sub.php');