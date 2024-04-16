<?php
// 공통 변수, 상수 선언
define('BR',                                '<br>');
define('G5_USER_DIR',                       'user');
define('G5_USER_ADMIN_DIR',                 'v10');
define('G5_AJAX_DIR',                       'ajax');
define('G5_CRON_DIR',                       'cron');
define('G5_JSON_DIR',                       'json');
define('G5_HOOK_DIR',                       'hook');
define('G5_KIOSK_DIR',                      'kiosk');
define('G5_MONITOR_DIR',                    'monitor');
define('G5_MONITOR2_DIR',                   'monitor2');
define('G5_TMP_DIR',                        'tmp');
define('G5_WDG_DATA_PATH',                  G5_DATA_PATH . '/wdg');
define('G5_WDG_DATA_URL',                   G5_URL . '/data/wdg');
define('G5_TMP_PATH',                       G5_PATH . '/' . G5_TMP_DIR);
define('G5_TMP_URL',                        G5_URL . '/' . G5_TMP_DIR);
define('G5_USER_PATH',                      G5_PATH . '/' . G5_USER_DIR);
define('G5_USER_URL',                       G5_URL . '/' . G5_USER_DIR);
define('G5_USER_ADMIN_PATH',                G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR);
define('G5_USER_ADMIN_URL',                 G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR);
define('G5_USER_ADMIN_AJAX_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/' . G5_AJAX_DIR);
define('G5_USER_ADMIN_AJAX_URL',            G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/' . G5_AJAX_DIR);
define('G5_USER_ADMIN_BBS_PATH',            G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/' . G5_BBS_DIR);
define('G5_USER_ADMIN_BBS_URL',             G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/' . G5_BBS_DIR);
define('G5_USER_ADMIN_CSS_PATH',            G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/' . G5_CSS_DIR);
define('G5_USER_ADMIN_CSS_URL',             G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/' . G5_CSS_DIR);
define('G5_USER_ADMIN_IMG_PATH',            G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/' . G5_IMG_DIR);
define('G5_USER_ADMIN_IMG_URL',             G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/' . G5_IMG_DIR);
define('G5_USER_ADMIN_JS_PATH',             G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/' . G5_JS_DIR);
define('G5_USER_ADMIN_JS_URL',              G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/' . G5_JS_DIR);
define('G5_USER_ADMIN_LIB_PATH',            G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/' . G5_LIB_DIR);
define('G5_USER_ADMIN_LIB_URL',             G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/' . G5_LIB_DIR);
define('G5_USER_ADMIN_SKIN_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/skin');
define('G5_USER_ADMIN_SKIN_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/skin');
define('G5_USER_ADMIN_SKIN_FORM_PATH',       G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/skin/form');
define('G5_USER_ADMIN_SKIN_FORM_URL',       G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/skin/form');
define('G5_USER_ADMIN_SKIN_SVG_PATH',       G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/skin/_svg');
define('G5_USER_ADMIN_SKIN_SVG_URL',           G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/skin/_svg');
define('G5_USER_ADMIN_STAT_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/stat');
define('G5_USER_ADMIN_STAT_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/stat');
define('G5_USER_ADMIN_SET_PATH',               G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/set');
define('G5_USER_ADMIN_SET_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/set');
define('G5_USER_ADMIN_SQL_PATH',               G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/sql');
define('G5_USER_ADMIN_SQL_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/sql');
define('G5_USER_ADMIN_SQLS_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/sqls');
define('G5_USER_ADMIN_SQLS_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/sqls');
define('G5_USER_ADMIN_SVG_PATH',               G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/svg');
define('G5_USER_ADMIN_SVG_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/svg');
define('G5_USER_ADMIN_SVG_PHP_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/svg_php');
define('G5_USER_ADMIN_SVG_PHP_URL',           G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/svg_php');
define('G5_USER_ADMIN_TEST_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/test');
define('G5_USER_ADMIN_TEST_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/test');
define('G5_USER_ADMIN_WDG_PATH',               G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/wdg');
define('G5_USER_ADMIN_WDG_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/wdg');
define('G5_USER_ADMIN_WIN_PATH',               G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/win');
define('G5_USER_ADMIN_WIN_URL',               G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/win');
define('G5_USER_ADMIN_MODAL_PATH',           G5_ADMIN_PATH . '/' . G5_USER_ADMIN_DIR . '/modal');
define('G5_USER_ADMIN_MODAL_URL',            G5_ADMIN_URL . '/' . G5_USER_ADMIN_DIR . '/modal');
define('G5_USER_ADMIN_MOBILE_PATH',         G5_USER_ADMIN_PATH . '/' . G5_MOBILE_DIR);
define('G5_USER_ADMIN_MOBILE_URL',          G5_USER_ADMIN_URL . '/' . G5_MOBILE_DIR);
define('G5_USER_ADMIN_MOBILE_AJAX_PATH',    G5_USER_ADMIN_PATH . '/' . G5_MOBILE_DIR . '/' . G5_AJAX_DIR);
define('G5_USER_ADMIN_MOBILE_AJAX_URL',     G5_USER_ADMIN_URL . '/' . G5_MOBILE_DIR . '/' . G5_AJAX_DIR);
define('G5_USER_ADMIN_MOBILE_CSS_PATH',     G5_USER_ADMIN_PATH . '/' . G5_MOBILE_DIR . '/' . G5_CSS_DIR);
define('G5_USER_ADMIN_MOBILE_CSS_URL',      G5_USER_ADMIN_URL . '/' . G5_MOBILE_DIR . '/' . G5_CSS_DIR);
define('G5_USER_ADMIN_MOBILE_IMG_PATH',     G5_USER_ADMIN_PATH . '/' . G5_MOBILE_DIR . '/' . G5_IMG_DIR);
define('G5_USER_ADMIN_MOBILE_IMG_URL',      G5_USER_ADMIN_URL . '/' . G5_MOBILE_DIR . '/' . G5_IMG_DIR);
define('G5_USER_ADMIN_MOBILE_JS_PATH',      G5_USER_ADMIN_PATH . '/' . G5_MOBILE_DIR . '/' . G5_JS_DIR);
define('G5_USER_ADMIN_MOBILE_JS_URL',       G5_USER_ADMIN_URL . '/' . G5_MOBILE_DIR . '/' . G5_JS_DIR);
define('G5_USER_ADMIN_MOBILE_LIB_PATH',     G5_USER_ADMIN_PATH . '/' . G5_MOBILE_DIR . '/' . G5_LIB_DIR);
define('G5_USER_ADMIN_MOBILE_LIB_URL',      G5_USER_ADMIN_URL . '/' . G5_MOBILE_DIR . '/' . G5_LIB_DIR);
define('G5_USER_ADMIN_KIOSK_PATH',          G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR);
define('G5_USER_ADMIN_KIOSK_URL',           G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR);
define('G5_USER_ADMIN_KIOSK_AJAX_PATH',     G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR . '/' . G5_AJAX_DIR);
define('G5_USER_ADMIN_KIOSK_AJAX_URL',      G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR . '/' . G5_AJAX_DIR);
define('G5_USER_ADMIN_KIOSK_BBS_PATH',      G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR . '/' . G5_BBS_DIR);
define('G5_USER_ADMIN_KIOSK_BBS_URL',       G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR . '/' . G5_BBS_DIR);
define('G5_USER_ADMIN_KIOSK_CSS_PATH',      G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR . '/' . G5_CSS_DIR);
define('G5_USER_ADMIN_KIOSK_CSS_URL',       G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR . '/' . G5_CSS_DIR);
define('G5_USER_ADMIN_KIOSK_IMG_PATH',      G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR . '/' . G5_IMG_DIR);
define('G5_USER_ADMIN_KIOSK_IMG_URL',       G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR . '/' . G5_IMG_DIR);
define('G5_USER_ADMIN_KIOSK_JS_PATH',       G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR . '/' . G5_JS_DIR);
define('G5_USER_ADMIN_KIOSK_JS_URL',        G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR . '/' . G5_JS_DIR);
define('G5_USER_ADMIN_KIOSK_LIB_PATH',      G5_USER_ADMIN_PATH . '/' . G5_KIOSK_DIR . '/' . G5_LIB_DIR);
define('G5_USER_ADMIN_KIOSK_LIB_URL',       G5_USER_ADMIN_URL . '/' . G5_KIOSK_DIR . '/' . G5_LIB_DIR);

define('G5_MONITOR_PATH',                   G5_PATH . '/' . G5_MONITOR_DIR);
define('G5_MONITOR_URL',                    G5_URL . '/' . G5_MONITOR_DIR);
define('G5_MONITOR_AJAX_PATH',              G5_MONITOR_PATH . '/' . G5_AJAX_DIR);
define('G5_MONITOR_AJAX_URL',               G5_MONITOR_URL . '/' . G5_AJAX_DIR);
define('G5_MONITOR_BBS_PATH',               G5_MONITOR_PATH . '/' . G5_BBS_DIR);
define('G5_MONITOR_BBS_URL',                G5_MONITOR_URL . '/' . G5_BBS_DIR);
define('G5_MONITOR_CSS_PATH',               G5_MONITOR_PATH . '/' . G5_CSS_DIR);
define('G5_MONITOR_CSS_URL',                G5_MONITOR_URL . '/' . G5_CSS_DIR);
define('G5_MONITOR_IMG_PATH',               G5_MONITOR_PATH . '/' . G5_IMG_DIR);
define('G5_MONITOR_IMG_URL',                G5_MONITOR_URL . '/' . G5_IMG_DIR);
define('G5_MONITOR_JS_PATH',                G5_MONITOR_PATH . '/' . G5_JS_DIR);
define('G5_MONITOR_JS_URL',                 G5_MONITOR_URL . '/' . G5_JS_DIR);
define('G5_MONITOR_LIB_PATH',               G5_MONITOR_PATH . '/' . G5_LIB_DIR);
define('G5_MONITOR_LIB_URL',                G5_MONITOR_URL . '/' . G5_LIB_DIR);

define('G5_MONITOR2_PATH',                  G5_PATH . '/' . G5_MONITOR2_DIR);
define('G5_MONITOR2_URL',                   G5_URL . '/' . G5_MONITOR2_DIR);
define('G5_MONITOR2_AJAX_PATH',             G5_MONITOR2_PATH . '/' . G5_AJAX_DIR);
define('G5_MONITOR2_AJAX_URL',              G5_MONITOR2_URL . '/' . G5_AJAX_DIR);
define('G5_MONITOR2_BBS_PATH',              G5_MONITOR2_PATH . '/' . G5_BBS_DIR);
define('G5_MONITOR2_BBS_URL',               G5_MONITOR2_URL . '/' . G5_BBS_DIR);
define('G5_MONITOR2_CSS_PATH',              G5_MONITOR2_PATH . '/' . G5_CSS_DIR);
define('G5_MONITOR2_CSS_URL',               G5_MONITOR2_URL . '/' . G5_CSS_DIR);
define('G5_MONITOR2_IMG_PATH',              G5_MONITOR2_PATH . '/' . G5_IMG_DIR);
define('G5_MONITOR2_IMG_URL',               G5_MONITOR2_URL . '/' . G5_IMG_DIR);
define('G5_MONITOR2_JS_PATH',               G5_MONITOR2_PATH . '/' . G5_JS_DIR);
define('G5_MONITOR2_JS_URL',                G5_MONITOR2_URL . '/' . G5_JS_DIR);
define('G5_MONITOR2_LIB_PATH',              G5_MONITOR2_PATH . '/' . G5_LIB_DIR);
define('G5_MONITOR2_LIB_URL',               G5_MONITOR2_URL . '/' . G5_LIB_DIR);

define('G5_USER_BBS_URL',                   G5_USER_URL . '/' . G5_BBS_DIR);
define('G5_USER_BBS_PATH',                  G5_USER_PATH . '/' . G5_BBS_DIR);
define('G5_USER_AJAX_URL',                  G5_USER_URL . '/' . G5_AJAX_DIR);
define('G5_USER_AJAX_PATH',                 G5_USER_PATH . '/' . G5_AJAX_DIR);
define('G5_USER_CSS_PATH',                  G5_USER_PATH . '/' . G5_CSS_DIR);
define('G5_USER_CSS_URL',                   G5_USER_URL . '/' . G5_CSS_DIR);
define('G5_USER_CRON_PATH',                 G5_USER_PATH . '/' . G5_CRON_DIR);
define('G5_USER_CRON_URL',                  G5_USER_URL . '/' . G5_CRON_DIR);
define('G5_USER_IMG_PATH',                  G5_USER_PATH . '/' . G5_IMG_DIR);
define('G5_USER_IMG_URL',                   G5_USER_URL . '/' . G5_IMG_DIR);
define('G5_USER_JS_PATH',                   G5_USER_PATH . '/' . G5_JS_DIR);
define('G5_USER_JS_URL',                    G5_USER_URL . '/' . G5_JS_DIR);
define('G5_USER_JSON_URL',                  G5_USER_URL . '/' . G5_JSON_DIR);
define('G5_USER_JSON_PATH',                 G5_USER_PATH . '/' . G5_JSON_DIR);
define('G5_USER_SVG_URL',                   G5_USER_URL . '/svg_php');
define('G5_USER_SVG_PATH',                  G5_USER_PATH . '/svg_php');
define('G5_USER_MOBILE_URL',                G5_USER_URL . '/mobile');
define('G5_USER_MOBILE_PATH',               G5_USER_PATH . '/mobile');

define('G5_DATA_WDG_PERMISSION',  0707); // 디렉토리 생성시 퍼미션
// 설정 테이블 정의 -----------------------------------------------------------
define('USER_TABLE_PREFIX',          G5_TABLE_PREFIX . '5_');

// G5_5_TABLE
$g5['setting_table']                = USER_TABLE_PREFIX . 'setting';
$g5['meta_table']                   = USER_TABLE_PREFIX . 'meta';
$g5['tally_table']                  = USER_TABLE_PREFIX . 'tally';
$g5['ymd_table']                    = USER_TABLE_PREFIX . 'ymd';
$g5['term_table']                   = USER_TABLE_PREFIX . 'term';
$g5['term_relation_table']          = USER_TABLE_PREFIX . 'term_relation';
$g5['ymd_table']                    = USER_TABLE_PREFIX . 'ymd';
$g5['file_table']                   = USER_TABLE_PREFIX . 'file';
$g5['banner_table']                 = USER_TABLE_PREFIX . 'banner';
$g5['history_table']                = USER_TABLE_PREFIX . 'history';
$g5['comment_table']                = USER_TABLE_PREFIX . 'comment';
$g5['user_log_table']               = USER_TABLE_PREFIX . 'user_log';
/*
$g5['wdg_table']                    = USER_TABLE_PREFIX . 'wdg';
$g5['wdg_config_table']             = USER_TABLE_PREFIX . 'wdg_config';
$g5['wdg_file_table']               = USER_TABLE_PREFIX . 'wdg_file';
$g5['wdg_content_table']            = USER_TABLE_PREFIX . 'wdg_content';
$g5['wdg_content_extend_table']     = USER_TABLE_PREFIX . 'wdg_content_extend';
$g5['wdg_option_table']             = USER_TABLE_PREFIX . 'wdg_option';
$g5['wdg_user_option_table']        = USER_TABLE_PREFIX . 'wdg_user_option';
*/
// 사용자 테이블 정의 -----------------------------------------------------------
define('NEW_TABLE_PREFIX',          G5_TABLE_PREFIX . '1_');

// G5_1_TABLE : 공통된 프로젝트 확장 DB
$g5['cast_shot_table']              = NEW_TABLE_PREFIX . 'cast_shot';
$g5['cast_shot_sub_table']          = NEW_TABLE_PREFIX . 'cast_shot_sub';
$g5['cast_shot_pressure_table']     = NEW_TABLE_PREFIX . 'cast_shot_pressure';
$g5['charge_in_table']              = NEW_TABLE_PREFIX . 'charge_in';
$g5['charge_out_table']             = NEW_TABLE_PREFIX . 'charge_out';
$g5['engrave_qrcode_table']         = NEW_TABLE_PREFIX . 'engrave_qrcode';
$g5['qr_cast_code_table']           = NEW_TABLE_PREFIX . 'qr_cast_code';
$g5['melting_temp_table']           = NEW_TABLE_PREFIX . 'melting_temp';
$g5['xray_inspection_table']        = NEW_TABLE_PREFIX . 'xray_inspection';
$g5['company_table']                = NEW_TABLE_PREFIX . 'company';
$g5['company_member_table']         = NEW_TABLE_PREFIX . 'company_member';
$g5['company_saler_table']          = NEW_TABLE_PREFIX . 'company_saler';
$g5['customer_table']               = NEW_TABLE_PREFIX . 'customer';
$g5['customer_member_table']        = NEW_TABLE_PREFIX . 'customer_member';
$g5['code_table']                   = NEW_TABLE_PREFIX . 'code';
$g5['tag_code_table']               = NEW_TABLE_PREFIX . 'tag_code';
$g5['imp_table']                    = NEW_TABLE_PREFIX . 'imp';
$g5['mms_table']                    = NEW_TABLE_PREFIX . 'mms';
$g5['mms_group_table']              = NEW_TABLE_PREFIX . 'mms_group';
$g5['mms_parts_table']              = NEW_TABLE_PREFIX . 'mms_parts';
$g5['mms_checks_table']             = NEW_TABLE_PREFIX . 'mms_checks';
$g5['mms_status_table']             = NEW_TABLE_PREFIX . 'mms_status';
$g5['mms_item_table']               = NEW_TABLE_PREFIX . 'mms_item';
$g5['mms_item_price_table']         = NEW_TABLE_PREFIX . 'mms_item_price';
$g5['maintain_table']               = NEW_TABLE_PREFIX . 'maintain';
$g5['maintain_parts_table']         = NEW_TABLE_PREFIX . 'maintain_parts';
$g5['maintain_suggest_table']       = NEW_TABLE_PREFIX . 'maintain_suggest';
$g5['shift_table']                  = NEW_TABLE_PREFIX . 'shift';
$g5['shift_item_goal_table']        = NEW_TABLE_PREFIX . 'shift_item_goal';
$g5['data_table']                   = NEW_TABLE_PREFIX . 'data';
$g5['data_measure_table']           = NEW_TABLE_PREFIX . 'data_measure';
$g5['data_measure_best_table']      = NEW_TABLE_PREFIX . 'data_measure_best';
$g5['data_measure_sum_table']       = NEW_TABLE_PREFIX . 'data_measure_sum';
$g5['data_error_table']             = NEW_TABLE_PREFIX . 'data_error';
$g5['data_error_sum_table']         = NEW_TABLE_PREFIX . 'data_error_sum';
$g5['data_run_table']               = NEW_TABLE_PREFIX . 'data_run';
$g5['data_run_sum_table']           = NEW_TABLE_PREFIX . 'data_run_sum';
$g5['data_run_real_table']          = NEW_TABLE_PREFIX . 'data_run_real';
$g5['data_output_table']            = NEW_TABLE_PREFIX . 'data_output';
$g5['data_output_sum_table']        = NEW_TABLE_PREFIX . 'data_output_sum';
$g5['data_downtime_table']          = NEW_TABLE_PREFIX . 'data_downtime';
$g5['member_dash_table']            = NEW_TABLE_PREFIX . 'member_dash';
$g5['dash_grid_table']              = NEW_TABLE_PREFIX . 'dash_grid';
$g5['alarm_table']                  = NEW_TABLE_PREFIX . 'alarm';
$g5['offwork_table']                = NEW_TABLE_PREFIX . 'offwork';
$g5['alarm_send_table']             = NEW_TABLE_PREFIX . 'alarm_send';
$g5['alarm_tag_table']              = NEW_TABLE_PREFIX . 'alarm_tag';
$g5['alarm_tag_send_table']         = NEW_TABLE_PREFIX . 'alarm_tag_send';
$g5['cost_config_table']            = NEW_TABLE_PREFIX . 'cost_config';

$g5['bom_table']                    = NEW_TABLE_PREFIX . 'bom';
$g5['bom_jig_table']                = NEW_TABLE_PREFIX . 'bom_jig';
$g5['bom_category_table']           = NEW_TABLE_PREFIX . 'bom_category';
$g5['bom_price_table']              = NEW_TABLE_PREFIX . 'bom_price';
$g5['bom_part_no_table']            = NEW_TABLE_PREFIX . 'bom_part_no';
$g5['bom_backup_table']             = NEW_TABLE_PREFIX . 'bom_backup';
$g5['bom_item_table']               = NEW_TABLE_PREFIX . 'bom_item';
$g5['return_table']                 = NEW_TABLE_PREFIX . 'return';
// $g5['mms_worker_table']             = NEW_TABLE_PREFIX . 'mms_worker';
$g5['bom_mms_worker_table']         = NEW_TABLE_PREFIX . 'bom_mms_worker';
$g5['order_item_table']             = NEW_TABLE_PREFIX . 'order_item';
$g5['shipment_table']               = NEW_TABLE_PREFIX . 'shipment';
$g5['production_table']             = NEW_TABLE_PREFIX . 'production';
$g5['production_main_table']        = NEW_TABLE_PREFIX . 'production_main';
$g5['production_item_table']        = NEW_TABLE_PREFIX . 'production_item';
$g5['production_item_count_table']  = NEW_TABLE_PREFIX . 'production_item_count';
$g5['production_member_table']      = NEW_TABLE_PREFIX . 'production_member';
$g5['material_table']               = NEW_TABLE_PREFIX . 'material';
$g5['material_order_table']         = NEW_TABLE_PREFIX . 'material_order';
$g5['material_order_item_table']    = NEW_TABLE_PREFIX . 'material_order_item';
$g5['item_table']                   = NEW_TABLE_PREFIX . 'item';
$g5['item_sum_table']               = NEW_TABLE_PREFIX . 'item_sum';
$g5['material_item_table']          = NEW_TABLE_PREFIX . 'material_item';
$g5['member_device_table']          = NEW_TABLE_PREFIX . 'member_device';
$g5['msg_log_table']                = NEW_TABLE_PREFIX . 'msg_log';
$g5['pallet_table']                 = NEW_TABLE_PREFIX . 'pallet';
$g5['material_table']               = NEW_TABLE_PREFIX . 'material';
$g5['bom_customer_table']           = NEW_TABLE_PREFIX . 'bom_customer';
$g5['plc_protocol_table']           = NEW_TABLE_PREFIX . 'plc_protocol';
$g5['quality_table']                = NEW_TABLE_PREFIX . 'quality';

$g5['crontab_log_table']           = NEW_TABLE_PREFIX . 'crontab_log';
$g5['v_bom_item_table']             = NEW_TABLE_PREFIX . 'v_bom_item';
