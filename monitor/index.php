<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_MONITOR_PATH.'/_head.sub.php');

if($m_id)
    include_once(G5_MONITOR_PATH.'/mms_call.php');
else
    include_once(G5_MONITOR_PATH.'/dash_call.php');

include_once(G5_MONITOR_PATH.'/_tail.sub.php');