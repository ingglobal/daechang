This is james work memo.

CREATE TABLE `g5_1_data_measure_58` (
  `dta_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `dta_type` tinyint(4) NOT NULL COMMENT '전류,전압등',
  `dta_no` int(11) NOT NULL DEFAULT '0' COMMENT '측정번호',
  `dta_value` double DEFAULT '0' COMMENT '데이터값',
  `dta_datetime` int(11) DEFAULT NULL COMMENT '일시 timestamp',
  `dta_dt` int(11) DEFAULT NULL COMMENT '일시 datetime',
  PRIMARY KEY (`dta_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_return` (
  `ret_idx` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '불량idx',
  `ret_ym` date DEFAULT '0000-00-00' COMMENT '년월',
  `ret_type` varchar(20) DEFAULT '' NOT NULL COMMENT '구분',
  `ret_count` int(11) NOT NULL DEFAULT '0' COMMENT '갯수',
  `ret_reg_dt` int(11) DEFAULT NULL COMMENT '등록일',
  `ret_update_dt` int(11) DEFAULT NULL COMMENT '수정일',
  PRIMARY KEY (`ret_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_cast_shot_sub` (
  `css_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `shot_id` int(11) NOT NULL COMMENT '샷ID',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '발생시각',
  `hold_temp` double NOT NULL COMMENT '보온로온도',
  `upper_heat` double NOT NULL COMMENT '상형히트',
  `lower_heat` double NOT NULL COMMENT '하형히트',
  `upper_1_temp` double NOT NULL COMMENT '상금형1',
  `upper_2_temp` double NOT NULL COMMENT '상금형2',
  `upper_3_temp` double NOT NULL COMMENT '상금형3',
  `upper_4_temp` double NOT NULL COMMENT '상금형4',
  `upper_5_temp` double NOT NULL COMMENT '상금형5',
  `upper_6_temp` double NOT NULL COMMENT '상금형6',
  `lower_1_temp` double NOT NULL COMMENT '하금형1',
  `lower_2_temp` double NOT NULL COMMENT '하금형2',
  `lower_3_temp` double NOT NULL COMMENT '하금형3',
  PRIMARY KEY (`css_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_cast_shot` (
  `csh_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `shot_id` int(11) NOT NULL COMMENT '샷ID',
  `work_date` date DEFAULT CURRENT_TIMESTAMP COMMENT '작업일',
  `work_shift` int(11) NOT NULL COMMENT '주(1)/야(2)',
  `start_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '시작시각',
  `end_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '종료시각',
  `elapsed_time` int(11) NOT NULL COMMENT '경과시간',
  `machine_id` double NOT NULL COMMENT '설비ID',
  `machine_no` double NOT NULL COMMENT '설비번호',
  `item_no` double noT NULL COMMENT '금형번호',
  `item_name` double NOT NULL COMMENT '제품명',
  `mold_no` double noT NULL COMMENT '금형번호',
  `shot_no` int(11) nOT NULL COMMENT '샷번호',
  `pv_cycletime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'PV사이클타임',
  `machine_cycletime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '설비사이클타임',
  `product_cycletime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '제품사이클타임',
  `hold_temp` double NOT NULL COMMENT '보온로온도',
  `upper_heat` double NOT NULL COMMENT '상형히트',
  `lower_heat` double NOT NULL COMMENT '하형히트',
  `upper_1_temp` double NOT NULL COMMENT '상금형1',
  `upper_2_temp` double NOT NULL COMMENT '상금형2',
  `upper_3_temp` double NOT NULL COMMENT '상금형3',
  `upper_4_temp` double NOT NULL COMMENT '상금형4',
  `upper_5_temp` double NOT NULL COMMENT '상금형5',
  `upper_6_temp` double NOT NULL COMMENT '상금형6',
  `lower_1_temp` double NOT NULL COMMENT '하금형1',
  `lower_2_temp` double NOT NULL COMMENT '하금형2',
  `lower_3_temp` double NOT NULL COMMENT '하금형3',
  PRIMARY KEY (`csh_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_xray_inspection` (
  `xry_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_date` date DEFAULT CURRENT_TIMESTAMP COMMENT '작업일',
  `work_shift` int(11) NOT NULL COMMENT '주(1)/야(2)',
  `start_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '시작시각',
  `end_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '종료시각',
  `qrcode` int(11) NOT NULL COMMENT 'QRCode',
  `production_ID` int(11) NOT NULL COMMENT '생산품ID',
  `machine_id` double NOT NULL COMMENT '설비ID',
  `machine_no` double NOT NULL COMMENT '설비번호',
  `position_1` double NOT NULL COMMENT '위치1',
  `position_2` double NOT NULL COMMENT '위치2',
  `position_3` double NOT NULL COMMENT '위치3',
  `position_4` double NOT NULL COMMENT '위치4',
  `position_5` double NOT NULL COMMENT '위치5',
  `position_6` double NOT NULL COMMENT '위치6',
  `position_7` double NOT NULL COMMENT '위치7',
  `position_8` double NOT NULL COMMENT '위치8',
  `position_9` double NOT NULL COMMENT '위치9',
  `position_10` double NOT NULL COMMENT '위치10',
  `position_11` double NOT NULL COMMENT '위치11',
  `position_12` double NOT NULL COMMENT '위치12',
  `position_13` double NOT NULL COMMENT '위치13',
  `position_14` double NOT NULL COMMENT '위치14',
  `position_15` double NOT NULL COMMENT '위치15',
  `position_16` double NOT NULL COMMENT '위치16',
  `position_17` double NOT NULL COMMENT '위치17',
  `position_18` double NOT NULL COMMENT '위치18',
  PRIMARY KEY (`xry_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_charge_in` (
  `chi_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_date` date DEFAULT CURRENT_TIMESTAMP COMMENT '작업일',
  `work_shift` int(11) DEFAULT 0 NOT NULL COMMENT '주(1)/야(2)',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '발생시각',
  `weight_total` double NOT NULL COMMENT '총장입량',
  `weight_ingot` double NOT NULL COMMENT '인고트 장입량',
  `weight_scrap` double NOT NULL COMMENT '스크랩 장입량',
  PRIMARY KEY (`chi_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_charge_out` (
  `cho_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_date` date DEFAULT CURRENT_TIMESTAMP COMMENT '작업일',
  `work_shift` int(11) NOT NULL COMMENT '주(1)/야(2)',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '발생시각',
  `weight_out` int(11) DEFAULT 0 NOT NULL COMMENT '출탕량',
  `temp_out` int(11) DEFAULT 0 NOT NULL COMMENT '출탕온도',
  `temp_gbf` int(11) DEFAULT 0 NOT NULL COMMENT 'GBF 후 온도',
  `machine_1_id` int(11) DEFAULT 0 NOT NULL COMMENT '배탕주조기 1 ID',
  `machine_1_no` varchar(20) DEFAULT '' NOT NULL COMMENT '배탕주조기 1 번호',
  `machine_2_id` int(11) DEFAULT 0 NOT NULL COMMENT '배탕주조기 2 ID',
  `machine_2_no` varchar(20) DEFAULT '' NOT NULL COMMENT '배탕주조기 2 번호',
  `machine_3_id` int(11) DEFAULT 0 NOT NULL COMMENT '배탕주조기 3 ID',
  `machine_3_no` varchar(20) DEFAULT '' NOT NULL COMMENT '배탕주조기 3 번호',
  PRIMARY KEY (`cho_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_melting_temp` (
  `mlt_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_date` date DEFAULT CURRENT_TIMESTAMP COMMENT '작업일',
  `work_shift` int(11) NOT NULL COMMENT '주(1)/야(2)',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '발생시각',
  `temp_avg` int(11) DEFAULT 0 NOT NULL COMMENT '평균온도',
  `temp_min` int(11) DEFAULT 0 NOT NULL COMMENT '최소온도',
  `temp_max` int(11) DEFAULT 0 NOT NULL COMMENT '최대온도',
  `alarm_min` int(11) DEFAULT 0 NOT NULL COMMENT '최소알람기준',
  `alarm_max` varchar(20) DEFAULT '' NOT NULL COMMENT '최대알람기준',
  PRIMARY KEY (`mlt_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_cast_shot_pressure` (
  `csr_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `shot_id` int(11) NOT NULL COMMENT '샷ID',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '발생시각',
  `detect_pressure` int(11) DEFAULT 0 NOT NULL COMMENT '검출',
  `target_pressure` int(11) DEFAULT 0 NOT NULL COMMENT '목표',
  `control_pressure` int(11) DEFAULT 0 NOT NULL COMMENT '조작',
  `deviation_pressure` int(11) DEFAULT 0 NOT NULL COMMENT '편차',
  PRIMARY KEY (`csr_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_engrave_qrcode` (
  `eqr_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_date` date DEFAULT CURRENT_TIMESTAMP COMMENT '작업일',
  `work_shift` int(11) NOT NULL COMMENT '주(1)/야(2)',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '각인시각',
  `qrcode` varchar(30) DEFAULT '' NOT NULL COMMENT 'qrcode',
  `production_id` int(11) DEFAULT 0 NOT NULL COMMENT '생산품ID',
  PRIMARY KEY (`eqr_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `g5_1_factory_temphum` (
  `fct_idx` bigint(20) NOT NULL AUTO_INCREMENT,
  `work_date` date DEFAULT '0000-00-00' COMMENT '작업일',
  `work_shift` int(11) DEFAULT 0 NOT NULL COMMENT '주(1)/야(2)',
  `event_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '발생시각',
  `temp_avg` double NOT NULL COMMENT '평균온도',
  `temp_max` double NOT NULL COMMENT '온도최대',
  `temp_min` double NOT NULL COMMENT '온도최소',
  `hum_avg` double NOT NULL COMMENT '습도평균',
  `hum_max` double NOT NULL COMMENT '습도최대',
  `hum_min` double NOT NULL COMMENT '습도최소',
  PRIMARY KEY (`fct_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




2020-11-02 일부터 시작한 것으로 판단됨

SELECT * FROM MES_CAST_SHOT_SUB WHERE EVENT_TIME < '2020-11-02 00:00:00.000' ORDER BY EVENT_TIME LIMIT 1
SELECT * FROM MES_CAST_SHOT_SUB WHERE EVENT_TIME > '2022-04-16 04:25:39.615'


SELECT event_time FROM g5_1_cast_shot_sub ORDER BY event_time LIMIT 1

// 온도 정보 하루 전체 복제
INSERT INTO g5_1_cast_shot_sub(shot_id, event_time, hold_temp, upper_heat,lower_heat,upper_1_temp,upper_2_temp,upper_3_temp,upper_4_temp,upper_5_temp,upper_6_temp,lower_1_temp,lower_2_temp,lower_3_temp)
SELECT shot_id, CONCAT('2020-11-09',' ',SUBSTRING(event_time,12)) AS event_time, hold_temp, upper_heat,lower_heat
    ,upper_1_temp,upper_2_temp,upper_3_temp,upper_4_temp,upper_5_temp,upper_6_temp,lower_1_temp
    ,lower_2_temp,lower_3_temp
FROM g5_1_cast_shot_sub WHERE event_time LIKE '2020-11-02%'

// 삭제 현재 1091027 (~0220-11-07)
DELETE FROM g5_1_cast_shot_sub WHERE event_time LIKE '2020-11-09%'
DELETE FROM g5_1_cast_shot_sub WHERE event_time > '2020-11-09 00:00:00'
DELETE FROM g5_1_cast_shot_sub WHERE event_time >= '2022-04-13 00:00:00'

update `g5_1_cast_shot_sub` set shot_id = mcs_idx


// 압력 정보 하루 전체 복제
INSERT INTO g5_1_cast_shot_pressure(shot_id, event_time, detect_pressure, target_pressure,control_pressure,deviation_pressure)
SELECT shot_id, CONCAT('2020-11-04',' ',SUBSTRING(event_time,12)) AS event_time, detect_pressure, target_pressure,control_pressure,deviation_pressure
FROM g5_1_cast_shot_pressure WHERE event_time LIKE '2020-11-02%'
....
INSERT INTO g5_1_cast_shot_pressure(shot_id, event_time, detect_pressure, target_pressure,control_pressure,deviation_pressure)
SELECT shot_id, CONCAT('2020-11-05',' ',SUBSTRING(event_time,12)) AS event_time, detect_pressure, target_pressure,control_pressure,deviation_pressure
FROM g5_1_cast_shot_pressure WHERE event_time LIKE '2020-11-03%'
....
INSERT INTO g5_1_cast_shot_pressure(shot_id, event_time, detect_pressure, target_pressure,control_pressure,deviation_pressure)
SELECT shot_id, CONCAT('2020-11-06',' ',SUBSTRING(event_time,12)) AS event_time, detect_pressure, target_pressure,control_pressure,deviation_pressure
FROM g5_1_cast_shot_pressure WHERE event_time LIKE '2020-11-04%'
....


$ influx -import -path=ticker_data.txt -database=market -percision=s




SELECT * FROM g5_1_cast_shot_sub WHERE event_time >= '2020-11-02 00:00:00' AND event_time <= '2020-11-02 23:59:59'

INFLUXDB 성능 개선 작업
https://yenaworldblog.wordpress.com/2017/11/09/influxdb-%EC%84%B1%EB%8A%A5-%EA%B0%9C%EC%84%A0-%EC%9E%91%EC%97%85/
1. CQ 사용
    비싼 쿼리문을 날리는 경우에 (aggregation 같은 경우) 모든 시간에 대해 쿼리 하기에 오래걸림
    미리 쿼리 계산을 해서 별도의 measurement에 데이터를 넣어서 기간 별로 모든 데이터를 계산하지 않고 미리 계산된 데이터만 뽑아오는 방식으로 사용 가능
2. RP 사용
    retention policy 보존정책을 정해서 오래된 데이터들을 삭제함
    데이터 셋이 줄어들기 때문에 (down sampling) 검색해야 하는 속도가 빨라짐
    사용하지 않은 데이터들을 삭제하는 정책으로 influxdb query 속도를 높일 수 있음
    주의!! 중간에 보존 정책을 바꾸면 그동안에 쌓은 데이터가 다 날아갈 위험이 있음
    create/drop 하지말고 alter 로 변경 작업을 해야지 서비스 중인 데이터가 안전!!
    default vs DEFAULT
    default: InfluxDB가 새 데이터베이스를 만들 때 자동으로 생성하는 RP의 이름. 무한한 지속 기간과 복제 계수를 1로 설정함. 처음에는 DEFAULTRP 였지만 변경 될 수 있음
    DEFAULT: 쓰기에서 명시 적 RP를 제공하지 않으면 InfluxDB가 쓰는 RP

InfluxDB 테스트 및 Machbase와의 비교
https://kr.machbase.com/influxdb-%ED%85%8C%EC%8A%A4%ED%8A%B8-%EB%B0%8F-machbase%EC%99%80%EC%9D%98-%EB%B9%84%EA%B5%90/


[공식 문서 정리] InfluxDB와 SQL 데이터베이스의 비교
명령어 정리가 잘 되어 있네.
Flux
InfluxQL


from(bucket: "ing")
  |> range(start: v.timeRangeStart, stop: v.timeRangeStop)
//  |> range(start: 2021-01-01T00:00:00Z, stop: 2021-01-01T12:00:00Z)
  |> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")
....
from(bucket: "ing")
  |> range(start: 2021-01-01T00:00:00Z, stop: 2021-01-01T12:00:00Z)
  // range(start: 1527031800, stop: 1527033600)
  |> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")
....
from(bucket: "ing")
//  |> range(start: v.timeRangeStart, stop: v.timeRangeStop)
  |> range(start: 2020-11-02T08:15:42+09:00, stop: 2020-11-02T10:55:42+09:00)
//   |> range(start: 2020-11-02T08:15:42Z, stop: 2020-11-02T10:55:42Z)
  |> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")
....
from(bucket: "ing")
  |> range(start: 2020-11-02T08:15:42+09:00, stop: 2020-11-02T10:55:42+09:00)
  |> filter(fn: (r) => r._measurement == "cast_shot_sub")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")
....
from(bucket: "ing")
  |> range(start: v.timeRangeStart, stop: v.timeRangeStop)
  |> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
  |> aggregateWindow(every: v.windowPeriod, fn: mean, createEmpty: false)
  |> yield(name: "mean")
....
from(bucket: "ing")
  |> range(start: 2020-11-02T08:15:42+09:00, stop: 2020-11-02T10:55:42+09:00)
  |> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")
....
from(bucket: "ing")
  |> range(start: 2020-11-02T08:15:42+09:00, stop: 2020-11-02T10:55:42+09:00)
  |> filter(fn: (r) => r["_measurement"] == "cast_shot_sub" and r["_field"] == "hold_temp")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")
....
from(bucket: "ing")
|> range(start: v.timeRangeStart)
|> keyValues(keyColumns: ["shot_id"])
|> group()



List fields in a measurement
....
import "influxdata/influxdb/schema"

schema.measurementFieldKeys(
    bucket: "ing",
    measurement: "cast_shot_sub",
)
....
List tag keys in a measurement
Use the schema.measurementTagKeys function to list tag keys in a measurement. This function returns results from the last 30 days.
....
import "influxdata/influxdb/schema"

schema.measurementTagKeys(
    bucket: "ing",
    measurement: "cast_shot_sub",
)


List tag values in a measurement
Use the schema.measurementTagValues function to list tag values for a given tag in a measurement. This function returns results from the last 30 days.
....
import "influxdata/influxdb/schema"

schema.measurementTagValues(
    bucket: "ing",
    tag: "shot_id",
    measurement: "cast_shot_sub",
)
....


from(bucket: "ing")
|> range(start: 2020-11-02T08:15:42+09:00, stop: 2020-11-02T10:55:42+09:00)
|> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
|> count()
....
from(bucket: "mes")
|> range(start: 2020-11-02T08:15:42+09:00, stop: 2020-11-02T10:55:42+09:00)
|> filter(fn: (r) => r["_measurement"] == "cast_shot_sub")
|> count()


influxDB vs TimescaleDB
https://tzara.tistory.com/117


psql "postgres://tsdbadmin@vbjysvzz2g.hp6tz73i1r.tsdb.cloud.timescale.com:32530/tsdb?sslmode=require"
^^tiA**e@@칠.....

SELECT ROUND(AVG(fill_level), 2) AS avg_fill_level
    , time_bucket('10m',time) AS bucket
    , sensors.country_code, sensors.machine_id
FROM fill_measurements, sensors
WHERE sensor_id = sensors.id
    AND time >= '2021-04-05 02:00' AND time >= '2021-04-05 3:00'
GROUP BY bucket, sensors.country_code, sensors.machine_id
HAVING ROUND(AVG(fill_level),2) >= 251;


CREATE TABLE test1 (
    time        TIMESTAMPTZ         NOT NULL,
    location    text                NOT NULL,
    temperature DOUBLE PRECISION    NULL
);

CREATE TABLE test4 (
  mcs_idx SERIAL PRIMARY KEY,
  shot_id integer NOT NULL,
  event_time TIMESTAMPTZ    NOT NULL,
  hold_temp DOUBLE PRECISION    NULL,
  upper_heat DOUBLE PRECISION    NULL,
  lower_heat DOUBLE PRECISION    NULL,
  upper_1_temp DOUBLE PRECISION    NULL,
  upper_2_temp DOUBLE PRECISION    NULL,
  upper_3_temp DOUBLE PRECISION    NULL,
  upper_4_temp DOUBLE PRECISION    NULL,
  upper_5_temp DOUBLE PRECISION    NULL,
  upper_6_temp DOUBLE PRECISION    NULL,
  lower_1_temp DOUBLE PRECISION    NULL,
  lower_2_temp DOUBLE PRECISION    NULL,
  lower_3_temp DOUBLE PRECISION    NULL
);

SELECT create_hypertable('test1', 'event_time');
SELECT create_hypertable('test2', 'event_time');
SELECT create_hypertable('test4', 'event_time');

timescaledb-parallel-copy --db-name new_db --table conditions --file old_db.csv --workers 4 --copy-options "CSV"
timescaledb-parallel-copy --db-name test_db --table test1 --file /var/www/kr.websiteman.py/php/timescale/test1.csv --workers 4 --copy-options "CSV"
timescaledb-parallel-copy user=postgres password=db@ypage2018 --db-name test_db --table test1 --file /var/www/kr.websiteman.py/php/timescale/test1.csv --workers 4 --copy-options "CSV"
timescaledb-parallel-copy -connection "host=localhost user=postgres password=db@ypage2018 sslmode=disable" --db-name test_db --table test1 --file /var/www/kr.websiteman.py/php/timescale/test1.csv --workers 4 --copy-options "CSV"

timescaledb-parallel-copy \
--connection host=localhost user=postgres password=db@ypage2018 sslmode=disable \
--db-name test_db --schema web --table test1 \
--file /var/www/kr.websiteman.py/php/timescale/test1.csv --workers 4 --reporting-period 30s --copy-options "CSV"

timescaledb-parallel-copy \
--connection host=localhost user=postgres password=db@ypage2018 sslmode=disable \
--db-name test_db --schema web --table test1 \
--file /var/www/kr.websiteman.py/php/timescale/test1.csv --workers 4 --reporting-period 30s --copy-options "CSV"


psql -d test_db -c "\COPY test1 FROM /var/www/kr.websiteman.py/php/timescale/test1.csv CSV"
psql -U postgres -d test_db -c "\COPY test1 FROM /var/www/kr.websiteman.py/php/timescale/test1.csv WITH (FORMAT CSV, HEADER)"
psql -U postgres -d test_db -c "\COPY test1 FROM /var/www/kr.websiteman.py/php/timescale/test2.csv WITH (FORMAT CSV, HEADER)"
psql -U postgres -d test_db -c "\COPY test1 FROM /var/www/kr.websiteman.py/php/timescale/g5_1_cast_shot_sub.csv WITH (FORMAT CSV, HEADER)"

INSERT INTO "test1" ("mcs_idx", "shot_id", "event_time", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES (NULL, '2324', '2020-11-02 08:16:43', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');


// install TimescaleDB
https://computingforgeeks.com/how-to-install-timescaledb-on-ubuntu/

Step 1: Update your system
sudo apt update
sudo apt upgrade


Step 2: Install PostgreSQL

Import  the repository signing key:
# sudo wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -

Add PostgreSQL apt repository:
# sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'

Update the package lists and install postgresql package:
# sudo apt update
# sudo apt install postgresql-12

Start postgresql-demon
# sudo service postgresql start | restart 

The configuration file for PostgreSQL 10 is /etc/postgresql/12/main/postgresql.conf

Set PostgreSQL admin user’s password
# sudo su - postgres
# psql -c "alter user postgres with password 'db@ypage2018'"

if error occurs.. ()
postgres@ypagepy:~$ psql -c "alter user postgres with password 'db@ypage2018'"
-su: /usr/bin/psql: /usr/bin/perl: bad interpreter: Permission denied
$ exit (back to root account)
$ sudo chmod a+x /usr/bin/perl

and do it again.

Step 3: Install TimescaleDB on Ubuntu 20.04|18.04
The last step is the installation of TimescaleDB on server


Add PPA:
$ exit (back to root)
$ sudo add-apt-repository ppa:timescale/timescaledb-ppa

Update APT package index to confirm if the repository was indeed added:
$ sudo apt-get update

Then install TimescaleDB for PostgreSQL 12, if you have PG 11, replace accordingly:
$ sudo apt install timescaledb-postgresql-12

Now update your PostgreSQL configuration settings for TimescaleDB:
$ sudo timescaledb-tune --quiet --yes

Saving changes to: /etc/postgresql/12/main/postgresql.conf


sudo systemctl restart postgresql
sudo systemctl status postgresql


Step 4: Test TimescaleDB installation on Ubuntu 20.04|18.04

Connect to PostgreSQL, using a superuser named postgres
$ sudo su - postgres
$ psql

postgres=# CREATE database test_db;

Connect to the database
postgres=# \c test_db

Extend the database with TimescaleDB
test_db=# CREATE EXTENSION IF NOT EXISTS timescaledb CASCADE;

Connect to your newly created database:
$ psql -U postgres -h localhost -d test_db


SELECT time_bucket('5 minutes', event_time) AS five_min, avg(lower_heat)
FROM test1
GROUP BY five_min
ORDER BY five_min DESC LIMIT 10;


SELECT table_size, index_size, toast_size, total_size FROM hypertable_relation_size_pretty('test1');

select pg_size_pretty(pg_database_size('test_db'));

SELECT show_chunks('test1', older_than => interval '20 months');





===========================================================

Migrate your TimescaleDB database to Timescale Cloud
Migrating the entire database at once
https://docs.timescale.com/cloud/latest/migrate-to-cloud/entire-database/#prerequisites


pg_dump -U postgres -W \
-h localhost -p 5432 -Fc -v \
-f dump.bak test_db


$ sudo su - postgres

psql “postgres://tsdbadmin:<CLOUD_PASSWORD>@<CLOUD_HOST>:<CLOUD_PORT>/tsdb?sslmode=require”
$ psql "postgres://tsdbadmin:^^tiAnne@@740620@vbjysvzz2g.hp6tz73i1r.tsdb.cloud.timescale.com:32530/tsdb?sslmode=require"
???
psql: error: connection to server on socket "@740620@vbjysvzz2g.hp6tz73i1r.tsdb.cloud.timescale.com/.s.PGSQL.32530" failed: Connection refused
due to the password with @ uh.....
try again without password.
$ psql "postgres://tsdbadmin@vbjysvzz2g.hp6tz73i1r.tsdb.cloud.timescale.com:32530/tsdb?sslmode=require"

Prepare your Timescale Cloud database for data restoration
by using timescaledb_pre_restore to stop background workers:
$ SELECT timescaledb_pre_restore();

pg_restore -U tsdbadmin -W \
-h <CLOUD_HOST> -p <CLOUD_PORT> --no-owner \
-Fc -v -d tsdb dump.bak
....
pg_restore -U tsdbadmin -W -h vbjysvzz2g.hp6tz73i1r.tsdb.cloud.timescale.com -p 32530 --no-owner -Fc -v -d tsdb dump.bak;
pg_restore --clean -U tsdbadmin -W -h vbjysvzz2g.hp6tz73i1r.tsdb.cloud.timescale.com -p 32530 --no-owner -Fc -v -d tsdb dump.bak;



// index가 없으니까.. 엄청 오래 걸리는구나!!
SELECT mlt_idx FROM g5_1_melting_temp WHERE work_date = '2022-03-10' AND work_shift = '2' AND event_time = '2022-03-10 22:27:00'

SELECT mlt_idx FROM g5_1_melting_temp WHERE work_date = '2022-03-10' AND work_shift = '2' AND event_time = '2022-03-10 23:42:00'

// The last inserted data.
SELECT * FROM `g5_1_cast_shot_pressure` WHERE event_time LIKE '2022-05-02%' ORDER BY event_time DESC LIMIT 1
132478245
DELETE FROM `g5_1_cast_shot_pressure` WHERE csp_idx > 132478245
DELETE FROM `g5_1_cast_shot_pressure` WHERE event_time > '2022-05-03 11:47:11'


SELECT * FROM `g5_1_cast_shot_sub` WHERE event_time LIKE '2022-05-02%' ORDER BY event_time DESC LIMIT 1
93709810
DELETE FROM `g5_1_cast_shot_sub` WHERE csp_idx > 93709810
DELETE FROM `g5_1_cast_shot_sub` WHERE event_time > '2022-05-03 12:00:00'

// query speed is too slow.
explain
SELECT csp_idx FROM g5_1_cast_shot_pressure WHERE shot_id = '408746' AND event_time = '2022-05-03 13:04:30.155'
SELECT csp_idx FROM g5_1_cast_shot_pressure WHERE shot_id = '408744' AND event_time = '2022-05-03 13:04:30.173'

// set shot_id index
ALTER TABLE `hanjoo_test`.`g5_1_cast_shot_pressure` ADD INDEX `idx_shot_id` (`shot_id`);


explain
SELECT css_idx FROM g5_1_cast_shot_sub WHERE shot_id = '408746' AND event_time = '2022-05-03 13:04:30.155'



SELECT * FROM g5_1_cast_shot_sub
WHERE (1)
  AND event_time >= '2022-05-09 00:00:00' AND event_time <= '2022-05-09 23:59:59'
ORDER BY event_time DESC LIMIT 0, 100

# mdate 컬럼 속성을 변경하지 않아야 함
WHERE mdate >= '2020-11-01'::timestamp AND mdate < '2020-11-06'::timestamp + interval '1 day'

SELECT *
FROM test2
WHERE event_time >= '2021-11-01'::timestamp
AND event_time < '2021-11-06'::timestamp + interval '1 day';

SELECT * FROM test2
WHERE event_time >= '2021-11-01 00:00:00'::timestamp
AND event_time < '2021-11-02 00:00:00'::timestamp
LIMIT 100;

SELECT COUNT(*) FROM test2;

SELECT * FROM test2 ORDER BY event_time LIMIT 100;


SELECT * FROM test2
WHERE 1=1 AND event_time >= '2022-04-12 00:00:00' AND event_time <= '2022-04-12 23:59:59'
ORDER BY event_time DESC LIMIT 100 OFFSET 0;

SELECT * FROM test2
ORDER BY event_time DESC LIMIT 100 OFFSET 0;

ANALYZE test2;
SELECT * FROM approximate_row_count('test2');

ANALYZE conditions;
SELECT * FROM approximate_row_count('conditions');


SELECT relname, n_tup_ins - n_tup_del as rowcount FROM pg_stat_all_tables;
SELECT relname, n_tup_ins - n_tup_del as rowcount FROM pg_stat_all_tables WHERE relname = 'test2';


SELECT 
  nspname AS schemaname,relname,reltuples
FROM pg_class C
LEFT JOIN pg_namespace N ON (N.oid = C.relnamespace)
WHERE 
  nspname IN ('test2') AND
  relkind='r' 
ORDER BY reltuples DESC;


SELECT reltuples::bigint
FROM pg_catalog.pg_class
WHERE relname = 'test2';

SELECT reltuples AS estimate FROM pg_class where relname = 'test2';


SELECT c.reltuples::bigint AS estimate
FROM   pg_class c
JOIN   pg_namespace n ON n.oid = c.relnamespace
WHERE  c.relname = 'test2'
AND    n.nspname = 'postgres';
myschema=postgres

SELECT reltuples::bigint AS estimate
FROM   pg_class
WHERE  oid = 'postgres.test2'::regclass;

select nspname, pg_authid.rolname as schemaowner, nspacl
from pg_namespace
join pg_authid on pg_authid.oid = pg_namespace.nspowner;

// this might have some solution.
SELECT *
FROM pg_stat_user_tables 
WHERE relname like 'test%'

INSERT INTO "test2" ("shot_id", "event_time", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES ('2324', '2020-11-02 08:16:43', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');
INSERT INTO "test4" ("shot_id", "event_time", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES ('2324', '2020-11-02 08:16:43', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');
INSERT INTO "test4" ("shot_id", "event_time", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES ('2324', '2020-11-02 08:16:43', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');

// disppered?
UPDATE g5_1_cast_shot_sub SET shot_id = '90454369' , event_time = '2022-04-13 08:54:10' , hold_temp = '692' , upper_heat = '363.35' , lower_heat = '372.3' , upper_1_temp = '0' , upper_2_temp = '0' , upper_3_temp = '0' , upper_4_temp = '0' , upper_5_temp = '435.425' , upper_6_temp = '448.4' , lower_1_temp = '0' , lower_2_temp = '0' , lower_3_temp = '371.275' WHERE css_idx = '90454369'

// primary key is awkard.
ALTER SEQUENCE seq RESTART WITH 1;
UPDATE g5_1_cast_shot_sub SET idcolumn=nextval('seq');

ALTER SEQUENCE seq RESTART;
UPDATE g5_1_cast_shot_sub SET css_idx = DEFAULT;

ALTER SEQUENCE test2_mcs_idx_seq RESTART WITH 1453;
ALTER SEQUENCE test2_mcs_idx_seq RESTART WITH 90454371;


create sequence test_field1_seq;
create table test(field1 int not null default nextval('test_field1_seq'));

create table table3(id serial, firstname varchar(20));
create table table4(id int not null default nextval('table3_id_seq'), firstname varchar(20));

SELECT con.*
FROM pg_catalog.pg_constraint con
    INNER JOIN pg_catalog.pg_class rel
                ON rel.oid = con.conrelid
    INNER JOIN pg_catalog.pg_namespace nsp
                ON nsp.oid = connamespace
WHERE nsp.nspname = 'postgres'
      AND rel.relname = 'g5_1_cast_shot_sub';


SELECT constraint_name FROM information_schema.table_constraints
    WHERE table_name='g5_1_cast_shot_sub' AND constraint_type='UNIQUE';             

SELECT conname, contype
FROM pg_catalog.pg_constraint
JOIN pg_class t ON t.oid = c.conrelid
WHERE t.relname ='g5_1_cast_shot_sub';


SELECT column_name, data_type, character_maximum_length
FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = 'g5_1_cast_shot_sub';

// get the last css_idx from temperature db.
SELECT * FROM g5_1_cast_shot_sub ORDER BY event_time DESC LIMIT 1

// timescale DB IN statement
SELECT * FROM public.g5_1_cast_shot_sub ORDER BY event_time DESC LIMIT 100
....
SELECT * FROM public.g5_1_cast_shot_sub ORDER BY event_time DESC LIMIT 1
....
SELECT * FROM public.g5_1_cast_shot_sub
WHERE shot_id IN (414691,414692,414693,414694,414695,414696,414697)
ORDER BY event_time DESC LIMIT 100
....


SELECT GROUP_CONCAT(shot_id) FROM g5_1_cast_shot 
WHERE start_time > '2022-05-14 22:52:20'
  AND start_time <= '2022-05-14 23:07:20'



UPDATE `g5_1_cast_shot` SET end_time = '' WHERE csh_idx = 237307;
UPDATE `g5_1_cast_shot` SET end_time = NULL WHERE csh_idx = 237307;
UPDATE `g5_1_cast_shot` SET end_time = NULL WHERE end_time = '0000-00-00 00:00:00';

SELECT shot_id FROM g5_1_cast_shot
WHERE start_time >= '2022-06-01 13:33:14'
  AND start_time <= '2022-06-02 14:33:14'
  AND machine_id = 63


SELECT * FROM g5_1_cast_shot_sub
WHERE shot_id IN (
  SELECT shot_id FROM g5_1_cast_shot
  WHERE start_time >= '2022-06-02 13:33:14'
    AND start_time <= '2022-06-02 14:33:14'
    AND machine_id = 63
);
....
SELECT * FROM g5_1_cast_shot_sub
WHERE shot_id > 427436
....

create index idx_shot_id on g5_1_cast_shot_sub (shot_id);

SELECT * FROM g5_1_cast_shot_sub
WHERE shot_id IN (
  SELECT shot_id FROM g5_1_cast_shot
  WHERE start_time >= '2022-05-01 13:33:14'
    AND start_time <= '2022-05-31 14:33:14'
    AND machine_id = 63  
);




// get the last time of shot_sub data.
SELECT * FROM g5_1_cast_shot_sub ORDER BY event_time DESC LIMIT 1;
SELECT * FROM g5_1_cast_shot_sub ORDER BY css_idx DESC LIMIT 1;

// get the last time of shot data.
SELECT * FROM g5_1_cast_shot ORDER BY csh_idx DESC LIMIT 1;

SELECT machine_id FROM g5_1_cast_shot WHERE shot_id = '428514';

UPDATE g5_1_cast_shot_sub AS css SET
  machine_id = (SELECT machine_id FROM g5_1_cast_shot WHERE shot_id = css.shot_id )
WHERE event_time > '2022-06-04 04:51:26'
.....
UPDATE g5_1_cast_shot_sub AS css SET
  machine_id = IFNULL((SELECT machine_id FROM g5_1_cast_shot WHERE shot_id = css.shot_id),0)
WHERE event_time > '2022-05-01 00:00:00'
....
UPDATE g5_1_cast_shot_pressure AS csp SET
  machine_id = IFNULL((SELECT machine_id FROM g5_1_cast_shot WHERE shot_id = csp.shot_id),0)
WHERE event_time > '2022-05-01 00:00:00'
....
UPDATE g5_1_cast_shot_sub AS css SET
  shot_no = IFNULL((SELECT shot_no FROM g5_1_cast_shot WHERE shot_id = css.shot_id),0)
WHERE event_time > '2022-05-01 00:00:00'
....
UPDATE g5_1_cast_shot_pressure AS csp SET
shot_no = IFNULL((SELECT shot_no FROM g5_1_cast_shot WHERE shot_id = csp.shot_id),0)
WHERE event_time > '2022-05-01 00:00:00'
....


SELECT work_date, shot_no FROM g5_1_cast_shot
WHERE work_date = '2022-06-02'
ORDER BY shot_no DESC LIMIT 1


if data exists in the range of specific time.
....
SELECT * FROM g5_1_cast_shot_sub
WHERE event_time >= '2022-06-02 04:51:37' AND event_time <= '2022-06-03 04:51:37'
....
SELECT * FROM g5_1_cast_shot_sub
WHERE event_time >= '2022-06-02 04:51:37' AND event_time <= '2022-06-03 04:51:37'
GROUP BY machine_id
....
SELECT machine_id FROM g5_1_cast_shot_sub
WHERE event_time >= '2022-06-02 04:51:37' AND event_time <= '2022-06-03 04:51:37'
GROUP BY machine_id
....
SELECT machine_id FROM g5_1_cast_shot_sub
WHERE event_time >= '2022-06-02 04:51:37'
GROUP BY machine_id
....
postgress is fast....
........................................
SELECT machine_id, MAX(hold_temp) AS hold_temp_max, MAX(upper_heat) AS upper_heat_max, MAX(lower_heat) AS lower_heat_max
  , MAX(upper_1_temp) AS upper_1_temp_max, MAX(upper_2_temp) AS upper_2_temp_max, MAX(upper_3_temp) AS upper_3_temp_max, MAX(upper_4_temp) AS upper_4_temp_max, MAX(upper_5_temp) AS upper_5_temp_max, MAX(upper_6_temp) AS upper_6_temp_max
  , MAX(lower_1_temp) AS lower_1_temp_max, MAX(lower_2_temp) AS lower_2_temp_max, MAX(lower_3_temp) AS lower_3_temp_max
FROM g5_1_cast_shot_sub
WHERE event_time >= '2022-06-02 04:51:37'
GROUP BY machine_id
....
SELECT machine_id, MAX(hold_temp) AS hold_temp_max, MAX(upper_heat) AS upper_heat_max, MAX(lower_heat) AS lower_heat_max
  , MAX(upper_1_temp) AS upper_1_temp_max, MAX(upper_2_temp) AS upper_2_temp_max, MAX(upper_3_temp) AS upper_3_temp_max, MAX(upper_4_temp) AS upper_4_temp_max, MAX(upper_5_temp) AS upper_5_temp_max, MAX(upper_6_temp) AS upper_6_temp_max
  , MAX(lower_1_temp) AS lower_1_temp_max, MAX(lower_2_temp) AS lower_2_temp_max, MAX(lower_3_temp) AS lower_3_temp_max
FROM g5_1_cast_shot_sub
WHERE event_time >= '2022-06-02 04:51:37' AND machine_id = 45
GROUP BY machine_id
....
SELECT machine_id, MAX(detect_pressure) AS detect_pressure_max, MAX(target_pressure) AS target_pressure_max
  , MAX(control_pressure) AS control_pressure_max
  , MAX(deviation_pressure) AS deviation_pressure_max
FROM g5_1_cast_shot_pressure
WHERE event_time >= '2022-06-02 04:51:37' AND machine_id = 45
GROUP BY machine_id
....


SELECT machine_id, machine_no
FROM g5_1_cast_shot
WHERE start_time >= '2022-01-02 04:51:37'
GROUP BY machine_id, machine_no
....


hold_temp=보온로온도
upper_heat=상형히트
lower_heat=하형히트
upper_1_temp=상금형1
upper_2_temp=상금형2
upper_3_temp=상금형3
upper_4_temp=상금형4
upper_5_temp=상금형5
upper_6_temp=상금형6
lower_1_temp=하금형1
lower_2_temp=하금형2
lower_3_temp=하금형3
detect_pressure=검출압력
target_pressure=목표압력
control_pressure=조작압력
deviation_pressure=편차
temp_avg=평균온도
temp_max=온도최대
temp_min=온도최소
hum_avg=평균습도
hum_max=습도최대
hum_min=습도최소


how to program python.
1. 개발환경 vscode, pycharm?
2. 프로그램 백그라운드 실행? 
  현재 프로그램 실행중이 아닌 듯!!
3. 엣지1에 opcua-client 설치
  - GUI 버전!!
4. 


SELECT * FROM g5_1_cast_shot_sub WHERE shot_id IN ( SELECT shot_id FROM g5_1_cast_shot WHERE start_time >= '2022-06-10 19:46:31' AND start_time <= '2022-06-11 05:46:31' AND machine_id = '45' ) ORDER BY event_time ASC;
SELECT shot_id, start_time FROM g5_1_cast_shot WHERE start_time >= '2022-06-10 19:46:31' AND start_time <= '2022-06-11 05:46:31' AND machine_id = '45'
SELECT shot_id, start_time FROM g5_1_cast_shot WHERE start_time >= '2022-06-10 19:46:31' AND start_time <= '2022-06-11 05:46:31' AND machine_id = '45' ORDER BY start_time


ALTER TABLE g5_1_data_measure_58 ADD INDEX idx_type (dta_type);
ALTER TABLE g5_1_data_measure_58 DROP INDEX idx_type;
ALTER TABLE g5_1_data_measure_58 ADD INDEX idx_type_no (dta_type,dta_no);
ALTER TABLE g5_1_data_measure_58 DROP INDEX idx_type_no;

ALTER TABLE `g5_1_data_measure_59` ADD `dta_1` INT NOT NULL DEFAULT '0' COMMENT '추가정보1' AFTER `dta_value`, ADD `dta_2` INT NOT NULL DEFAULT '0' COMMENT '추가정보2' AFTER `dta_1`;
ALTER TABLE `g5_1_data_measure_60` ADD `dta_1` INT NOT NULL DEFAULT '0' COMMENT '추가정보1' AFTER `dta_value`, ADD `dta_2` INT NOT NULL DEFAULT '0' COMMENT '추가정보2' AFTER `dta_1`;
ALTER TABLE `g5_1_data_measure_61` ADD `dta_1` INT NOT NULL DEFAULT '0' COMMENT '추가정보1' AFTER `dta_value`, ADD `dta_2` INT NOT NULL DEFAULT '0' COMMENT '추가정보2' AFTER `dta_1`;
ALTER TABLE `g5_1_data_measure_62` ADD `dta_1` INT NOT NULL DEFAULT '0' COMMENT '추가정보1' AFTER `dta_value`, ADD `dta_2` INT NOT NULL DEFAULT '0' COMMENT '추가정보2' AFTER `dta_1`;
ALTER TABLE `g5_1_data_measure_63` ADD `dta_1` INT NOT NULL DEFAULT '0' COMMENT '추가정보1' AFTER `dta_value`, ADD `dta_2` INT NOT NULL DEFAULT '0' COMMENT '추가정보2' AFTER `dta_1`;
ALTER TABLE `g5_1_data_measure_64` ADD `dta_1` INT NOT NULL DEFAULT '0' COMMENT '추가정보1' AFTER `dta_value`, ADD `dta_2` INT NOT NULL DEFAULT '0' COMMENT '추가정보2' AFTER `dta_1`;

ALTER TABLE `g5_1_data_measure_58` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;
ALTER TABLE `g5_1_data_measure_59` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;
ALTER TABLE `g5_1_data_measure_60` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;
ALTER TABLE `g5_1_data_measure_61` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;
ALTER TABLE `g5_1_data_measure_62` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;
ALTER TABLE `g5_1_data_measure_63` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;
ALTER TABLE `g5_1_data_measure_64` ADD `dta_3` INT DEFAULT '0' COMMENT '추가정보3' AFTER `dta_2`;

DELETE FROM g5_1_cast_shot_pressure WHERE event_time > '2022-06-20 16:07:54';
DELETE FROM g5_1_cast_shot_sub WHERE event_time > '2022-06-20 16:07:54';

SELECT MIN(shot_id), MAX(shot_id) FROM MES_CAST_SHOT_SUB
WHERE EVENT_TIME >= '2022-06-20 20:08:33'

SELECT machine_id FROM g5_1_cast_shot
WHERE shot_id >= 437005
AND shot_id <= 437719
GROUP BY machine_id


- AAS(자산관리쉘) 도입을 통한 디지털 트윈 모델링
- 시계열 디비(TSDB)를 통한 고속 태그 정보 수집
- SCADA(실시간 설비 정보 표현)
- 현장용 엣지 서버 개발 & 클라우드 확장
- 기업 예산과 유지 관리 능력에 따른 단계별 데이터 저장 및 관리 시스템
- 예측 알고리즘을 탑재하여 분석할 수 있도록 플랫폼 개발
- 기계/설비/부품의 생애주기 관리 기능 설계 및 개발
- 고객 맞춤 알람(예지) 시스템 (단계별, 레벨별 메시징)
- 주요 지표의 통합 모니터링 및 분석 기능 설계, 구현
- 다양한 디바이스에서 표현하도록 하여 UI/UX 확장

SELECT * FROM `g5_5_meta` WHERE `mta_db_id` LIKE '58' ORDER BY `mta_key` ASC


INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-5', '구배 압력 P0S 0 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-6', '구배 압력 P0S 1 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-7', '구배 압력 P0S 2 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-8', '구배 압력 P0S 3 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-9', '구배 압력 P0S 4 SV  SETTING', '2022-07-01 18:45:47');
...
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-10', '구배 시간 P0S 0 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-11', '구배 시간 P0S 1 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-12', '구배 시간 P0S 2 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-13', '구배 시간 P0S 3 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-14', '구배 시간 P0S 4 SV  SETTING', '2022-07-01 18:45:47');
...
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-15', '목표 압력 (구배+증분xShot=작업영역)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` (`mta_idx`, `mta_country`, `mta_db_table`, `mta_db_id`, `mta_key`, `mta_value`, `mta_reg_dt`) VALUES
  (NULL, '', 'mms', '58', 'dta_type_label-13-16', '조작압력 MV', '2022-07-01 18:45:47');
.............
.............
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-1', '보온로온도', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-10', '하금형1', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-11', '하금형2', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-12', '하금형3', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-2', '상형히트', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-3', '하형히트', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-4', '상금형1', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-5', '상금형2', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-6', '상금형3', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-7', '상금형4', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-8', '상금형5', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-1-9', '상금형6', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-1', '형 가열온도 #1 SV_T', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-10', '구배시간 P0S 0 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-11', '구배시간 P0S 1 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-12', '구배시간 P0S 2 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-13', '구배시간 P0S 3 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-14', '구배시간 P0S 4 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-15', '목표 압력 (구배+증분xShot=작업영역)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-16', '조작압력 MV', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-17', '압력 Keep (9-STEP) 완료 COUNT', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-19', '가압 자동 운전중 COUNT', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-2', '형 가열온도 #2 SV_T', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-20', '주조기 일시정지 PB', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-21', '자동 운전 중', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-22', '배기 대 밸브 SOL', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-23', '상금형 M/C ON', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-24', '하금형 M/C ON', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-3', '작동유 온도 SV_T', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-31', 'BoolData1', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-32', '상형#1 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-33', '상형#1 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-34', '상형#2 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-35', '상형#2 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-36', '상형#7 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-37', '상형#7 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-38', '하형#1 냉각 시간설정 - 물 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-39', '하형#1 냉각 시간설정 - 공기 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-4', '검출 압력 PV DISPLAY', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-40', '상형#1 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-41', '상형#1 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-42', '상형#2 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-43', '상형#2 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-44', '상형#7 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-45', '상형#7 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-46', '하형#1 냉각 지연 시간 설정 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-47', '하형#1 냉각 총 시간 설정 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-5', '구배 압력 P0S 0 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-6', '구배 압력 P0S 1 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-7', '구배 압력 P0S 2 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-8', '구배 압력 P0S 3 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-13-9', '구배 압력 P0S 4 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-8-1', '검출압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-8-2', '목표압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-8-3', '조작압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '59', 'dta_type_label-8-4', '편차', '2022-07-01 18:38:43');
.............
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-1', '보온로온도', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-10', '하금형1', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-11', '하금형2', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-12', '하금형3', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-2', '상형히트', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-3', '하형히트', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-4', '상금형1', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-5', '상금형2', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-6', '상금형3', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-7', '상금형4', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-8', '상금형5', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-1-9', '상금형6', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-1', '형 가열온도 #1 SV_T', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-10', '구배시간 P0S 0 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-11', '구배시간 P0S 1 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-12', '구배시간 P0S 2 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-13', '구배시간 P0S 3 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-14', '구배시간 P0S 4 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-15', '목표 압력 (구배+증분xShot=작업영역)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-16', '조작압력 MV', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-17', '압력 Keep (9-STEP) 완료 COUNT', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-19', '가압 자동 운전중 COUNT', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-2', '형 가열온도 #2 SV_T', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-20', '주조기 일시정지 PB', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-21', '자동 운전 중', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-22', '배기 대 밸브 SOL', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-23', '상금형 M/C ON', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-24', '하금형 M/C ON', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-3', '작동유 온도 SV_T', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-31', 'BoolData1', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-32', '상형#1 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-33', '상형#1 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-34', '상형#2 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-35', '상형#2 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-36', '상형#7 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-37', '상형#7 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-38', '하형#1 냉각 시간설정 - 물 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-39', '하형#1 냉각 시간설정 - 공기 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-4', '검출 압력 PV DISPLAY', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-40', '상형#1 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-41', '상형#1 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-42', '상형#2 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-43', '상형#2 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-44', '상형#7 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-45', '상형#7 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-46', '하형#1 냉각 지연 시간 설정 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-47', '하형#1 냉각 총 시간 설정 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-5', '구배 압력 P0S 0 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-6', '구배 압력 P0S 1 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-7', '구배 압력 P0S 2 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-8', '구배 압력 P0S 3 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-13-9', '구배 압력 P0S 4 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-8-1', '검출압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-8-2', '목표압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-8-3', '조작압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '60', 'dta_type_label-8-4', '편차', '2022-07-01 18:38:43');
.............
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-1', '보온로온도', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-10', '하금형1', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-11', '하금형2', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-12', '하금형3', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-2', '상형히트', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-3', '하형히트', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-4', '상금형1', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-5', '상금형2', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-6', '상금형3', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-7', '상금형4', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-8', '상금형5', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-1-9', '상금형6', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-1', '형 가열온도 #1 SV_T', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-10', '구배시간 P0S 0 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-11', '구배시간 P0S 1 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-12', '구배시간 P0S 2 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-13', '구배시간 P0S 3 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-14', '구배시간 P0S 4 SV', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-15', '목표 압력 (구배+증분xShot=작업영역)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-16', '조작압력 MV', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-17', '압력 Keep (9-STEP) 완료 COUNT', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-19', '가압 자동 운전중 COUNT', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-2', '형 가열온도 #2 SV_T', '2022-07-01 18:42:03');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-20', '주조기 일시정지 PB', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-21', '자동 운전 중', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-22', '배기 대 밸브 SOL', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-23', '상금형 M/C ON', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-24', '하금형 M/C ON', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-3', '작동유 온도 SV_T', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-31', 'BoolData1', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-32', '상형#1 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-33', '상형#1 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-34', '상형#2 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-35', '상형#2 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-36', '상형#7 냉각 시간설정 - 물', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-37', '상형#7 냉각 시간설정 - 공기', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-38', '하형#1 냉각 시간설정 - 물 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-39', '하형#1 냉각 시간설정 - 공기 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-4', '검출 압력 PV DISPLAY', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-40', '상형#1 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-41', '상형#1 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-42', '상형#2 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-43', '상형#2 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-44', '상형#7 냉각 지연 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-45', '상형#7 냉각 총 시간 설정', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-46', '하형#1 냉각 지연 시간 설정 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-47', '하형#1 냉각 총 시간 설정 (#9)', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-5', '구배 압력 P0S 0 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-6', '구배 압력 P0S 1 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-7', '구배 압력 P0S 2 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-8', '구배 압력 P0S 3 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-13-9', '구배 압력 P0S 4 SV  SETTING', '2022-07-01 18:45:47');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-8-1', '검출압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-8-2', '목표압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-8-3', '조작압력', '2022-07-01 18:38:43');
INSERT INTO `g5_5_meta` VALUES(NULL, '', 'mms', '61', 'dta_type_label-8-4', '편차', '2022-07-01 18:38:43');

2번로봇
"R2_NG"
"2A31B32"
넘기는 값(최지환): 2A31B32
기타-1 13-1	825442610 (32비트)
기타-2 13-2	3289922 (32비트)

1. 기타-1 13-1	825442610 (32비트 10진수)
  . 825442610 (10진수) -> 3133 4132 (16진수) 참고: http://www.hipenpal.com/tool/binary-octal-decimal-hexadecimal-number-converter-in-korean.php
  . 두자리씩 끊어서 16진수 to ASCII (참고:https://www.ibm.com/docs/ko/aix/7.1?topic=adapters-ascii-decimal-hexadecimal-octal-binary-conversion-table)
    - 31 -> 1
    - 33 -> 3
    - 41 -> A
    - 32 -> 2 
2. 기타-2 13-2	3289922 (32비트 10진수)
  . 3289922 (10진수) -> 0032 3342 (16진수)
    - 00 -> 무시
    - 32 -> 2
    - 33 -> 3
    - 42 -> B
1번을 거꾸로 읽고... 2번을 거꾸로 읽으면.. 조합 완료!!
2A31B32

1번로봇
"R1_NG"
"1A31B41"
기타-4 13-4	825442609
기타-5 13-5	3224642
1. 기타-4 13-4	825442609 (10진수) -> 31334131 (16진수)
    - 31 -> 1
    - 33 -> 3
    - 41 -> A
    - 31 -> 1 
2. 기타-5 13-5	3224642 -> 00313442 (16진수)
    - 00 -> 무시
    - 31 -> 1
    - 34 -> 4
    - 42 -> B
1번을 거꾸로 읽고... 2번을 거꾸로 읽으면.. 조합 완료!!
1A31B41

부산은행!!
DC형으로 IRP계좌를 만들고 가입을 해야 한다.
신분증.. 
DC 타입 가입 준비완료!!

1. 직접 확인
2. 

// x-ray inspection table query.
SELECT * FROM g5_1_xray_inspection WHERE xry_idx = 33455

SELECT * FROM g5_1_cast_shot_pressure WHERE event_time = '2022-07-11 06:10:26';
SELECT * FROM g5_1_cast_shot_sub WHERE event_time = '2022-07-11 06:10:26';

SELECT * FROM g5_1_data_measure_58 WHERE dta_dt > '2022-07-11 06:10:26' ORDER BY dta_dt LIMIT 1;
SELECT * FROM g5_1_data_measure_59 WHERE dta_dt > '2022-07-11 06:10:26' ORDER BY dta_dt LIMIT 1;
SELECT * FROM g5_1_data_measure_60 WHERE dta_dt > '2022-07-11 06:10:26' ORDER BY dta_dt LIMIT 1;
SELECT * FROM g5_1_data_measure_61 WHERE dta_dt > '2022-07-11 06:10:26' ORDER BY dta_dt LIMIT 1;

SELECT * FROM g5_1_data_measure_61 WHERE dta_dt = timestamp '2022-07-11 06:10:30';

ALTER TABLE g5_1_data_measure_64
  ALTER COLUMN dta_dt
  SET DATA TYPE timestamp without time zone;

ALTER TABLE g5_1_data_measure_64 ALTER dta_dt TYPE timestamptz
USING dta_dt AT TIME ZONE 'Asia/Seoul';

ALTER TABLE g5_1_data_measure_64 ALTER COLUMN dta_dt TYPE timestamp without time zone;

// QR주조코드 복제
INSERT INTO g5_1_qr_cast_code(qrcode, qrc_reg_dt)
SELECT qrcode, event_time
FROM g5_1_engrave_qrcode

// 일단은 평일 최근 날짜의 평균값을 최적값으로 넣어두자.
SELECT * FROM g5_1_data_measure_58 WHERE dta_dt > '2022-07-11 06:10:26' ORDER BY dta_dt LIMIT 1;

SELECT dta_type, dta_no, AVG(dta_value) AS dta_value
FROM g5_1_data_measure_60
WHERE dta_type IN (1,8) AND dta_no IN (1,2,3,4,5,6,7,8,9,10,11)
  AND dta_dt >= '2022-07-15 00:00:00'
  AND dta_dt < '2022-07-16 00:00:00'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no
....

// 최적 태그기준값 추출 (제일 최근 거 기준)
SELECT dta_type, dta_no, MAX(dta_value), MIN(dta_value)
FROM g5_1_data_measure_best
WHERE {$sql_dta_type}
AND dta_dt >= '".$st_date." ".$st_time."' AND dta_dt <= '".$en_date." ".$en_time."'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no ASC

/home/hanjoo/www/php/hanjoo/skin/member/basic/login.skin.php

지능형주조파라메터
1. 온도, 압력을 알려주는 것이다.
@2. 불량품이 나왔을 때는 

설비통합관리시스템
설비 자체 문제가 생겼을 때 조치사항을 알려주는 것이다.
불량품이 나왔을 때 조치하는 것이 아니다.

로봇..
1. 토그 & 온도만 모니터링 한다.
2. 기준이 되면 멈춤
@3. 재시작? 한시간 있다가 다시 시작한다?
  . 온도설정값을 저장해 두었다가..
  . 기준 두가지: 시간, 온도, 토크
4. 머신러닝인가?
  . 머신러닝은 아님!!

// 17호기(58)번 알람 리스트
SELECT arm.cod_idx,trm_idx_category, arm_cod_code, cod_name, cod_memo, COUNT(arm_idx) AS cnt
FROM g5_1_alarm AS arm
  LEFT JOIN g5_1_code AS cod USING(cod_idx)
WHERE arm.com_idx = '15'
  AND arm.mms_idx = '58'
  AND cod_quality_yn != '1'
GROUP BY arm_cod_code
ORDER BY cnt DESC
....
SELECT cod_name, COUNT(arm_idx) AS cnt
FROM g5_1_alarm AS arm
  LEFT JOIN g5_1_code AS cod USING(cod_idx)
WHERE arm.com_idx = '15'
  AND arm.mms_idx = '58'
  AND cod_quality_yn != '1'
  AND cod_name != ''
GROUP BY arm_cod_code
ORDER BY cnt DESC
....
// 18호기(59)
SELECT cod_name, COUNT(arm_idx) AS cnt
FROM g5_1_alarm AS arm
  LEFT JOIN g5_1_code AS cod USING(cod_idx)
WHERE arm.com_idx = '15'
  AND arm.mms_idx = '59'
  AND cod_quality_yn != '1'
  AND cod_name != ''
GROUP BY arm_cod_code
ORDER BY cnt DESC
....
// get one of them randomly.
SELECT cod_idx, cod_code, cod_name, COUNT(arm_idx) AS cnt
FROM g5_1_alarm AS arm
  LEFT JOIN g5_1_code AS cod USING(cod_idx)
WHERE arm.com_idx = '15'
  AND arm.mms_idx = '59'
  AND cod_quality_yn != '1'
  AND cod_name != ''
GROUP BY arm_cod_code
ORDER BY RAND() LIMIT 1
....







// 10초에 한개씩!!
* *      * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php

// only 1 in case
* *      * * *  root    /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php
* *      * * *  root    /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php

// crontab at the moment.
SHELL=/bin/sh
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin

# m h dom mon dow user  command
17 *    * * *   root    cd / && run-parts --report /etc/cron.hourly
25 6    * * *   root    test -x /usr/sbin/anacron || ( cd / && run-parts --report /etc/cron.daily )
47 6    * * 7   root    test -x /usr/sbin/anacron || ( cd / && run-parts --report /etc/cron.weekly )
52 6    1 * *   root    test -x /usr/sbin/anacron || ( cd / && run-parts --report /etc/cron.monthly )

# James code starts here.
#*/2 *  * * *   root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/cron_test.php
2 */1   * * *   root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_charge_in_sync.php
4 */1   * * *   root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_charge_out_sync.php
6 */1   * * *   root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_melting_temp_sync.php
8 */1   * * *   root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_cast_shot_sync.php
10 */1   * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_cast_shot_sub_sync.php
12 */1   * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_cast_shot_pressure_sync.php
14 */1   * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_engrave_qrcode_sync.php
16 */1   * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_xray_inspection_sync.php
18 */1   * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/mes_factory_temphum_sync.php
#* *      * * *  root    /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php
#* *      * * *  root    wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php; sleep 10; wget -O - -q -t 1 http://hanjoo.epcs.co.kr/user/cron/robot_input_test.php
* *      * * *  root    /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php; sleep 10; /usr/bin/php /home/hanjoo/www/php/hanjoo/user/cron/robot_input_test.php

GMP2030 광장비
이혜민씨

// 온도, 압력은 최근 시간에는 없다. 1시간에 한번씩만 동기화를 진행함
//hanjoo.epcs.co.kr/user/json/measure.php?token=1099de5drf09&mms_idx=58&dta_type=8&dta_no=1&
st_date=2022-08-23&st_time=16:57:12&en_date=2022-08-23&en_time=17:57:12

//hanjoo.epcs.co.kr/user/json/measure.php?token=1099de5drf09&mms_idx=58&dta_type=8&dta_no=1&
st_date=2022-08-23&st_time=15:54:34&en_date=2022-08-23&en_time=16:54:34

http://hanjoo.epcs.co.kr/user/json/measure.php?token=1099de5drf09&mms_idx=58&dta_type=1&dta_no=2&st_date=2022-08-24&st_time=07:31:14&en_date=2022-08-24&en_time=08:31:14&graph_name=%EC%83%81%ED%98%95%ED%9E%88%ED%8A%B8&graph_id=NThfMV8y&mbd_idx=1530
st_date=2022-08-24&st_time=07:31:14&en_date=2022-08-24&en_time=08:31:14

// 온도 압력 같은 경우 시간대에 정보가 없으면 마지막 시점 기준으로 거꾸로 시간을 계산해서 보여주는 것으로 해야 겠어요.

// 대시보드
. 상세보기하면 이동!!
. 기타 다른 버튼들도 기능을 붙여주세요.


// 최적 파라메터 
SELECT dta_type, dta_no, AVG(dta_value) AS dta_value, MIN(dta_idx) AS dta_idx
FROM g5_1_data_measure_61
WHERE dta_type IN (1,8)
  AND dta_dt >= '2022-08-23 00:00:00' AND dta_dt <= '2022-08-23 23:59:59'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no ASC


피씨크기:
560:315 = 890:x (500)
모바일크기: 
560:315 = 373:x (209)



08/30 발표 내용 ................................................
1. 대시보드
  . 100% 실시간은 아니고 온도, 압력 정보는 1시간 정도 시차가 있음: MES개발사인 큐빅에서 서버 부하 줄여달라는 요청이 있어서 1시간에 1회 수집된 정보를 동기화 작업진행중
  . 다른 설비측정정보들은 20초 정도 시차가 있음 (데이터 수집 주기)



SELECT dept_name, mo01, mo02 ... mo12, tot
FROM (SELECT dept
   ,SUM (case substr(month,3,2) = '01' THEN amount) mo01  -- 1월
   ,SUM (case substr(month,3,2) = '02' THEN amount) mo02  -- 2월
   , ....
   ,SUM (case substr(month,3,2) = '12' THEN amount) mo12  -- 12월
   ,SUM (amount) tot       -- 총계
   FROM plan_table
   WHERE yymmdd LIKE '199606'     -- 1996년 06월일것만
   GROUP BY dept
  ) x, detp_table y
WHERE y.dept = x.dept
....
SELECT ret_type
  ,SUM(IF(SUBSTR(ret_ym,6,2)='01', ret_count, 0)) AS mo01
  ,SUM(IF(SUBSTR(ret_ym,6,2)='02', ret_count, 0)) AS mo02
  ,SUM(IF(SUBSTR(ret_ym,6,2)='03', ret_count, 0)) AS mo03
  ,SUM(IF(SUBSTR(ret_ym,6,2)='04', ret_count, 0)) AS mo04
  ,SUM(IF(SUBSTR(ret_ym,6,2)='05', ret_count, 0)) AS mo05
  ,SUM(IF(SUBSTR(ret_ym,6,2)='06', ret_count, 0)) AS mo06
  ,SUM(IF(SUBSTR(ret_ym,6,2)='07', ret_count, 0)) AS mo07
  ,SUM(IF(SUBSTR(ret_ym,6,2)='08', ret_count, 0)) AS mo08
  ,SUM(IF(SUBSTR(ret_ym,6,2)='09', ret_count, 0)) AS mo09
  ,SUM(IF(SUBSTR(ret_ym,6,2)='10', ret_count, 0)) AS mo10
  ,SUM(IF(SUBSTR(ret_ym,6,2)='11', ret_count, 0)) AS mo11
  ,SUM(IF(SUBSTR(ret_ym,6,2)='12', ret_count, 0)) AS mo12
FROM g5_1_return
WHERE ret_ym LIKE '2022%'
GROUP BY ret_type
....
SELECT ret_type, mo01, mo02, mo03, mo04, mo05, mo06, mo07, mo08, mo09, mo10, mo11, mo12, flag
FROM (
  SELECT ret_type
    , SUM(IF(SUBSTR(ret_ym,6,2)='01', ret_count, 0)) AS mo01
    , SUM(IF(SUBSTR(ret_ym,6,2)='02', ret_count, 0)) AS mo02
    , SUM(IF(SUBSTR(ret_ym,6,2)='03', ret_count, 0)) AS mo03
    , SUM(IF(SUBSTR(ret_ym,6,2)='04', ret_count, 0)) AS mo04
    , SUM(IF(SUBSTR(ret_ym,6,2)='05', ret_count, 0)) AS mo05
    , SUM(IF(SUBSTR(ret_ym,6,2)='06', ret_count, 0)) AS mo06
    , SUM(IF(SUBSTR(ret_ym,6,2)='07', ret_count, 0)) AS mo07
    , SUM(IF(SUBSTR(ret_ym,6,2)='08', ret_count, 0)) AS mo08
    , SUM(IF(SUBSTR(ret_ym,6,2)='09', ret_count, 0)) AS mo09
    , SUM(IF(SUBSTR(ret_ym,6,2)='10', ret_count, 0)) AS mo10
    , SUM(IF(SUBSTR(ret_ym,6,2)='11', ret_count, 0)) AS mo11
    , SUM(IF(SUBSTR(ret_ym,6,2)='12', ret_count, 0)) AS mo12
    , (CASE ret_type
          WHEN '투입' THEN 1
          WHEN 'N02' THEN 2
          WHEN 'L/ARM' THEN 3
          WHEN 'SKID' THEN 4
          WHEN 'A/ARM' THEN 5
          WHEN 'B/MTG' THEN 6
          WHEN '기타' THEN 7
      ELSE 1000
      END) AS flag
  FROM g5_1_return
  WHERE ret_ym LIKE '2022%'
  GROUP BY ret_type
) AS db1
ORDER BY flag
....
SELECT ret_type, mo01, mo02, mo03, mo04, mo05, mo06, mo07, mo08, mo09, mo10, mo11, mo12, flag
FROM (
  SELECT ret_type
    , SUM(IF(SUBSTR(ret_ym,6,2)='01', ret_count, 0)) AS mo01
    , SUM(IF(SUBSTR(ret_ym,6,2)='02', ret_count, 0)) AS mo02
    , SUM(IF(SUBSTR(ret_ym,6,2)='03', ret_count, 0)) AS mo03
    , SUM(IF(SUBSTR(ret_ym,6,2)='04', ret_count, 0)) AS mo04
    , SUM(IF(SUBSTR(ret_ym,6,2)='05', ret_count, 0)) AS mo05
    , SUM(IF(SUBSTR(ret_ym,6,2)='06', ret_count, 0)) AS mo06
    , SUM(IF(SUBSTR(ret_ym,6,2)='07', ret_count, 0)) AS mo07
    , SUM(IF(SUBSTR(ret_ym,6,2)='08', ret_count, 0)) AS mo08
    , SUM(IF(SUBSTR(ret_ym,6,2)='09', ret_count, 0)) AS mo09
    , SUM(IF(SUBSTR(ret_ym,6,2)='10', ret_count, 0)) AS mo10
    , SUM(IF(SUBSTR(ret_ym,6,2)='11', ret_count, 0)) AS mo11
    , SUM(IF(SUBSTR(ret_ym,6,2)='12', ret_count, 0)) AS mo12
    , (CASE ret_type
          WHEN '투입' THEN 1
          WHEN 'N02' THEN 2
          WHEN 'L/ARM' THEN 3
          WHEN 'SKID' THEN 4
          WHEN 'A/ARM' THEN 5
          WHEN 'B/MTG' THEN 6
          WHEN '기타' THEN 7
      ELSE 1000
      END) AS flag
  FROM g5_1_return
  WHERE ret_ym LIKE '2022%'
  GROUP BY ret_type
) AS db1
ORDER BY flag
....

// output from old database
SELECT SQL_CALC_FOUND_ROWS mms_idx
  , dta_mmi_no, dta_date 
  , SUM(dta_value) AS output_sum 
FROM g5_1_data_output_sum 
WHERE mms_idx = '58' AND dta_date >= '2022-09-01' AND dta_date <= '2022-09-04' 
GROUP BY dta_mmi_no, dta_date 
ORDER BY dta_date DESC, dta_mmi_no
....
// now let's get the new one based on PgSQL -------------------------------------
SELECT SQL_CALC_FOUND_ROWS mms_idx
  , dta_mmi_no, dta_date 
  , SUM(dta_value) AS output_sum 
FROM g5_1_data_output_sum 
WHERE mms_idx = '58' AND dta_date >= '2022-09-01' AND dta_date <= '2022-09-04' 
GROUP BY dta_mmi_no, dta_date 
ORDER BY dta_date DESC, dta_mmi_no
....
SELECT SUBSTRING(qrcode,8,2) AS item_type
  , work_date
  , COUNT(xry_idx) AS output_sum
FROM g5_1_xray_inspection
WHERE work_date >= '2022-04-01' AND work_date <= '2022-09-04' 
GROUP BY item_type, work_date
ORDER BY work_date DESC
....
SELECT SUBSTRING(qrcode,8,2) AS item_type
  , SUBSTRING(end_time,1,10) AS end_date
  , COUNT(xry_idx) AS output_sum
FROM g5_1_xray_inspection
WHERE end_time >= '2022-04-01 00:00:00' AND end_time <= '2022-09-04 23:59:59' 
GROUP BY item_type, end_date
ORDER BY end_date DESC, item_type
....
// include the item_type of Regular and Electric viechel.
SELECT SUBSTRING(end_time,1,10) AS end_date
  , SUBSTRING(qrcode,7,1) AS item_type
  , SUBSTRING(qrcode,8,2) AS item_lhrh
  , COUNT(xry_idx) AS output_sum
FROM g5_1_xray_inspection
WHERE end_time >= '2022-04-01 00:00:00' AND end_time <= '2022-09-04 23:59:59' 
GROUP BY end_date, item_type, item_lhrh
ORDER BY end_date DESC, item_type, item_lhrh
....
// remove the type RH, LH
SELECT SUBSTRING(end_time,1,10) AS end_date
  , COUNT(xry_idx) AS output_sum
FROM g5_1_xray_inspection
WHERE end_time >= '2022-04-01 00:00:00' AND end_time <= '2022-09-04 23:59:59' 
GROUP BY end_date
ORDER BY end_date DESC
....


// start_time and end_time baseed on end_time field.
SELECT SUBSTRING(end_time,1,10) AS end_date
  , SUBSTRING(qrcode,7,1) AS item_type
  , SUBSTRING(qrcode,8,2) AS item_lhrh
  , min(end_time) AS dta_ymdhis_min
  , max(end_time) AS dta_ymdhis_max
  , SUBSTRING(min(end_time),11,9) AS dta_start_his
  , SUBSTRING(max(end_time),11,9) AS dta_end_his
FROM g5_1_xray_inspection 
WHERE end_time >= '2022-09-05 00:00:00' AND end_time <= '2022-09-05 23:59:59'


SELECT dta_mmi_no, dta_date, dta_dt 
  , min(dta_dt) 
  , max(dta_dt) 
  , FROM_UNIXTIME(min(dta_dt),'%Y-%m-%d %H:%i:%s') AS dta_ymdhis_min 
  , FROM_UNIXTIME(max(dta_dt),'%Y-%m-%d %H:%i:%s') AS dta_ymdhis_max 
  , FROM_UNIXTIME(min(dta_dt),'%H%i%s') AS dta_start_his 
  , FROM_UNIXTIME(max(dta_dt),'%H%i%s') AS dta_end_his 
FROM g5_1_data_output_58 
WHERE dta_mmi_no = '' AND dta_date IN ('')
....
SELECT work_date AS dta_date
  , SUBSTRING(qrcode,7,1) AS item_type
  , SUBSTRING(qrcode,8,2) AS item_lhrh
  , min(end_time) AS dta_ymdhis_min
  , max(end_time) AS dta_ymdhis_max
  , SUBSTRING(min(end_time),11,9) AS dta_start_his
  , SUBSTRING(max(end_time),11,9) AS dta_end_his
FROM g5_1_xray_inspection 
WHERE end_time >= '2022-09-05 00:00:00' AND end_time <= '2022-09-05 23:59:59'

// find the records that has wide gap which is more than 20 secs between outputs.
SELECT xry_idx, work_date AS dta_date
  , UNIX_TIMESTAMP(end_time) AS end_timestamp
  , ( UNIX_TIMESTAMP(end_time) - LAG(UNIX_TIMESTAMP(end_time)) OVER (order by xry_idx ASC) ) AS diff_timestamp
FROM g5_1_xray_inspection
WHERE end_time >= '2022-09-14 00:00:00' AND end_time <= '2022-09-14 23:59:59'
ORDER BY xry_idx DESC
LIMIT 10
....
SELECT xry_idx, work_date AS dta_date
  , UNIX_TIMESTAMP(end_time) AS end_timestamp
  , LAG(end_time)
--  , ( end_time - LAG(end_time) OVER (order by xry_idx ASC) ) AS 'diff_timestamp'
FROM g5_1_xray_inspection
WHERE end_time >= '2022-09-14 00:00:00' AND end_time <= '2022-09-14 23:59:59'
ORDER BY xry_idx DESC
LIMIT 10
....
SELECT end_time
     , (SELECT end_time
        FROM g5_1_xray_inspection AS B
        WHERE B.end_time < A.end_time
        ORDER BY end_time DESC
        LIMIT 1
        ) AS end_time_prev
FROM g5_1_xray_inspection AS A
ORDER BY xry_idx DESC
LIMIT 10
....
SELECT end_time, end_time_prev
  , UNIX_TIMESTAMP(end_time) AS end_timestamp
  , UNIX_TIMESTAMP(end_time_prev) AS end_timestamp_prev
  , ( UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(end_time_prev) ) AS end_time_diff
FROM (
  SELECT end_time
       , (SELECT end_time
          FROM g5_1_xray_inspection AS B
          WHERE B.end_time < A.end_time
          ORDER BY end_time DESC
          LIMIT 1
          ) AS end_time_prev
  FROM g5_1_xray_inspection AS A
  ORDER BY xry_idx DESC
  LIMIT 10
) AS db1
....
3. 스칼라서브쿼리
// not good for many data.
SELECT end_time, end_time_prev
  , UNIX_TIMESTAMP(end_time) AS end_timestamp
  , UNIX_TIMESTAMP(end_time_prev) AS end_timestamp_prev
  , ( UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(end_time_prev) ) AS end_time_diff
FROM (
  SELECT end_time
       , (SELECT end_time
          FROM g5_1_xray_inspection AS B
          WHERE end_time >= '2022-09-14 00:00:00' AND end_time <= '2022-09-14 23:59:59'
            AND B.end_time < A.end_time
          ORDER BY end_time DESC
          LIMIT 1
          ) AS end_time_prev
  FROM g5_1_xray_inspection AS A
  ORDER BY xry_idx DESC
) AS db1
....
1. Self join method.
SELECT A.end_time
  , MAX(B.end_time) end_time_prev
FROM g5_1_xray_inspection AS A
LEFT OUTER JOIN g5_1_xray_inspection AS B
  ON A.end_time > B.end_time
WHERE A.end_time >= '2022-09-14 00:00:00' AND A.end_time <= '2022-09-14 23:59:59'
GROUP BY A.xry_idx DESC
// After setting the search periof of one month, query speed was acceptable (about 10 secs.)
....
SELECT end_time, end_time_prev
  , UNIX_TIMESTAMP(end_time) AS end_timestamp
  , UNIX_TIMESTAMP(end_time_prev) AS end_timestamp_prev
  , ( UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(end_time_prev) ) AS end_time_diff
FROM (
    SELECT A.end_time AS end_time
      , MAX(B.end_time) AS end_time_prev
    FROM g5_1_xray_inspection AS A
    LEFT OUTER JOIN g5_1_xray_inspection AS B
      ON A.end_time > B.end_time
    WHERE A.end_time >= '2022-09-13 00:00:00' AND A.end_time <= '2022-09-13 23:59:59'
    GROUP BY A.xry_idx DESC
) AS db1
ORDER BY end_time_diff
WHERE ( UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(end_time_prev) ) <= 50
// too big time span should be avoidable. 
....


SELECT * FROM g5_1_dash_grid WHERE mta_idx = '' AND dsg_status = 'ok' ORDER BY dsg_order

SELECT mta_idx,mta_value,mta_title,mta_number FROM g5_5_meta
WHERE mta_db_table = 'member' AND mta_db_id = 'tidy345' AND mta_key = 'dashboard_menu' ORDER BY mta_number

SELECT mta_idx,mta_value,mta_title,mta_number FROM g5_5_meta
WHERE mta_db_table = 'member' AND mta_db_id = 'jamesjoa' AND mta_key = 'dashboard_menu' ORDER BY mta_number

// let's make default 2 dashboards in meta db tables.

SELECT DATE_ADD(now(), INTERVAL -1 DAY) FROM dual
SELECT DATE_ADD(now(), INTERVAL -6 HOUR) FROM dual



[
  {
    x: 1663589512000,
    y: 0,
    yraw: 0,
    yamp: 1,
    ymove: 0
  },
  {
    x: 1663589580000,
    y: 0,
    yraw: 0,
    yamp: 1,
    ymove: 0
  },
  {
    x: 1663589600000,
    y: 0,
    yraw: 0,
    yamp: 1,
    ymove: 0
  },
  {
    x: 1663589652000,
    y: 0,
    yraw: 0,
    yamp: 1,
    ymove: 0
  },
  {
    x: 1663589688000,
    y: 0,
    yraw: 0,
    yamp: 1,
    ymove: 0
  }
]


컨베이어 동작은 지금 무조건 위로 올라가고 있다.
0,1,2..

유재선 상무


// get the parameters at the point around the found spot.
SELECT * FROM g5_1_data_measure_58 ORDER BY dta_dt DESC LIMIT 100;
2022-09-16 06:33:00

// Query speed is too slow.
SELECT * FROM g5_1_data_measure_60 WHERE 1=1 ORDER BY dta_idx DESC LIMIT 15 OFFSET 0
SELECT * FROM g5_1_data_measure_61 WHERE 1=1 ORDER BY dta_dt DESC LIMIT 15 OFFSET 0

SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_60')


SELECT mta_value FROM g5_5_meta WHERE mta_key LIKE 'dta_type_label%' AND mta_db_table = 'mms' AND mta_db_id = '58' ORDER BY mta_key




lbk1130 / lee0710a

// 로봇 데이터가 자꾸 죽어요.
admin 계정
cd robot
nohup bash robot_mon_start.sh &
nohup.out

// 조치 제안 토픽모델링 실행하는 방법
admin 계정 로그인
cd maintain
python maintain_test.py


SELECT * FROM g5_5_meta
WHERE mta_db_table = 'mms' AND mta_db_id = 59


SELECT mta_key, mta_value
FROM g5_5_meta
WHERE mta_key LIKE 'dta_type_label%' 
    AND mta_db_table = 'mms' AND mta_db_id = '59'
ORDER BY mta_key
....
SELECT mta_key, mta_value
  , SUBSTRING_INDEX(mta_key,'-',2) AS dta_type
  , SUBSTRING_INDEX(mta_key,'-',-1) AS dta_no
  , SUBSTRING_INDEX(mta_key,'-',-2) AS dta_type_no
FROM g5_5_meta
WHERE mta_key LIKE 'dta_type_label%' 
    AND mta_db_table = 'mms' AND mta_db_id = '59'
ORDER BY mta_key
....
SELECT mta_key, mta_value
  , SUBSTRING_INDEX(SUBSTRING_INDEX(mta_key,'-',-2),'-',1) AS dta_type
  , SUBSTRING_INDEX(mta_key,'-',-1) AS dta_no
FROM g5_5_meta
WHERE mta_key LIKE 'dta_type_label%' 
    AND mta_db_table = 'mms' AND mta_db_id = '59'
ORDER BY convert(dta_type, decimal), convert(dta_no, decimal)
....

DELETE FROM g5_5_meta WHERE mta_key IN ('james1','james2');



$mbd['data'][0]['name'] = '검출압력';
$mbd['data'][0]['id']['dta_data_url_host'] = 'hanjoo.epcs.co.kr';
$mbd['data'][0]['id']['dta_data_url_path'] = '/user/json';
$mbd['data'][0]['id']['dta_data_url_file'] = 'measure.php';
$mbd['data'][0]['id']['mms_idx'] = 60;
$mbd['data'][0]['id']['dta_type'] = 8;
$mbd['data'][0]['id']['dta_no'] = 1;
$mbd['data'][0]['id']['type1'] = '';
$mbd['data'][0]['id']['graph_name'] = '%EA%B2%80%EC%B6%9C%EC%95%95%EB%A0%A5';
$mbd['data'][0]['id']['graph_id'] = 'NjBfOF8xXw';
$mbd['data'][0]['type'] = 'spline';
$mbd['data'][0]['dashStyle'] = 'solid';

SELECT *
FROM g5_1_cast_shot_pressure
WHERE shot_id IN (".$shod_ids2[0].",".$shod_ids1[0].",".$shod_ids1[1].")
ORDER BY event_time


SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run WHERE dta_dt >= '1664580032' AND dta_dt <= '1664652224' AND mms_idx IN (63,64)
144228
5316

SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run WHERE dta_dt >= '1664580032' AND dta_dt <= '1664652224' AND mms_idx = '63'
72114
2658
// by the unit of one hour.
SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run
WHERE dta_dt >= UNIX_TIMESTAMP('2022-10-01 08:20:32') AND dta_dt <= UNIX_TIMESTAMP('2022-10-01 09:20:32')
AND mms_idx = '63'
....
SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run
WHERE dta_dt >= UNIX_TIMESTAMP('2022-10-01 10:00:00') AND dta_dt <= UNIX_TIMESTAMP('2022-10-01 11:00:00')
AND mms_idx = '63'
....
SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run
WHERE dta_dt >= UNIX_TIMESTAMP('2022-10-01 00:00:00') AND dta_dt <= UNIX_TIMESTAMP('2022-10-01 23:59:59')
AND mms_idx = '63'
....
SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run
WHERE dta_dt >= UNIX_TIMESTAMP('2022-10-01 00:00:00') AND dta_dt <= UNIX_TIMESTAMP('2022-10-01 23:59:59')
AND mms_idx = '64'
....
SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run
WHERE dta_dt >= UNIX_TIMESTAMP('2022-09-30 00:00:00') AND dta_dt <= UNIX_TIMESTAMP('2022-09-30 23:59:59')
AND mms_idx = '63'
....
SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run
WHERE dta_dt >= UNIX_TIMESTAMP('2022-09-30 00:00:00') AND dta_dt <= UNIX_TIMESTAMP('2022-09-30 23:59:59')
AND mms_idx = '64'
....

SELECT SUM(dta_value) AS dta_sum, COUNT(*) AS dta_count FROM g5_1_data_run WHERE dta_dt >= '1664580032' AND dta_dt <= '1664652224' AND mms_idx = '64'
72114
2658


SELECT work_date AS dta_date
    , SUBSTRING(qrcode,7,1) AS item_type
    , SUBSTRING(qrcode,8,2) AS item_lhrh
    , min(end_time) AS dta_ymdhis_min
    , max(end_time) AS dta_ymdhis_max
    , REPLACE(SUBSTRING(min(end_time),11,9),':','') AS dta_start_his
    , REPLACE(SUBSTRING(max(end_time),11,9),':','') AS dta_end_his
FROM g5_1_xray_inspection
WHERE work_date = '2022-10-01'
....
SELECT *
FROM g5_1_xray_inspection
WHERE work_date = '2022-10-01'
....
SELECT machine_id, COUNT(*) AS dta_count
FROM g5_1_xray_inspection
WHERE work_date = '2022-09-30'
GROUP BY machine_id
....
SELECT GROUP_CONCAT(machine_id) AS machine_ids
FROM (
  SELECT machine_id, COUNT(*) AS dta_count
  FROM g5_1_xray_inspection
  WHERE work_date = '2022-09-30'
  GROUP BY machine_id
) AS db1
....
SELECT machine_id, COUNT(*) AS dta_count
FROM g5_1_xray_inspection
WHERE work_date = '2022-10-01'
GROUP BY machine_id
....
SELECT work_date AS dta_date
    , SUBSTRING(qrcode,7,1) AS item_type
    , SUBSTRING(qrcode,8,2) AS item_lhrh
    , min(end_time) AS dta_ymdhis_min
    , max(end_time) AS dta_ymdhis_max
    , REPLACE(SUBSTRING(min(end_time),11,9),':','') AS dta_start_his
    , REPLACE(SUBSTRING(max(end_time),11,9),':','') AS dta_end_his
FROM g5_1_xray_inspection
WHERE work_date = '2022-09-30' AND machine_id=56
....
SELECT work_date AS dta_date
    , SUBSTRING(qrcode,7,1) AS item_type
    , SUBSTRING(qrcode,8,2) AS item_lhrh
    , min(end_time) AS dta_ymdhis_min
    , max(end_time) AS dta_ymdhis_max
    , REPLACE(SUBSTRING(min(end_time),11,9),':','') AS dta_start_his
    , REPLACE(SUBSTRING(max(end_time),11,9),':','') AS dta_end_his
FROM g5_1_xray_inspection
WHERE work_date = '2022-09-30' AND machine_id=60
....

구배 시간 P0S 0 SV SETTING

// optimal cast parameters check and monitring.
1. Expain page itself.
2. Go to detail page and setting for detail view.
3. Change setting name. 


// 통계..................................................................................
SELECT * FROM g5_1_xray_inspection ORDER BY xry_idx DESC LIMIT 100;

// 설비별
SELECT (CASE WHEN n='1' THEN machine_id ELSE 'total' END) AS item_name
    , SUM(output_total) AS output_total
    , MAX(output_total) AS output_max
    , SUM(output_good) AS output_good
    , SUM(output_defect) AS output_defect
FROM
(
    SELECT 
        machine_id
        , COUNT(xry_idx) AS output_total
        , SUM( CASE WHEN result = 'OK' THEN 1 ELSE 0 END ) AS output_good
        , SUM( CASE WHEN result = 'NG' THEN 1 ELSE 0 END ) AS output_defect
    FROM g5_1_xray_inspection
    WHERE work_date >= '2022-10-01' AND work_date <= '2022-10-31'
    GROUP BY machine_id
    ORDER BY machine_id
) AS db2, g5_5_tally AS db_no
WHERE n <= 2
GROUP BY item_name
ORDER BY n DESC, convert(item_name, decimal)


// 일자별
SELECT (CASE WHEN n='1' THEN ymd_date ELSE 'total' END) AS item_name
    , SUM(output_total) AS output_total
    , MAX(output_total) AS output_max
    , SUM(output_good) AS output_good
    , SUM(output_defect) AS output_defect
FROM
(
    SELECT 
        ymd_date
        , SUM(output_total) AS output_total
        , SUM(output_good) AS output_good
        , SUM(output_defect) AS output_defect
    FROM
    (
        (
        SELECT 
            CAST(ymd_date AS CHAR) AS ymd_date
            , 0 AS output_total
            , 0 AS output_good
            , 0 AS output_defect
        FROM g5_5_ymd AS ymd
        WHERE ymd_date BETWEEN '2022-10-01' AND '2022-10-31'
        ORDER BY ymd_date
        )
        UNION ALL
        (
        SELECT 
            work_date AS ymd_date
            , COUNT(xry_idx) AS output_total
            , SUM( CASE WHEN result = 'OK' THEN 1 ELSE 0 END ) AS output_good
            , SUM( CASE WHEN result = 'NG' THEN 1 ELSE 0 END ) AS output_defect
        FROM g5_1_xray_inspection
        WHERE work_date >= '2022-10-01' AND work_date <= '2022-10-31'
        GROUP BY ymd_date
        ORDER BY ymd_date
        )
    ) AS db_table
    GROUP BY ymd_date
) AS db2, g5_5_tally AS db_no
WHERE n <= 2
GROUP BY item_name
ORDER BY n DESC, item_name



// 월간
SELECT (CASE WHEN n='1' THEN ymd_month ELSE 'total' END) AS item_name
    , SUM(output_total) AS output_total
    , MAX(output_total) AS output_max
    , SUM(output_good) AS output_good
    , SUM(output_defect) AS output_defect
FROM
(

    SELECT 
        ymd_month
        , SUM(output_total) AS output_total
        , SUM(output_good) AS output_good
        , SUM(output_defect) AS output_defect
    FROM
    (
        (
        SELECT 
            substring( CAST(ymd_date AS CHAR),1,7) AS ymd_month
            , 0 AS output_total
            , 0 AS output_good
            , 0 AS output_defect
        FROM g5_5_ymd AS ymd
        WHERE ymd_date BETWEEN '2022-10-01' AND '2022-10-31'
        ORDER BY ymd_date
        )
        UNION ALL
        (
        SELECT 
            substring( CAST(work_date AS CHAR),1,7) AS ymd_month
            , COUNT(xry_idx) AS output_total
            , SUM( CASE WHEN result = 'OK' THEN 1 ELSE 0 END ) AS output_good
            , SUM( CASE WHEN result = 'NG' THEN 1 ELSE 0 END ) AS output_defect
        FROM g5_1_xray_inspection
        WHERE work_date >= '2022-10-01' AND work_date <= '2022-10-31'
        GROUP BY ymd_month
        ORDER BY ymd_month
        )
    ) AS db_table
    GROUP BY ymd_month

) AS db2, g5_5_tally AS db_no
WHERE n <= 2
GROUP BY item_name
ORDER BY n DESC, item_name



delete FROM `g5_1_member_dash` WHERE mb_id='ajintest'
delete FROM `g5_1_member_dash` WHERE mb_id='lbk1130'

SELECT mta_idx,mta_value,mta_title,mta_number FROM g5_5_meta WHERE mta_db_table = 'member' AND mta_db_id = 'super' AND mta_key = 'dashboard_menu' ORDER BY mta_number
SELECT mta_idx FROM g5_5_meta WHERE mta_db_table = 'member' AND mta_db_id = 'super' AND mta_key = 'dashboard_menu' ORDER BY mta_number


UPDATE g5_1_member_dash SET mbd_status = 'trash' ,mbd_update_dt = '2022-10-26 16:32:01' WHERE mta_idx = '417' AND dsg_idx = '3'
UPDATE g5_1_dash_grid SET dsg_status = 'trash' ,dsg_update_dt = '2022-10-26 16:32:01' WHERE mta_idx = '417' AND dsg_idx = '3'
SELECT mta_idx,mta_value,mta_title,mta_number FROM g5_5_meta WHERE mta_db_table = 'member' AND mta_db_id = 'super' AND mta_key = 'dashboard_menu' ORDER BY mta_number LIMIT 1

http://hanjoo.epcs.co.kr/adm/v10/ajax/dash.php?aj=mv1&mbd_idx=10&mta_idx=1265


// icmms에서 발송
Oct 31 21:02:24 q381-1158 sendmail[15376]: 29VC2Nr0015376: from=<master@icmms.co.kr>, size=1141, class=0, nrcpts=1, msgid=<7e4333e357a1525de4005c54def79476@icmms.co.kr>, proto=ESMTP, daemon=MTA, relay=localhost [127.0.0.1]
Oct 31 21:02:24 q381-1158 sendmail[15379]: STARTTLS=client, relay=mx1.naver.com., version=TLSv1/SSLv3, verify=FAIL, cipher=ECDHE-RSA-AES256-GCM-SHA384, bits=256/256
Oct 31 21:02:24 q381-1158 sendmail[15379]: 29VC2Nr0015376: to=<websiteman@naver.com>, delay=00:00:00, xdelay=00:00:00, mailer=esmtp, pri=121141, relay=mx1.naver.com. [125.209.238.100], dsn=2.0.0, stat=Sent (OK 6dOWzZKBTPSwJEJJAuZEZw - nsmtp)


// 보광에서 발송
Oct 31 21:03:40 q381-1158 sendmail[15444]: 29VC3c9f015444: from=<master@epcs.co.kr>, size=1148, class=0, nrcpts=1, msgid=<8f14a88e09facac75051d1faa016571b@bogwang.epcs.co.kr>, proto=ESMTP, daemon=MTA, relay=[119.202.74.194]
Oct 31 21:03:44 q381-1158 sendmail[15448]: STARTTLS=client, relay=mx.yandex.net., version=TLSv1/SSLv3, verify=FAIL, cipher=ECDHE-RSA-AES128-GCM-SHA256, bits=128/128
Oct 31 21:03:44 q381-1158 sendmail[15448]: 29VC3c9f015444: to=<websiteman@icmms.co.kr>, delay=00:00:04, xdelay=00:00:04, mailer=esmtp, pri=121148, relay=mx.yandex.net. [77.88.21.249], dsn=5.7.1, stat=User unknown
Oct 31 21:03:47 q381-1158 sendmail[15448]: 29VC3c9f015444: 29VC3l9f015448: DSN: User unknown

// 한주에서 발송
Oct 31 21:05:17 q381-1158 sendmail[15715]: 29VC5Hbm015715: ruleset=check_rcpt, arg1=<websiteman@naver.com>, relay=[61.83.89.93], reject=550 5.7.1 <websiteman@naver.com>... Relaying denied. IP name lookup failed [61.83.89.93]
Oct 31 21:05:17 q381-1158 sendmail[15715]: 29VC5Hbm015715: from=<jamesjoa@gmail.com>, size=0, class=0, nrcpts=0, proto=ESMTP, daemon=MTA, relay=[61.83.89.93]


 
http://hanjoo.epcs.co.kr/adm/v10/ajax/shot.json.php?frm_data=machine_id%3D45%26st_date%3D2022-10-31%26st_time%3D23%253A24%253A51%26en_date%3D2022-11-01%26en_time%3D01%253A21%253A20
 
SELECT * FROM g5_1_cast_shot WHERE machine_id = '45' AND start_time >= '2022-10-31 23:24:51' AND end_time <= '2022-11-01 01:21:20'


http://hanjoo.epcs.co.kr/adm/v10/ajax/shot.json.php?frm_data=token%3D7470e62d1b3d9eea5ce88bea4a127a55%26machine_id%3D59%26st_date%3D2022-11-08%26st_time%3D10%253A00%253A00%26en_date%3D2022-11-08%26en_time%3D10%253A39%253A00
SELECT * FROM g5_1_cast_shot WHERE machine_id = '59' AND start_time >= '2022-11-08 10:00:00' AND end_time <= '2022-11-08 10:39:00'


로봇정지, 실시간컨트롤

admin
['/home/admin/robot/james', '/usr/lib/python2.7', '/usr/lib/python2.7/plat-x86_64-linux-gnu', '/usr/lib/python2.7/lib-tk', '/usr/lib/python2.7/lib-old', '/usr/lib/python2.7/lib-dynload', '/home/admin/.local/lib/python2.7/site-packages', '/usr/local/lib/python2.7/dist-packages', '/usr/lib/python2.7/dist-packages']
['/home/admin/robot/james', '/usr/lib/python2.7', '/usr/lib/python2.7/plat-x86_64-linux-gnu', '/usr/lib/python2.7/lib-tk', '/usr/lib/python2.7/lib-old', '/usr/lib/python2.7/lib-dynload', '/usr/local/lib/python2.7/dist-packages', '/usr/lib/python2.7/dist-packages', '/home/admin/.local/lib/python2.7/site-packages']
['/home/admin/robot/james', '/usr/lib/python2.7', '/usr/lib/python2.7/plat-x86_64-linux-gnu', '/usr/lib/python2.7/lib-tk', '/usr/lib/python2.7/lib-old', '/usr/lib/python2.7/lib-dynload', '/home/hanjoo/.local/lib/python2.7/site-packages', '/usr/local/lib/python2.7/dist-packages', '/usr/lib/python2.7/dist-packages']

hanjoo
'/home/hanjoo/.local/lib/python2.7/site-packages'
www-data
'/usr/lib/python2.7/lib-dynload'

sudo -u www-data python blahblah.py

sudo -u admin python /home/admin/robot/james/robot_warn.py 1
>> 내 계정으로 로그인 후 이건 되네! (root 로그인 후 실행하면 안 된다.)
sudo -u root python /home/admin/robot/james/robot_warn.py 1
>> 이건 되네!
sudo -u www-data python /home/admin/robot/james/robot_warn.py 1
sudo -u admin python /home/admin/robot/james/z1.py
sudo -u www-data python /home/admin/robot/james/z1.py


sudo vi /etc/sudoers
www-data All=NOPASSWD: /usr/bin/python

sudo vi /etc/sudoers
www-data    raspberrypi=NOPASSWD:    /var/www/lights.py
www-data All=NOPASSWD: /home/admin/robot/james/robot_warn.py
www-data All=NOPASSWD: /home/admin/robot/james/z1.py


php python 실행, www-date 권한으로 실행
여러가시 시도들을 다 해 보다가...
모듈, 패키지를 다른 디렉토리에서 참고하면 안 되는 것 같고..
같은 디렉토리에 다 복제해서 넣어주니까 되는 거 같아요.


SELECT SQL_CALC_FOUND_ROWS * FROM g5_1_xray_inspection AS xry LEFT JOIN g5_1_qr_cast_code AS qrc USING(qrcode)
WHERE (1) ORDER BY xry_idx DESC LIMIT 0, 15

SELECT SQL_CALC_FOUND_ROWS * FROM g5_1_xray_inspection AS xry LEFT JOIN g5_1_qr_cast_code AS qrc USING(qrcode) 
WHERE (1) AND event_time >= '2022-12-01 11:51:14' ORDER BY xry_idx DESC LIMIT 0, 15



---------------------
재생성 하려면 지우고 다시 해야 합니다.
get-session-token was failing for me because I still had the environment variables AWS_SESSION_TOKEN and AWS_SECURITY_TOKEN set.
These should be unset first or AWS will try to use them implicitly and fail because they're invalid.

sudo rm -rf ~/.aws/credentials
sudo rm -rf ~/.aws/config
unset AWS_DEFAULT_REGION
unset AWS_ACCESS_KEY_ID
unset AWS_SECRET_ACCESS_KEY
unset AWS_SESSION_TOKEN

기본 설정부터 하고..
aws configure


james에 할당된 MFA 디바이스 확인해서 저장!!
arn:aws:iam::215907354426:mfa/james

Google OTP에서 확인해야 번호 확인해야 함 6자리 숫자

$ aws sts get-session-token --serial-number arn:aws:iam::215907354426:mfa/james --token-code 303586
james:~/environment $ aws sts get-session-token --serial-number arn:aws:iam::215907354426:mfa/james --token-code 303586
{
    "Credentials": {
        "AccessKeyId": "ASIATERIUY45B7QMIQK2",
        "SecretAccessKey": "ilRrWsUAlJrJPTvFylnFOti4W36AOWhgOZQZZTWv",
        "SessionToken": "FwoGZXIvYXdzEM3//////////wEaDIeZWkI9kwX99VYcnyKGAfYgFA5TK9AOCl0i1iTmP1APtmD2IlQtk9nodmBZ7TywBGOHSqJiTtUB2zcb9kXwbEvjGEVcyRcVcbRseKNFsM2pPkrLTOecbBHYgff2BPvIBcC2hl0dwD5td2ogpgGYwuJsHBT7V9+Tq6SO+Edl4xrundw6CUlDiUWYwAigQSeiXUbwVGCAKNuTspwGMijjMotHP/jKiIfTEJsIU4QfsbhgoZbXkkpbVEUSVaG/t/lQYxOV75Wm",
        "Expiration": "2022-12-04T23:51:55Z"
    }
}

받은 코드를 넣어서 환경설정 파일로 저장
export AWS_DEFAULT_REGION=us-east-1
export AWS_ACCESS_KEY_ID=ASIATERIUY45B7QMIQK2
export AWS_SECRET_ACCESS_KEY=ilRrWsUAlJrJPTvFylnFOti4W36AOWhgOZQZZTWv
export AWS_SESSION_TOKEN=FwoGZXIvYXdzEM3//////////wEaDIeZWkI9kwX99VYcnyKGAfYgFA5TK9AOCl0i1iTmP1APtmD2IlQtk9nodmBZ7TywBGOHSqJiTtUB2zcb9kXwbEvjGEVcyRcVcbRseKNFsM2pPkrLTOecbBHYgff2BPvIBcC2hl0dwD5td2ogpgGYwuJsHBT7V9+Tq6SO+Edl4xrundw6CUlDiUWYwAigQSeiXUbwVGCAKNuTspwGMijjMotHP/jKiIfTEJsIU4QfsbhgoZbXkkpbVEUSVaG/t/lQYxOV75Wm


환경설정 파일 확인!!
echo $AWS_DEFAULT_REGION
echo $AWS_ACCESS_KEY_ID
echo $AWS_SECRET_ACCESS_KEY
echo $AWS_SESSION_TOKEN

다른 방법 확인
env | grep AWS 


이건 잘 되는데..
james:~/environment $ curl -s https://d2s8p88vqu9w66.cloudfront.net/releases/greengrass-nucleus-latest.zip > greengrass-nucleus-latest.zip && unzip greengrass-nucleus-latest.zip -d GreengrassInstaller
Archive:  greengrass-nucleus-latest.zip
  inflating: GreengrassInstaller/LICENSE  
  inflating: GreengrassInstaller/NOTICE  
  inflating: GreengrassInstaller/README.md  
  inflating: GreengrassInstaller/THIRD-PARTY-LICENSES  
  inflating: GreengrassInstaller/bin/greengrass.exe  
  inflating: GreengrassInstaller/bin/greengrass.service.template  
  inflating: GreengrassInstaller/bin/greengrass.xml.template  
  inflating: GreengrassInstaller/bin/loader  
  inflating: GreengrassInstaller/bin/loader.cmd  
  inflating: GreengrassInstaller/conf/recipe.yaml  
  inflating: GreengrassInstaller/lib/Greengrass.jar  

이제 이거 실행하면 에러나요..
sudo -E java -Droot="/greengrass/v2" -Dlog.store=FILE -jar ./GreengrassInstaller/lib/Greengrass.jar --aws-region us-east-1 --thing-name GreengrassQuickStartCore-184dcf24fe1 --thing-group-name GreengrassQuickStartGroup --component-default-user ggc_user:ggc_group --provision true --setup-system-service true --deploy-dev-tools true
에러나는 이유는 설정을 몇 가지 빠뜨렸기 때문이죠.
단계 3에서 1.2.3.4 특히 2번 항목을 잘 읽어보고
james 권한을 다 줘야 하고..
사용자 및 그룹 권한도 생성해서 만들어야 해요.


....
TES role alias "GreengrassV2TokenExchangeRoleAlias" does not exist, creating new alias...
Error while trying to setup Greengrass Nucleus
software.amazon.awssdk.services.iam.model.IamException: The security token included in the request is invalid (Service: Iam, Status Code: 403, Request ID: d71276c1-1b55-4aa6-b747-e590cfc53d45)
        at software.amazon.awssdk.core.internal.http.CombinedResponseHandler.handleErrorResponse(CombinedResponseHandler.java:125)
        at software.amazon.awssdk.core.internal.http.CombinedResponseHandler.handleResponse(CombinedResponseHandler.java:82)
        at com.aws.greengrass.easysetup.GreengrassSetup.performSetup(GreengrassSetup.java:324)
        at com.aws.greengrass.easysetup.GreengrassSetup.main(GreengrassSetup.java:274)


참고: https://docs.aws.amazon.com/greengrass/v2/developerguide/configure-greengrass-core-v2.html#configure-system-service
참고: https://nasanx2001.tistory.com/entry/%EC%9A%B0%EB%B6%84%ED%88%AC-1804-%EC%9E%90%EB%8F%99%EC%8B%A4%ED%96%89-%EC%84%9C%EB%B9%84%EC%8A%A4%EB%93%B1%EB%A1%9D

james:~/environment $ sudo vi /etc/systemd/system/greengrass.service
---------
[Unit]
Description=Greengrass Core

[Service]
Type=simple
PIDFile=/greengrass/v2/alts/loader.pid
RemainAfterExit=no
Restart=on-failure
RestartSec=10
ExecStart=/bin/sh /greengrass/v2/alts/current/distro/bin/loader

[Install]
WantedBy=multi-user.target
------------

To check the status of the service (systemd)
# sudo systemctl status greengrass.service

To enable the nucleus to start when the device boots.
# sudo systemctl enable greengrass.service

To stop the nucleus from starting when the device boots.
# sudo systemctl disable greengrass.service

To start the AWS IoT Greengrass Core software.
# sudo systemctl start greengrass.service

To stop the AWS IoT Greengrass Core software.
# sudo systemctl stop greengrass.service


사용자 생성
greengrass
AKIATERIUY45AT42WBLW
0cAhpkUJ0ckzCGTX2kESbJWEOw4fnJhKic9TcP9w
us-east-1
json



/greengrass/v2/bin/greengrass-cli -V
이거 안 나오더라.. 
그래서 찾아봤더니.. cli 설정을 먼저 해 줘야 하더라.

greengrass CLI 설정하기
https://docs.aws.amazon.com/greengrass/v2/developerguide/install-gg-cli.html

중간쯤
Deploy the Greengrass CLI component
여기 부분 아래를 실행하세요.

