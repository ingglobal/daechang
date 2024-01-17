디비 export (mysql접속하지 않고 명령어 실행)
# mysqldump -u root -psuper@ingglobal* daechang_www > /tmp/daechang_www.sql


디비 삭제하고 재생성
# mysql -u root -psuper@ingglobal*
> DROP DATABASE daechang_test;
> CREATE DATABASE daechang_test
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;


디비 import (mysql접속한다)
# mysql -u root -psuper@ingglobal*
> USE daechang_test;
> source /tmp/daechang_www.sql;


임지 저장한 /tmp/daechang_www.sql을 삭제
# rm -rf /tmp/daechang_www.sql

#####################################
alter table g5_1_shipment add column prd_idx int(11) null default 0 comment '수주생산idx' after ori_idx;
alter table g5_1_production add column boc_idx int(11) null default 0 comment 'BOM고객처idx' after com_idx;


UPDATE g5_1_production prd
    LEFT JOIN g5_1_order_item ori ON prd.ori_idx = ori.ori_idx
SET prd_value = ori_count


UPDATE g5_1_production prd
    LEFT JOIN g5_1_order_item ori ON prd.ori_idx = ori.ori_idx
SET prd_date = ori_date


UPDATE g5_1_shipment shp 
    LEFT JOIN g5_1_production prd ON shp.ori_idx = prd.ori_idx
SET shp.boc_idx = prd.boc_idx


UPDATE g5_1_production prd 
    LEFT JOIN g5_1_order_item ori ON prd.ori_idx = ori.ori_idx
    LEFT JOIN g5_1_bom_customer boc ON ori.bom_idx = boc.bom_idx
        AND ori.cst_idx = boc.cst_idx
SET prd.boc_idx = boc.boc_idx;


UPDATE g5_1_production
    SET boc_idx = 0 WHERE boc_idx IS NULL


UPDATE g5_1_shipment shp 
    LEFT JOIN g5_1_bom_customer boc ON shp.cst_idx = boc.cst_idx
        AND shp.bom_idx = boc.bom_idx
SET shp.boc_idx = boc.boc_idx

UPDATE g5_1_production
SET prd_done_date = DATE_ADD(prd_start_date, INTERVAL 12 DAY)


SELECT boc.cst_idx, cst_name, boc_type FROM g5_1_bom_customer boc 
LEFT JOIN g5_1_customer cst ON boc.cst_idx = cst.cst_idx 
WHERE boc_type = 'customer' AND boc.cst_idx != 0
GROUP BY boc.cst_idx
ORDER BY cst_name


SELECT pri_idx 
        , pri.prd_idx 
        , pri.boc_idx
        , pri.bom_idx 
        , bom_part_no 
        , bom_name 
        , bom_type 
        , prd_value 
        , pri_order_no 
        , pri_date 
        , SUM(pri_value) AS pri_value 
        , pri_status 
    FROM g5_1_production_item pri 
    LEFT JOIN g5_1_production prd ON pri.prd_idx = prd.prd_idx 
    LEFT JOIN g5_1_bom bom ON pri.bom_idx = bom.bom_idx 
WHERE pri_status NOT IN ('trash','delete') 
    AND pri.com_idx = '13' 
    AND prd.bom_idx = pri.bom_idx 
GROUP BY pri.prd_idx, pri.bom_idx, pri_date 
ORDER BY pri.pri_date desc, pri.pri_idx desc 
LIMIT 0, 15



g5_1_production_item테이블의 prd_idx 필드의 값이 g5_1_production테이블의 prd_idx필드의 값 중에 동일한 값이 없으면
g5_1_production_item테이블의 prd_idx 필등의 값을 0으로 수정하는 쿼리를 만들어줘
    



UPDATE g5_1_production_item pri 
    LEFT JOIN g5_1_production prd ON pri.prd_idx = prd.prd_idx 
    LEFT JOIN g5_1_bom bom ON pri.bom_idx = bom.bom_idx 
SET pri_date = prd_start_date
WHERE pri_status NOT IN ('trash','delete') 
    AND pri.com_idx = '13' 
    AND bom.bom_type = 'product'



select count(*) AS cnt from ( 
        select count(*) as cnt FROM g5_1_production_item pri 
            LEFT JOIN g5_1_production prd ON pri.prd_idx = prd.prd_idx 
            LEFT JOIN g5_1_bom bom ON pri.bom_idx = bom.bom_idx 
        WHERE pri_status NOT IN ('trash','delete') 
            AND pri.com_idx = '13' 
            AND bom.bom_type = 'product' pri.prd_idx, pri.bom_idx, pri_date ) scnt


UPDATE g5_1_production_item pri 
    LEFT JOIN g5_1_production prd ON pri.prd_idx = prd.prd_idx 
SET bom_idx_parent = prd.bom_idx
WHERE pri_status NOT IN ('trash','delete') 
    AND pri.com_idx = '13' 
    AND pri.prd_idx = prd.prd_idx


UPDATE g5_1_production_item pri 
    LEFT JOIN g5_1_production prd ON pri.prd_idx = prd.prd_idx 
SET pri.pri_order_no = CONCAT(prd.prd_order_no,prd.bom_idx,pri.pri_date)
WHERE pri_status NOT IN ('trash','delete') 
    AND pri.com_idx = '13' 
    AND pri.prd_idx = prd.prd_idx



UPDATE g5_1_production_item pri 
    LEFT JOIN g5_1_production prd ON pri.prd_idx = prd.prd_idx 
SET pri.pri_order_no = CONCAT(prd.prd_order_no, prd.bom_idx, REPLACE(pri.pri_date, '-', ''))
WHERE pri_status NOT IN ('trash', 'delete') 
    AND pri.com_idx = '13' 
    AND pri.prd_idx = prd.prd_idx





SELECT * FROM ( 
    (
    SELECT bom.bom_idx 
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
    FROM g5_1_bom bom 
    LEFT JOIN g5_1_bom_customer boc ON bom.bom_idx = boc.bom_idx 
                                    AND boc.boc_type = 'customer' 
    WHERE bom.bom_idx = '413'
    )
    UNION ALL 
    (
    SELECT bom.bom_idx 
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
    FROM g5_1_bom_item bot 
    LEFT JOIN g5_1_bom bom ON bot.bom_idx_child = bom.bom_idx 
    LEFT JOIN g5_1_bom_customer boc ON bom.bom_idx = boc.bom_idx 
                                    AND boc.boc_type = 'provider' 
    LEFT JOIN g5_1_customer cst ON boc.cst_idx = cst.cst_idx 
    WHERE bot.bom_idx = '413' 
    ORDER BY bot.bit_reply 
    )
) db1 
ORDER BY bit_reply


50호기 , 89311-S8000 떠이2(주), 코요(야)

INSERT INTO g5_1_bom_mms_worker (bom_idx, mms_idx, mb_id, bmw_type, bmw_sort, bmw_status, bmw_reg_dt, bmw_update_dt) VALUES 
(229, 140, '01025141739', 'day', 1, 'ok', NOW(), NOW())
,(229, 140, '01073861823', 'night', 2, 'ok', NOW(), NOW())



DELETE FROM g5_1_bom_mms_worker WHERE bmw_idx IN (139, 140);
ALTER TABLE g5_1_bom_mms_worker AUTO_INCREMENT=139;




SELECT GROUP_CONCAT(mb_id) AS mb_ids
    , GROUP_CONCAT(mms_idx) AS mms_idxs
    , GROUP_CONCAT(bom_idx) AS bom_idxs 
FROM g5_1_bom_mms_worker 
    WHERE bom_idx = 120
        AND bmw_type IN('day','night')
        AND bmw_status = 'ok'
        AND bmw_main_yn = 1
    ORDER BY mms_idx, bmw_type
    LIMIT 2


//LX2완제품 추출
SELECT bct_idx
    , bom_idx
    , bom_part_no
FROM g5_1_bom
WHERE com_idx = 13
    AND bom_type = 'product'
    AND bom_status = 'ok'
    AND bct_idx IN (15,16)

//LX2완제품 설비 작업자 추출
SELECT bom_idx
    , GROUP_CONCAT(mms_idx) AS mms_idxs
    , GROUP_CONCAT(mb_id) AS mb_ids 
    , GROUP_CONCAT(bmw_type) AS bmw_types
FROM g5_1_bom_mms_worker
WHERE bmw_type IN ('day','night')
    AND bmw_status = 'ok'
    AND bmw_main_yn = 1
GROUP BY bom_idx
ORDER BY mms_idx, bmw_type


SELECT bct_idx
    , bom.bom_idx
    , bom_part_no
    , mms_idxs
    , mb_ids 
    , bmw_types
FROM g5_1_bom bom
LEFT JOIN (
    SELECT bom_idx
        , GROUP_CONCAT(mms_idx) AS mms_idxs
        , GROUP_CONCAT(mb_id) AS mb_ids 
        , GROUP_CONCAT(bmw_type) AS bmw_types
    FROM g5_1_bom_mms_worker
    WHERE bmw_type IN ('day','night')
        AND bmw_status = 'ok'
        AND bmw_main_yn = 1
    GROUP BY bom_idx
    ORDER BY mms_idx, bmw_type
) bmw ON bom.bom_idx = bmw.bom_idx
WHERE com_idx = 13
    AND bom_type = 'product'
    AND bom_status = 'ok'
    AND bct_idx IN (15,16)




SELECT bct.bct_idx
    , bct.bct_name
    , bmw.bom_idx
    , bom.bom_part_no
    , bom.bom_name
    , bom.bom_type
    , GROUP_CONCAT(mms_idx) AS mms_idxs
    , GROUP_CONCAT(mb_id) AS mb_ids 
    , GROUP_CONCAT(bmw_type) AS bmw_types
    , pbc.bct_name
    , pbm.bom_idx AS pbom_idx 
    , pbm.bom_part_no AS pbom_part_no
    , pbm.bom_type AS pbom_type
FROM g5_1_bom_mms_worker bmw
LEFT JOIN g5_1_bom bom ON bmw.bom_idx = bom.bom_idx
LEFT JOIN g5_1_bom_category bct ON bom.bct_idx = bct.bct_idx
LEFT JOIN g5_1_bom_item bot ON bmw.bom_idx = bot.bom_idx_child
LEFT JOIN g5_1_bom pbm ON bot.bom_idx = pbm.bom_idx
LEFT JOIN g5_1_bom_category pbc ON pbm.bct_idx = pbc.bct_idx
WHERE bmw_type IN ('day','night')
    AND bmw_status = 'ok'
    AND bmw_main_yn = 1
GROUP BY bom_idx
ORDER BY mms_idx, bmw_type


CREATE TABLE `g5_1_production_main` (
  `prm_idx` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `com_idx` int(11) NOT NULL DEFAULT 0 COMMENT '업체ID',
  `prd_idx` int(11) NOT NULL DEFAULT 0 COMMENT '생산계획ID',
  `bom_idx` int(11) NOT NULL DEFAULT 0 COMMENT '완제품idx',
  `boc_idx` int(11) DEFAULT 0 COMMENT 'BOM고객처idx',
  `prm_order_no` varchar(255) NOT NULL COMMENT '지시번호',
  `prm_date` date DEFAULT '0000-00-00' COMMENT '생산일',
  `prm_value` int(11) DEFAULT 0 COMMENT '지시수량',
  `prm_memo` text DEFAULT NULL COMMENT '메모',
  `prm_status` varchar(20) DEFAULT 'pending' COMMENT '상태',
  `prm_reg_dt` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '등록일시',
  `prm_update_dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '수정일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `g5_5_setting` (`set_idx`, `set_country`, `com_idx`, `set_key`, `set_name`, `set_value`, `set_auto_yn`) VALUES
(294, '', 0, 'site', 'set_prm_status', 'pending=대기,confirm=확정,done=완료', 1);



SELECT GROUP_CONCAT(mb_id) AS mb_ids
                    , GROUP_CONCAT(mms_idx) AS mms_idxs 
FROM g5_1_bom_mms_worker 
WHERE bom_idx = 373
    AND bmw_type IN('day','night')
    AND bmw_status = 'ok'
    AND bmw_main_yn = 1
ORDER BY mms_idx, bmw_type
LIMIT 2


SELECT bit_idx 
    , bom_idx
    , bom_idx_child 
    , bit_count 
    , bit_main_yn 
    , bit_reply 
FROM g5_1_bom_item
WHERE bom_idx = '429' 
ORDER BY bit_reply



SELECT GROUP_CONCAT(mb_id) AS mb_ids
        , GROUP_CONCAT(mms_idx) AS mms_idxs 
FROM g5_1_bom_mms_worker
WHERE bom_idx = 154
    AND bmw_type IN('day','night')
    AND bmw_status = 'ok'
    AND bmw_main_yn = 1
ORDER BY mms_idx, bmw_type
LIMIT 2


SELECT prm_idx FROM g5_1_production_main
WHERE bom_idx = '449'
    AND boc_idx = '222'
    AND prm_date = '2024-01-01'
    AND prm_status NOT IN ('trash','delete')
LIMIT 1