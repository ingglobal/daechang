pgAdmin..
..................................................................................
SELECT * FROM g5_1_cast_shot_sub ORDER BY event_time DESC LIMIT 100;
SELECT count(*) FROM g5_1_cast_shot_sub;
SELECT * FROM g5_1_cast_shot_pressure ORDER BY event_time DESC LIMIT 100;
SELECT * FROM g5_1_melting_temp ORDER BY event_time DESC LIMIT 100;
SELECT * FROM g5_1_factory_temphum ORDER BY event_time DESC LIMIT 100;
SELECT * FROM g5_1_cast_shot_sub ORDER BY css_idx DESC LIMIT 100;
SELECT * FROM g5_1_cast_shot ORDER BY start_time DESC LIMIT 100;
SELECT * FROM g5_1_data_measure_58 ORDER BY dta_dt DESC LIMIT 100;
SELECT * FROM g5_1_data_measure_59 WHERE dta_type = 1 ORDER BY dta_dt DESC LIMIT 100;
SELECT * FROM g5_1_data_measure_61 WHERE dta_idx=2
SELECT * FROM g5_1_data_measure_60 WHERE dta_type IN (1,8) ORDER BY dta_dt DESC LIMIT 100;
SELECT count(*) FROM g5_1_data_measure_61
SELECT count(*) FROM g5_1_data_measure_64
SELECT * FROM g5_1_xray_inspection ORDER BY xry_idx DESC LIMIT 100;

SELECT * FROM pg_stat_activity ORDER BY query_start ASC;
SELECT pg_cancel_backend(31956);

DELETE FROM g5_1_cast_shot_sub WHERE event_time > '2022-06-04 00:00:00'
DELETE FROM g5_1_cast_shot_pressure WHERE event_time > '2022-06-04 00:00:00'

..................................................................................

-- Create a temporary TIMESTAMP column
ALTER TABLE g5_1_robot ADD COLUMN time_temp TIMESTAMP without time zone NOT NULL DEFAULT (current_timestamp AT TIME ZONE 'Asia/Seoul');

-- Copy casted value over to the temporary column
UPDATE g5_1_robot SET time_temp = time::TIMESTAMP;

-- Modify original column using the temporary column
ALTER TABLE g5_1_robot ALTER COLUMN time TYPE TIMESTAMP without time zone USING time_temp;

-- Drop the temporary column (after examining altered column values)
ALTER TABLE g5_1_robot DROP COLUMN time_temp;

SELECT create_hypertable('g5_1_robot', 'time', migrate_data => true);


CREATE TABLE g5_1_robot_test (
  id SERIAL,
  time timestamp without time zone NOT NULL DEFAULT (current_timestamp AT TIME ZONE 'Asia/Seoul'),
  robot_no integer NOT NULL,
  tq1 DOUBLE PRECISION NULL,
  tq2 DOUBLE PRECISION NULL,
  tq3 DOUBLE PRECISION NULL,
  tq4 DOUBLE PRECISION NULL,
  tq5 DOUBLE PRECISION NULL,
  tq6 DOUBLE PRECISION NULL,
  et1 DOUBLE PRECISION NULL,
  et2 DOUBLE PRECISION NULL,
  et3 DOUBLE PRECISION NULL,
  et4 DOUBLE PRECISION NULL,
  et5 DOUBLE PRECISION NULL,
  et6 DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_robot_test', 'time');
CREATE INDEX g5_1_robot_test_robot_no ON g5_1_robot_test (robot_no);

INSERT INTO "g5_1_robot_test" ("id", "time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6")
VALUES ('1', '2022-08-10 08:16:43',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36');
INSERT INTO "g5_1_robot_test" ("id", "time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6")

// auto_increment 생성
// pgadmin 페이지에서 column 항목을 보고 Default 항목의 설명을 보면 정확한 이름이 나와있음
ALTER SEQUENCE g5_1_robot_test_id_seq RESTART WITH 3;

// auto_increment가 되는지 입력해 봄
VALUES ('2', '2022-08-10 08:16:43',           '2',       '31', '32',  '33',   '34',  '35',  '36',  '41', '42',  '43',   '44',  '45',  '46');
INSERT INTO "g5_1_robot_test" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6")
VALUES ('2022-08-10 08:16:43',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36');
INSERT INTO "g5_1_robot_test" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6")
VALUES ('2022-08-10 08:16:44',           '2',       '31', '32',  '33',   '34',  '35',  '36',  '41', '42',  '43',   '44',  '45',  '46');
INSERT INTO "g5_1_robot_test" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6")
VALUES ('2022-08-10 08:16:45',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36');

ALTER TABLE g5_1_robot RENAME TO g5_1_robot_bak;
ALTER TABLE g5_1_robot_test2 RENAME TO g5_1_robot;
ALTER TABLE g5_1_robot_test RENAME TO g5_1_robot_test3;
ALTER TABLE g5_1_robot RENAME TO g5_1_robot_test2;

DROP TABLE g5_1_robot_test2;

CREATE TABLE g5_1_robot_test2 (
  rob_idx SERIAL,
  time timestamp without time zone NOT NULL DEFAULT (current_timestamp AT TIME ZONE 'Asia/Seoul'),
  robot_no integer NOT NULL,
  tq1 DOUBLE PRECISION NULL,
  tq2 DOUBLE PRECISION NULL,
  tq3 DOUBLE PRECISION NULL,
  tq4 DOUBLE PRECISION NULL,
  tq5 DOUBLE PRECISION NULL,
  tq6 DOUBLE PRECISION NULL,
  et1 DOUBLE PRECISION NULL,
  et2 DOUBLE PRECISION NULL,
  et3 DOUBLE PRECISION NULL,
  et4 DOUBLE PRECISION NULL,
  et5 DOUBLE PRECISION NULL,
  et6 DOUBLE PRECISION NULL,
  mtq1 DOUBLE PRECISION NULL,
  mtq2 DOUBLE PRECISION NULL,
  mtq3 DOUBLE PRECISION NULL,
  mtq4 DOUBLE PRECISION NULL,
  mtq5 DOUBLE PRECISION NULL,
  mtq6 DOUBLE PRECISION NULL,
  alarm DOUBLE PRECISION NULL,
  status DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_robot_test2', 'time');
CREATE INDEX g5_1_robot_test2_robot_no ON g5_1_robot_test2 (robot_no);

INSERT INTO "g5_1_robot_test2" ("rob_idx", "time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('1', '2022-08-10 08:16:43',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36',  '31', '32',  '33',   '34',  '35',  '36',  '1',  '2');
INSERT INTO "g5_1_robot_test2" ("rob_idx", "time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('2', '2022-08-10 08:16:43',           '2',       '31', '32',  '33',   '34',  '35',  '36',  '41', '42',  '43',   '44',  '45',  '46',  '51', '52',  '53',   '54',  '55',  '56',  '1',  '2');

// auto_increment 생성
// pgadmin 페이지에서 column 항목을 보고 Default 항목의 설명을 보면 정확한 이름이 나와있음
ALTER SEQUENCE g5_1_robot_test2_rob_idx_seq RESTART WITH 3;

// auto_increment가 되는지 입력해 봄
INSERT INTO "g5_1_robot_test2" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('2022-08-10 08:16:43',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36',  '31', '32',  '33',   '34',  '35',  '36',  '1',  '2');
INSERT INTO "g5_1_robot_test2" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('2022-08-10 08:16:44',           '2',       '31', '32',  '33',   '34',  '35',  '36',  '41', '42',  '43',   '44',  '45',  '46',  '41', '42',  '43',   '44',  '45',  '46',  '1',  '2');
INSERT INTO "g5_1_robot_test2" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('2022-08-10 08:16:45',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36',  '31', '32',  '33',   '34',  '35',  '36',  '1',  '2');

DROP TABLE g5_1_borot;

CREATE TABLE g5_1_robot (
  rob_idx SERIAL,
  time timestamp without time zone NOT NULL DEFAULT (current_timestamp AT TIME ZONE 'Asia/Seoul'),
  robot_no integer NOT NULL,
  tq1 DOUBLE PRECISION NULL,
  tq2 DOUBLE PRECISION NULL,
  tq3 DOUBLE PRECISION NULL,
  tq4 DOUBLE PRECISION NULL,
  tq5 DOUBLE PRECISION NULL,
  tq6 DOUBLE PRECISION NULL,
  et1 DOUBLE PRECISION NULL,
  et2 DOUBLE PRECISION NULL,
  et3 DOUBLE PRECISION NULL,
  et4 DOUBLE PRECISION NULL,
  et5 DOUBLE PRECISION NULL,
  et6 DOUBLE PRECISION NULL,
  mtq1 DOUBLE PRECISION NULL,
  mtq2 DOUBLE PRECISION NULL,
  mtq3 DOUBLE PRECISION NULL,
  mtq4 DOUBLE PRECISION NULL,
  mtq5 DOUBLE PRECISION NULL,
  mtq6 DOUBLE PRECISION NULL,
  alarm DOUBLE PRECISION NULL,
  status DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_robot', 'time');
CREATE INDEX g5_1_robot_robot_no ON g5_1_robot (robot_no);

INSERT INTO "g5_1_robot" ("rob_idx", "time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('1', '2022-08-10 08:16:43',           '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36',  '31', '32',  '33',   '34',  '35',  '36',  '1',  '2');
INSERT INTO "g5_1_robot" ("rob_idx", "time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('2', '2022-08-10 08:16:43',           '2',       '31', '32',  '33',   '34',  '35',  '36',  '41', '42',  '43',   '44',  '45',  '46',  '51', '52',  '53',   '54',  '55',  '56',  '1',  '2');

// auto_increment 생성
// pgadmin 페이지에서 column 항목을 보고 Default 항목의 설명을 보면 정확한 이름이 나와있음
ALTER SEQUENCE g5_1_robot_rob_idx_seq1 RESTART WITH 3;

// auto_increment가 되는지 입력해 봄
INSERT INTO "g5_1_robot" ("time", "robot_no", "tq1", "tq2", "tq3", "tq4", "tq5", "tq6", "et1", "et2", "et3", "et4", "et5", "et6", "mtq1", "mtq2", "mtq3", "mtq4", "mtq5", "mtq6", "alarm", "status")
VALUES ('2022-08-10 08:16:43',     '1',       '21', '22',  '23',   '24',  '25',  '26',  '31', '32',  '33',   '34',  '35',  '36',  '31', '32',  '33',   '34',  '35',  '36',  '1',  '2');



CREATE TABLE g5_1_data_measure_58 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_58', 'dta_dt');
CREATE INDEX g5_1_data_measure_58_idx_type ON g5_1_data_measure_58 (dta_type);
CREATE INDEX g5_1_data_measure_58_idx_type_no ON g5_1_data_measure_58 (dta_type, dta_no);

CREATE TABLE g5_1_data_measure_59 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_59', 'dta_dt');
CREATE INDEX g5_1_data_measure_59_idx_type ON g5_1_data_measure_59 (dta_type);
CREATE INDEX g5_1_data_measure_59_idx_type_no ON g5_1_data_measure_59 (dta_type, dta_no);

CREATE TABLE g5_1_data_measure_60 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_60', 'dta_dt');
CREATE INDEX g5_1_data_measure_60_idx_type ON g5_1_data_measure_60 (dta_type);
CREATE INDEX g5_1_data_measure_60_idx_type_no ON g5_1_data_measure_60 (dta_type, dta_no);

CREATE TABLE g5_1_data_measure_61 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_61', 'dta_dt');
CREATE INDEX g5_1_data_measure_61_idx_type ON g5_1_data_measure_61 (dta_type);
CREATE INDEX g5_1_data_measure_61_idx_type_no ON g5_1_data_measure_61 (dta_type, dta_no);

CREATE TABLE g5_1_data_measure_62 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_62', 'dta_dt');
CREATE INDEX g5_1_data_measure_62_idx_type ON g5_1_data_measure_62 (dta_type);
CREATE INDEX g5_1_data_measure_62_idx_type_no ON g5_1_data_measure_62 (dta_type, dta_no);

CREATE TABLE g5_1_data_measure_63 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_63', 'dta_dt');
CREATE INDEX g5_1_data_measure_63_idx_type ON g5_1_data_measure_63 (dta_type);
CREATE INDEX g5_1_data_measure_63_idx_type_no ON g5_1_data_measure_63 (dta_type, dta_no);

CREATE TABLE g5_1_data_measure_64 (
  dta_idx SERIAL,
  dta_dt TIMESTAMPTZ NOT NULL,
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
SELECT create_hypertable('g5_1_data_measure_64', 'dta_dt');
CREATE INDEX g5_1_data_measure_64_idx_type ON g5_1_data_measure_64 (dta_type);
CREATE INDEX g5_1_data_measure_64_idx_type_no ON g5_1_data_measure_64 (dta_type, dta_no);




CREATE TABLE g5_1_cast_shot_sub (
  css_idx SERIAL,
  shot_id integer NOT NULL,
  event_time TIMESTAMPTZ NOT NULL,
  hold_temp DOUBLE PRECISION NULL,
  upper_heat DOUBLE PRECISION NULL,
  lower_heat DOUBLE PRECISION NULL,
  upper_1_temp DOUBLE PRECISION NULL,
  upper_2_temp DOUBLE PRECISION NULL,
  upper_3_temp DOUBLE PRECISION NULL,
  upper_4_temp DOUBLE PRECISION NULL,
  upper_5_temp DOUBLE PRECISION NULL,
  upper_6_temp DOUBLE PRECISION NULL,
  lower_1_temp DOUBLE PRECISION NULL,
  lower_2_temp DOUBLE PRECISION NULL,
  lower_3_temp DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_cast_shot_sub', 'event_time');

CREATE TABLE g5_1_cast_shot_pressure (
  csp_idx SERIAL,
  shot_id integer NOT NULL,
  event_time TIMESTAMPTZ NOT NULL,
  detect_pressure DOUBLE PRECISION NULL,
  target_pressure DOUBLE PRECISION NULL,
  control_pressure DOUBLE PRECISION NULL,
  deviation_pressure DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_cast_shot_pressure', 'event_time');

CREATE TABLE g5_1_factory_temphum (
  fct_idx SERIAL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  event_time TIMESTAMPTZ NOT NULL,
  temp_avg DOUBLE PRECISION NULL,
  temp_max DOUBLE PRECISION NULL,
  temp_min DOUBLE PRECISION NULL,
  hum_avg DOUBLE PRECISION NULL,
  hum_max DOUBLE PRECISION NULL,
  hum_min DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_factory_temphum', 'event_time');


// make csv file from mysql
// You will not be able to download form the tool phpMyAdmin.
// You should do it form terminal.

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_cast_shot_sub
INTO OUTFILE 'g5_1_cast_shot_sub.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';
....
SELECT * FROM g5_1_cast_shot_pressure
INTO OUTFILE 'g5_1_cast_shot_pressure.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';
....
SELECT * FROM g5_1_factory_temphum
INTO OUTFILE 'g5_1_factory_temphum.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';
....

// root account is able to accesss the mysql folder.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_factory_temphum.csv
// You will see the status of making the csv file.
# mv g5_1_factory_temphum.csv /home/hanjoo/files/

// test for local mac file in pgAdmin.
# \COPY g5_1_cast_shot_sub FROM /Users/james/Downloads/g5_1_factory_temphum.csv WITH (FORMAT CSV, HEADER)

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_factory_temphum FROM /home/hanjoo/files/g5_1_factory_temphum.csv WITH (FORMAT CSV, HEADER)"
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_cast_shot_pressure FROM /home/hanjoo/files/g5_1_cast_shot_pressure.csv WITH (FORMAT CSV, HEADER)"

// test for new line. It should have popper serial for primary key of css_idx.
INSERT INTO "g5_1_cast_shot_sub" ("shot_id", "event_time", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES ('2324', '2020-11-02 08:16:43', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');

// css_idx = 3 ???
// You have set the value for the last number; It shoud be the lastNumber+1;
ALTER SEQUENCE g5_1_cast_shot_sub_css_idx_seq RESTART WITH 98347881;
ALTER SEQUENCE g5_1_cast_shot_pressure_csp_idx_seq RESTART WITH 137599094;
ALTER SEQUENCE g5_1_factory_temphum_fct_idx_seq RESTART WITH 137599094;

// Insert test again.
INSERT INTO "g5_1_cast_shot_sub" ("shot_id", "event_time", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES ('2324', '2020-11-02 08:16:43', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2');
// OK! it is working now.

// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_factory_temphum" ("work_date", "work_shift", "event_time", "temp_avg", "temp_max", "temp_min", "hum_avg", "hum_max", "hum_min")
VALUES ('2022-05-30', '1', '2022-05-30 23:59:59', '26.582', '26.582', '26.582', '26.582', '26.582', '26.582');
....

// now delete the test record.
delete FROM g5_1_factory_temphum WHERE fct_idx = 1268462;


====================================================
CREATE TABLE g5_1_cast_shot (
  csh_idx SERIAL,
  shot_id integer NOT NULL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  start_time TIMESTAMPTZ NOT NULL,
  end_time TIMESTAMPTZ NOT NULL,
  elapsed_time DOUBLE PRECISION NULL,
  machine_id integer default 0 not null,
  machine_no varchar(50) default '',
  item_no varchar(50) default '',
  item_name varchar(50) default '',
  mold_no integer NOT NULL default 0,
  shot_no integer default 0 not null,
  pv_cycletime DOUBLE PRECISION NULL,
  machine_cycletime DOUBLE PRECISION NULL,
  product_cycletime DOUBLE PRECISION NULL,
  hold_temp DOUBLE PRECISION NULL,
  upper_heat DOUBLE PRECISION NULL,
  lower_heat DOUBLE PRECISION NULL,
  upper_1_temp DOUBLE PRECISION NULL,
  upper_2_temp DOUBLE PRECISION NULL,
  upper_3_temp DOUBLE PRECISION NULL,
  upper_4_temp DOUBLE PRECISION NULL,
  upper_5_temp DOUBLE PRECISION NULL,
  upper_6_temp DOUBLE PRECISION NULL,
  lower_1_temp DOUBLE PRECISION NULL,
  lower_2_temp DOUBLE PRECISION NULL,
  lower_3_temp DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_cast_shot', 'start_time');

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_cast_shot
INTO OUTFILE 'g5_1_cast_shot.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';

exit (to hanjoo account)
and you have to go to root account.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_cast_shot.csv
# mv g5_1_cast_shot.csv /home/hanjoo/files/

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_cast_shot FROM /home/hanjoo/files/g5_1_cast_shot.csv WITH (FORMAT CSV, HEADER)"


exit (go back to hanjoo account)
chck for the inserted data.
---------
SELECT * FROM g5_1_cast_shot ORDER BY start_time DESC LIMIT 100;
---------

copy the last PRIMARY KEY (csh_idx)
now reset the serial value for new count starting point.

// You have set the value for the last number; It shoud be the lastNumber+1;
// Just find it the value name in pgAdmin field property.
ALTER SEQUENCE g5_1_cast_shot_csh_idx_seq RESTART WITH 237340;


// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_cast_shot" ("shot_id", "work_date", "work_shift", "start_time", "end_time", "elapsed_time", "machine_id", "machine_no", "item_no", "item_name", "mold_no", "shot_no", "pv_cycletime", "machine_cycletime", "product_cycletime", "hold_temp", "upper_heat", "lower_heat", "upper_1_temp", "upper_2_temp", "upper_3_temp", "upper_4_temp", "upper_5_temp", "upper_6_temp", "lower_1_temp", "lower_2_temp", "lower_3_temp")
VALUES ('184426', '2022-05-30', '1', '2022-05-30 23:59:50', '2022-05-30 23:59:58', '232', '45', '0', '55451', '0', '2', '231', '245.8', '0', '0', '692', '365.55', '369.9', '0', '0', '0', '0', '321.425', '345.6', '407.7', '0', '0');

// now delete the test record.
delete FROM g5_1_cast_shot WHERE csh_idx = 237340;

now program for auto input.
====================================================
====================================================
CREATE TABLE g5_1_xray_inspection (
  xry_idx SERIAL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  start_time TIMESTAMPTZ NOT NULL,
  end_time TIMESTAMPTZ NOT NULL,
  qrcode varchar(50) default '',
  production_id integer default 0 not null,
  machine_id integer default 0 not null,
  machine_no varchar(50) default '',
  position_1 integer default 0,
  position_2 integer default 0,
  position_3 integer default 0,
  position_4 integer default 0,
  position_5 integer default 0,
  position_6 integer default 0,
  position_7 integer default 0,
  position_8 integer default 0,
  position_9 integer default 0,
  position_10 integer default 0,
  position_11 integer default 0,
  position_12 integer default 0,
  position_13 integer default 0,
  position_14 integer default 0,
  position_15 integer default 0,
  position_16 integer default 0,
  position_17 integer default 0,
  position_18 integer default 0
);
SELECT create_hypertable('g5_1_xray_inspection', 'start_time');

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_xray_inspection
INTO OUTFILE 'g5_1_xray_inspection.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';

exit (to hanjoo account)
and you have to go to root account.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_xray_inspection.csv
# mv g5_1_xray_inspection.csv /home/hanjoo/files/

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_xray_inspection FROM /home/hanjoo/files/g5_1_xray_inspection.csv WITH (FORMAT CSV, HEADER)"


exit (go back to hanjoo account)
chck for the inserted data.
---------
SELECT * FROM g5_1_xray_inspection ORDER BY start_time DESC LIMIT 100;
---------

copy the last PRIMARY KEY (xry_idx)
now reset the serial value for new count starting point.

// You have set the value for the last number; It shoud be the lastNumber+1;
// Just find it the value name in pgAdmin field property.
ALTER SEQUENCE g5_1_xray_inspection_xry_idx_seq RESTART WITH 8978;


// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_xray_inspection" ("work_date", "work_shift", "start_time", "end_time", "qrcode", "production_id", "machine_id", "machine_no", "position_1", "position_2", "position_3", "position_4", "position_5", "position_6", "position_7", "position_8", "position_9", "position_10", "position_11", "position_12", "position_13", "position_14", "position_15", "position_16", "position_17", "position_18")
VALUES ('2022-05-31', '1', '2022-05-31 23:59:50', '2022-05-31 23:59:58', '22E30DRLH01131111', '430142', '45', 'ADR1호기', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO "g5_1_xray_inspection" ("work_date", "work_shift", "start_time", "end_time", "qrcode", "production_id", "machine_id", "machine_no", "position_1", "position_2", "position_3", "position_4", "position_5", "position_6", "position_7", "position_8", "position_9", "position_10", "position_11", "position_12", "position_13", "position_14", "position_15", "position_16", "position_17", "position_18")
VALUES ('2022-05-31', '1', '2022-05-31 23:59:50', '2022-05-31 23:59:58', '22E30DRLH01131112', '430142', '45', 'ADR1호기', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

SELECT * FROM g5_1_xray_inspection WHERE work_date = '2022-05-31' AND production_id = '430142';
SELECT * FROM g5_1_xray_inspection WHERE qrcode = '22E30DRLH01131111';

// now delete the test record.
delete FROM g5_1_xray_inspection WHERE xry_idx = 8980;
delete FROM g5_1_xray_inspection WHERE xry_idx = 8981;

now program for auto input.
====================================================
====================================================
CREATE TABLE g5_1_engrave_qrcode (
  eqr_idx SERIAL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  event_time TIMESTAMPTZ NOT NULL,
  qrcode varchar(50) default '',
  production_id integer default 0 not null
);
SELECT create_hypertable('g5_1_engrave_qrcode', 'event_time');

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_engrave_qrcode
INTO OUTFILE 'g5_1_engrave_qrcode.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';

exit (to hanjoo account)
and you have to go to root account.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_engrave_qrcode.csv
# mv g5_1_engrave_qrcode.csv /home/hanjoo/files/

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_engrave_qrcode FROM /home/hanjoo/files/g5_1_engrave_qrcode.csv WITH (FORMAT CSV, HEADER)"


exit (go back to hanjoo account)
chck for the inserted data.
---------
SELECT * FROM g5_1_engrave_qrcode ORDER BY event_time DESC LIMIT 100;
---------

copy the last PRIMARY KEY (eqr_idx)
now reset the serial value for new count starting point.

// You have set the value for the last number; It shoud be the lastNumber+1;
// Just find it the value name in pgAdmin field property.
# ALTER SEQUENCE g5_1_engrave_qrcode_eqr_idx_seq RESTART WITH 403142;


// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_engrave_qrcode" ("work_date", "work_shift", "event_time", "qrcode", "production_id")
VALUES ('2022-05-31', '1', '2022-05-31 23:59:50', '22E30DRLH01131112', '430142');

// now delete the test record.
delete FROM g5_1_engrave_qrcode WHERE eqr_idx = 403142;

now program for auto input.
====================================================
====================================================
CREATE TABLE g5_1_melting_temp (
  mlt_idx SERIAL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  event_time TIMESTAMPTZ NOT NULL,
  temp_avg DOUBLE PRECISION NULL,
  temp_max DOUBLE PRECISION NULL,
  temp_min DOUBLE PRECISION NULL,
  alarm_min DOUBLE PRECISION NULL,
  alarm_max DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_melting_temp', 'event_time');

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_melting_temp
INTO OUTFILE 'g5_1_melting_temp.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';

exit (to hanjoo account)
and you have to go to root account.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_melting_temp.csv
# mv g5_1_melting_temp.csv /home/hanjoo/files/

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_melting_temp FROM /home/hanjoo/files/g5_1_melting_temp.csv WITH (FORMAT CSV, HEADER)"


exit (go back to hanjoo account)
chck for the inserted data.
---------
SELECT * FROM g5_1_melting_temp ORDER BY event_time DESC LIMIT 100;
---------

copy the last PRIMARY KEY (mlt_idx)
now reset the serial value for new count starting point.

// You have set the value for the last number; It shoud be the lastNumber+1;
// Just find it the value name in pgAdmin field property.
ALTER SEQUENCE g5_1_melting_temp_mlt_idx_seq RESTART WITH 741821;


// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_melting_temp" ("work_date", "work_shift", "event_time", "temp_avg", "temp_max", "temp_min", "alarm_min", "alarm_max")
VALUES ('2022-05-31', '1', '2022-05-31 23:59:59', '26.582', '26.582', '26.582', '26.582', '26.582');

// now delete the test record.
delete FROM g5_1_melting_temp WHERE mlt_idx = 741821;

now program for auto input.
====================================================


ALTER TABLE g5_1_cast_shot ALTER COLUMN item_no TYPE varchar(50);
ALTER TABLE g5_1_cast_shot ALTER COLUMN item_no SET DEFAULT '';
....
ALTER TABLE g5_1_cast_shot ALTER COLUMN item_name TYPE varchar(50);
ALTER TABLE g5_1_cast_shot ALTER COLUMN item_name SET DEFAULT '';
....
ALTER TABLE g5_1_cast_shot ALTER COLUMN mold_no TYPE varchar(50);
ALTER TABLE g5_1_cast_shot ALTER COLUMN mold_no SET DEFAULT '';

ALTER TABLE g5_1_cast_shot ALTER COLUMN mold_no TYPE integer;
ALTER TABLE g5_1_cast_shot ALTER COLUMN mold_no SET DEFAULT 0;
....


====================================================
CREATE TABLE g5_1_charge_out (
  cho_idx SERIAL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  event_time TIMESTAMPTZ NOT NULL,
  weight_out integer default 0,
  temp_out integer default 0,
  temp_gbf integer default 0,
  machine_1_id integer default 0,
  machine_1_no varchar(50) default '',
  machine_2_id integer default 0,
  machine_2_no varchar(50) default '',
  machine_3_id integer default 0,
  machine_3_no varchar(50) default ''
);
SELECT create_hypertable('g5_1_charge_out', 'event_time');

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_charge_out
INTO OUTFILE 'g5_1_charge_out.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';

exit (to hanjoo account)
and you have to go to root account.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_charge_out.csv
# mv g5_1_charge_out.csv /home/hanjoo/files/

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_charge_out FROM /home/hanjoo/files/g5_1_charge_out.csv WITH (FORMAT CSV, HEADER)"


exit (go back to hanjoo account)
chck for the inserted data.
---------
SELECT * FROM g5_1_charge_out ORDER BY event_time DESC LIMIT 100;
---------

copy the last PRIMARY KEY (cho_idx)
now reset the serial value for new count starting point.

// You have set the value for the last number; It shoud be the lastNumber+1;
// Just find it the value name in pgAdmin field property.
ALTER SEQUENCE g5_1_charge_out_cho_idx_seq RESTART WITH 42744;  (lastNumber+1)


// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_charge_out" ("work_date", "work_shift", "event_time", "weight_out", "temp_out", "temp_gbf", "machine_1_id", "machine_1_no", "machine_2_id", "machine_2_no", "machine_3_id", "machine_3_no")
VALUES ('2022-05-31', 1, '2022-05-31 23:59:59', 450, 740, 0, 1, 'LP01', 2, 'LP02', 0, 'LP02');


// now delete the test record.
delete FROM g5_1_charge_out WHERE cho_idx = 42744;

now program for auto input.
====================================================
====================================================
CREATE TABLE g5_1_charge_in (
  chi_idx SERIAL,
  work_date date NOT NULL,
  work_shift integer default 0 not null,
  event_time TIMESTAMPTZ NOT NULL,
  weight_total DOUBLE PRECISION NULL,
  weight_ingot DOUBLE PRECISION NULL,
  weight_scrap DOUBLE PRECISION NULL
);
SELECT create_hypertable('g5_1_charge_in', 'event_time');

mysql -u root -p
use hanjoo_www
............
SELECT * FROM g5_1_charge_in
INTO OUTFILE 'g5_1_charge_in.csv'
CHARACTER SET utf8
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n';

exit (to hanjoo account)
and you have to go to root account.
$ su -
$ cd /var/lib/mysql/hanjoo_www
$ ls -al g5_1_charge_in.csv
# mv g5_1_charge_in.csv /home/hanjoo/files/

# sudo su - postgres
# psql -U postgres -d hanjoo_www -c "\COPY g5_1_charge_in FROM /home/hanjoo/files/g5_1_charge_in.csv WITH (FORMAT CSV, HEADER)"


exit (go back to hanjoo account)
chck for the inserted data.
---------
SELECT * FROM g5_1_charge_in ORDER BY event_time DESC LIMIT 100;
---------

copy the last PRIMARY KEY (chi_idx)
now reset the serial value for new count starting point.

// You have set the value for the last number; It shoud be the lastNumber+1;
// Just find it the value name in pgAdmin field property.
ALTER SEQUENCE g5_1_charge_in_chi_idx_seq RESTART WITH 34089;  (lastNumber+1)


// Insert test and chech for the last number for PRIMARY KEY.
INSERT INTO "g5_1_charge_in" ("work_date", "work_shift", "event_time", "weight_total", "weight_ingot", "weight_scrap")
VALUES ('2022-05-31', 1, '2022-05-31 23:59:59', 949, 922, 27);


// now delete the test record.
delete FROM g5_1_charge_in WHERE chi_idx = 34089;

now program for auto input.
====================================================

UPDATE g5_1_cast_shot_sub AS css SET
  machine_id = coalesce((SELECT machine_id FROM g5_1_cast_shot WHERE shot_id = css.shot_id),0)
WHERE event_time > '2022-06-01 00:00:00'
....
UPDATE g5_1_cast_shot_pressure AS csp SET
  machine_id = coalesce((SELECT machine_id FROM g5_1_cast_shot WHERE shot_id = csp.shot_id),0)
WHERE event_time > '2022-06-01 00:00:00'
....


SELECT * FROM pg_tables;
SELECT 1 as flag FROM pg_tables WHERE tableowner='postgres' AND tablename='g5_1_data_measure_58';
SELECT COUNT(*) FROM pg_tables WHERE tableowner='postgres' AND tablename='g5_1_charge_in';
SELECT 1 FROM pg_tables WHERE schemaname='postgres' AND tablename='g5_1_data_measure_58';

SELECT EXISTS (
  SELECT 1 FROM pg_tables WHERE tableowner='postgres' AND tablename='g5_1_data_measure_58'
) AS flag

SELECT dta_type, COUNT(*) AS sum_dta_type FROM g5_1_data_measure_58 GROUP BY dta_type
SELECT dta_type, COUNT(*) AS sum_dta_type FROM g5_1_data_measure_59 GROUP BY dta_type
SELECT dta_type, COUNT(*) AS sum_dta_type FROM g5_1_data_measure_60 GROUP BY dta_type
SELECT dta_type, COUNT(*) AS sum_dta_type FROM g5_1_data_measure_61 GROUP BY dta_type
SELECT dta_type, dta_no, COUNT(*) AS sum_dta_type FROM g5_1_data_measure_61 GROUP BY dta_type, dta_no

DELETE FROM g5_1_data_measure_61 WHERE dta_type = 0

// db exists?
SELECT * FROM pg_tables 
WHERE tableowner='postgres' AND tablename='g5_1_data_measure_58'

SELECT * FROM pg_tables
WHERE tableowner='postgres' AND tablename='g5_1_data_measure_58'

SELECT dta_type, dta_no FROM g5_1_data_measure_61 GROUP BY dta_type, dta_no ORDER BY dta_type, dta_no

SELECT * FROM pg_tables
WHERE tableowner='postgres' AND REGEXP_MATCHES(tablename, 'g5_1_data_measure_[0-9]+_[0-9]+_[0-9]+$', 'g')
....
SELECT * FROM pg_tables
WHERE tableowner='postgres' AND tablename='g5_1_data_measure_[0-9]+$'
....
SELECT * FROM pg_tables
WHERE tableowner='postgres' AND tablename~'g5_1_data_measure_[0-9]+$'

4866825
SELECT * FROM g5_1_data_measure_59 WHERE dta_idx = 4866825;


SELECT dta_type, dta_no, MAX(dta_value), MIN(dta_value) FROM g5_1_data_measure_59
WHERE dta_type IN (1,8)
  AND dta_dt >= '2022-07-14 12:07:05' AND dta_dt <= '2022-07-14 13:07:05'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no ASC


CREATE TABLE test (
  dta_idx SERIAL,
  dta_dt TIMESTAMP WITHOUT TIME ZONE DEFAULT timezone('utc' :: TEXT, now()),
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);
CREATE TABLE test (
  dta_idx SERIAL,
  dta_dt timestamp without time zone NOT NULL DEFAULT (current_timestamp AT TIME ZONE 'Asia/Seoul'),
  dta_type integer NOT NULL,
  dta_no integer NOT NULL,
  dta_value DOUBLE PRECISION NULL,
  dta_1 integer default 0,
  dta_2 integer default 0,
  dta_3 integer default 0
);

INSERT INTO "test" ("dta_idx", "dta_dt", "dta_type", "dta_no", "dta_value", "dta_1", "dta_2", "dta_3","dta_dt3")
VALUES ('4', '2020-11-02 08:16:43',     '2',          '2',      '2',        '2',    '2',    '2','2020-11-02 08:16:43');

ALTER TABLE test ADD COLUMN dta_dt2 TIMESTAMPTZ NOT NULL DEFAULT timezone('utc' :: TEXT, now());
ALTER TABLE test ADD COLUMN dta_dt3 timestamp without time zone NOT NULL DEFAULT (current_timestamp AT TIME ZONE 'UTC');


ALTER TABLE test ADD COLUMN dta_dt3 timestamp without time zone NOT NULL DEFAULT (current_timestamp);
ALTER TABLE test DROP COLUMN dta_dt3;

SELECT substring(dta_dt2::varchar,1,19) FROM test;
SELECT dta_dt2::timestamp FROM test;
UPDATE test SET dta_dt3 = dta_dt2::timestamp;
UPDATE test SET dta_dt3 = dta_dt;
UPDATE test SET dta_dt3 = to_char(dta_dt2, 'YYYY-MM-DD HH24:MI:SS');

UPDATE test SET dta_dt3 = substring(dta_dt2::varchar,1,19)::timestamp;

ALTER TABLE g5_1_data_measure_59 DROP COLUMN dta_dt2;
^^^^^^^^^^^^^^^^^^^^^^
ALTER TABLE g5_1_data_measure_64 ADD COLUMN dta_dt2 timestamp without time zone NOT NULL DEFAULT (current_timestamp);

ALTER TABLE g5_1_data_measure_64 ALTER COLUMN dta_dt TYPE timestamp;
>> error!!
ALTER TABLE g5_1_data_measure_64 ALTER COLUMN dta_dt TYPE integer NOT NULL DEFAULT (current_timestamp);

UPDATE g5_1_data_measure_64 SET dta_dt2 = dta_dt;
UPDATE g5_1_data_measure_64 SET dta_dt = dta_dt2;

SELECT * 
FROM g5_1_data_measure_58 
WHERE 1=1 AND dta_dt >= '2022-07-15 07:50:00' AND dta_dt <= '2022-07-15 08:10:00'
ORDER BY dta_idx DESC
LIMIT 15 OFFSET 0


// 각인 시간 10분 전후 5분 사이 값을 추적
SELECT dta_type, dta_no, AVG(dta_value) AS dta_value
FROM g5_1_data_measure_59
WHERE dta_type IN (1,8)
  AND dta_dt >= '2022-07-11 06:05:26' AND dta_dt <= '2022-07-11 06:15:26'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no ASC

SELECT machine_id
FROM g5_1_xray_inspection
GROUP BY machine_id
....
SELECT machine_id
FROM g5_1_cast_shot_pressure
GROUP BY machine_id
....
SELECT machine_id
FROM g5_1_cast_shot
GROUP BY machine_id
....
// 하루의 평균값을 일단은 최적값으로 넣어두자.
SELECT COUNT(dta_value) FROM g5_1_data_measure_59
WHERE dta_type IN (1,8)
  AND dta_dt >= date '2022-07-15'
  AND dta_dt < date '2022-07-15' + integer '1'
ORDER BY dta_dt
....
SELECT dta_type, dta_no, AVG(dta_value) AS dta_value
FROM g5_1_data_measure_59
WHERE dta_type IN (1,8)
  AND dta_dt >= date '2022-07-15'
  AND dta_dt < date '2022-07-15' + integer '1'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no
....
SELECT dta_type, dta_no, AVG(dta_value) AS dta_value
FROM g5_1_data_measure_60
WHERE dta_type IN (1,8)
  AND dta_dt >= date '2022-07-15'
  AND dta_dt < date '2022-07-15' + integer '1'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no
....
SELECT dta_type, dta_no, AVG(dta_value) AS dta_value
FROM g5_1_data_measure_60
WHERE dta_type IN (1,8)
  AND dta_dt >= date '2022-07-14'
  AND dta_dt < date '2022-07-14' + integer '1'
GROUP BY dta_type, dta_no
ORDER BY dta_type, dta_no
....
SELECT dta_type, dta_no, AVG(dta_value) AS dta_value, MIN(dta_idx) AS dta_idx 
FROM g5_1_data_measure_59 
WHERE dta_type IN (1,8) 
  AND dta_dt >= '2022-07-22 00:00:00' AND dta_dt <= '2022-07-22 23:59:59' 
GROUP BY dta_type, dta_no 
ORDER BY dta_type, dta_no ASC
....> 이걸로 최종 되었어요.


SELECT * FROM g5_1_cast_shot WHERE machine_id = '60' AND end_time <= '2022-09-16 06:33:00' ORDER BY csh_idx DESC LIMIT 1 OFFSET 0
SELECT * FROM g5_1_cast_shot WHERE machine_id = '45' AND end_time <= '2022-09-16 06:33:00' ORDER BY csh_idx DESC LIMIT 100 OFFSET 0

SELECT * FROM g5_1_cast_shot WHERE machine_id = '45' AND end_time >= '2022-09-16 06:33:00' ORDER BY csh_idx LIMIT 10
SELECT * FROM g5_1_cast_shot WHERE machine_id = '45' AND end_time < '2022-09-16 06:33:00' ORDER BY csh_idx DESC LIMIT 10

SELECT string_agg(shot_id::character varying,',') AS shot_ids
FROM g5_1_cast_shot WHERE machine_id = '45' AND end_time >= '2022-09-16 06:33:00' 
GROUP BY shot_id, csh_idx
ORDER BY csh_idx LIMIT 10

SELECT * FROM g5_1_cast_shot_sub WHERE shot_id IN (494082,494086,494090) AND machine_id = '45' ORDER BY event_time
starting poirt: 2022-09-16 06:22:25.608+09	
end point: 2022-09-16 06:39:03.581+09
total count: 692

// I want to know that the counts are sameme not only with shot_id array but also with time arrange.
SELECT * FROM g5_1_cast_shot_sub WHERE machine_id = '45' AND event_time >= '2022-09-16 06:22:25' AND event_time <= '2022-09-16 06:39:03.581+09'
ORDER BY event_time
total count: 692

// Ok! those are same with shot_ids and time arrange.

// data_measure_list page QUERYs which page are very slow to load.
SELECT * FROM pg_tables WHERE tableowner = 'postgres' AND tablename ~ 'g5_1_data_measure_[0-9]+$' ORDER BY tablename
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_58')
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_59')
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_60')
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_61')
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_62')
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_63')
SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_64')

SELECT row_estimate AS cnt FROM hypertable_approximate_row_count('g5_1_data_measure_59')
SELECT * FROM g5_1_data_measure_59 WHERE 1=1 ORDER BY dta_dt DESC LIMIT 15 OFFSET 0
SELECT * FROM g5_1_data_measure_60 WHERE 1=1 ORDER BY dta_dt DESC LIMIT 15 OFFSET 0
SELECT * FROM g5_1_data_measure_61 WHERE 1=1 ORDER BY dta_dt DESC LIMIT 15 OFFSET 0
SELECT * FROM g5_1_data_measure_58 WHERE 1=1 ORDER BY dta_dt DESC LIMIT 15 OFFSET 0


-------+--------+----------+-------------+-------------------------------+--------------------------------------------------------------------------------------------------------
  7405 | active | postgres |             | 2022-09-23 11:00:49.593405+09 | SELECT pid,state,usename,client_addr,query_start,query FROM pg_stat_activity ORDER BY query_start ASC;
 14281 |        | postgres |             |                               |
 14280 |        | postgres |             |                               |
 18707 | idle   | postgres | 192.1.2.1   |                               |
 14283 |        | postgres |             |                               |
 14284 |        | postgres |             |                               |
 14286 |        | postgres |             |                               |
 12453 | idle   | postgres | 127.0.0.1   |                               |
 14276 |        |          |             |                               |
 14275 |        |          |             |                               |
 14278 |        |          |             |                               |
 14277 |        |          |             |                               |
(12 rows)


SELECT column_name, data_type, character_maximum_length
FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = 'g5_1_robot';


SELECT * FROM g5_1_xray_inspection ORDER BY xry_idx DESC LIMIT 100;

// 설비 데이터 통계
SELECT * FROM g5_1_data_measure_58 ORDER BY dta_dt DESC LIMIT 100;

SELECT 
  COUNT(*) as count, 
  to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
FROM 
  g5_1_data_measure_58
WHERE 
  dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
GROUP BY 
  to_char(dta_dt, 'YYYY-MM-DD')
....
SELECT * 
FROM (
  (
    SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
    FROM g5_1_data_measure_58
    WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
    GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  )
  UNION ALL
  (
    SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
    FROM g5_1_data_measure_59
    WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
    GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  )
)
....
SELECT *
FROM (
  SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
  FROM g5_1_data_measure_58
  WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
  GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION
  SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
  FROM g5_1_data_measure_59
  WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
  GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
) AS db1
....
SELECT SUM(count) AS count_all, dta_date
FROM (
  SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
  FROM g5_1_data_measure_58
  WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
  GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION
  SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
  FROM g5_1_data_measure_59
  WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
  GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION
  SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date 
  FROM g5_1_data_measure_60
  WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08'
  GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
) AS db1
GROUP BY dta_date
....
SELECT SUM(count) AS count, dta_date
FROM ( 
  SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_58 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_59 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_60 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_61 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_62 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_63 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD')
  UNION SELECT COUNT(*) as count, to_char(dta_dt, 'YYYY-MM-DD') as dta_date FROM g5_1_data_measure_64 WHERE dta_dt >= '2022-11-01' AND dta_dt <= '2022-11-08' GROUP BY to_char(dta_dt, 'YYYY-MM-DD') 
) AS db1
GROUP BY dta_date


SELECT * FROM g5_1_robot ORDER BY time DESC LIMIT 100;
SELECT * FROM g5_1_robot WHERE robot_no = '1' ORDER BY time DESC LIMIT 10
// 로봇데이터 통계
SELECT 
  COUNT(*) as count, 
  to_char(time, 'YYYY-MM-DD') as dta_date 
FROM 
g5_1_robot
WHERE 
  time >= '2022-11-01' AND time <= '2022-11-08'
GROUP BY 
  to_char(time, 'YYYY-MM-DD')
....
