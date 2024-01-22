import os
import re
from time import sleep
from config import *
# from config import myconn1, my1
from datetime import datetime, timedelta

def addslashes(input_str):
    return input_str.replace('\\', '\\\\').replace("'", "\\'")
    
def production_count(dic):
    # print(dic)

    # get related info form db
    pri = get_table('g5_1_production_item','pri_idx',dic['pri_idx'])
    prd = get_table('g5_1_production','prd_idx',pri['prd_idx'])
    bom = get_table('g5_1_bom', 'bom_idx', dic['bom_idx'])
    mms = get_table('g5_1_mms', 'mms_idx', pri['mms_idx'])
    # print(mms)

    # 통계 반영 일자
    pic_date = statics_date(dic['sck_dt'])
    # print(pic_date)
    shf_idx = shift_idx(dic['sck_dt'])
    
    # 설비가 수동입력(mms_manual_yn) 상태인지 확인, 수동 입력 상태면 카운터 입력을 안 함
    if mms['mms_manual_yn']:
        return None
    
    # 작업자 생산제품 입력(production_item_count) - 갯수만큼 입력
    for i in range(dic['count']):
        sql = f" INSERT INTO g5_1_production_item_count SET " \
            f" pri_idx = '{dic['pri_idx']}', " \
            f" mb_id = '{pri['mb_id']}', " \
            f" pic_ing = '{pri['pri_ing']}', " \
            f" pic_value = '{dic['count']}', " \
            f" pic_date = '{pic_date}', " \
            f" pic_reg_dt = '{dic['sck_dt']}', " \
            f" pic_update_dt = now() "
        # print(sql)
        my1.execute(sql)

    # 대표상품인지 아닌지 확인
    sql = f" SELECT bit_main_yn, bom_idx FROM g5_1_bom_item WHERE bom_idx_child = '{pri['bom_idx']}' "
    # print(sql)
    my1.execute(sql)
    one = my1.fetchone()
    main = {"bit_main_yn": 0, "bom_idx": 0}
    if one is not None:
        main = {"bit_main_yn": one[0], "bom_idx": one[1]}
        # print(main)
    else:
        print("Am i main product? No:", sql)

    # 내가 대표상품이거나 혹은 최상위 상품인 경우, 하위의 모든 제품 재고를 사용으로 처리해야 함
    if main['bit_main_yn'] or bom['bom_type']=='product':
        # 대표상품(main_yn)인 경우 최상위 부모 bom_idx 추출
        bom_idx = main['bom_idx'] if main['bit_main_yn'] else pri['bom_idx']
        # print(bom_idx)
        
        # 모든 하위 구조 추출해서 재고 먼저 떨어줍니다.
        sql1 = f"SELECT bom.bom_idx, bom.bom_type, bom.bom_name, bom_part_no, bom_price, bom_status, cst_idx_provider, cst_idx_customer " \
               f"     , bit.bit_idx, bit.bom_idx AS bit_bom_idx, bit.bit_main_yn, bit.bom_idx_child, bit.bit_reply, bit.bit_count " \
               f"FROM g5_1_bom_item AS bit " \
               f"     LEFT JOIN g5_1_bom AS bom ON bom.bom_idx = bit.bom_idx_child " \
               f" WHERE bit.bom_idx = '{bom_idx}' " \
               f" ORDER BY bit.bit_reply "
        # print(sql1)
        my1.execute(sql1)
        fields = [column[0] for column in my1.description]
        rows = my1.fetchall()

        # 대표상품이 아닌 완제품(product)인 경우...혹시 내 하위 다른 대표상품이 있다면 재고를 이미 반영한 것이므로 재고 털어주면 안 되요.
        if main['bit_main_yn']==0 or bom['bom_type']=='product':
            for row in rows:
                # print(row[0], row[1])
                row = dict(zip(fields, row))
                if row['bit_main_yn']:
                    return None

        # print('-----------')
        for row in rows:
            row = dict(zip(fields, row))
            bom_name = addslashes(row['bom_name'])
            # print(row)
            # count 갯수만큼 생산카운터 반영
            for i in range(dic['count']):
                if row['bom_type']=='half':
                    # 반제품 생산카운터 레코드 생성
                    sql2 = f" INSERT INTO g5_1_material SET " \
                        f" com_idx = '13', " \
                        f" cst_idx_provider = '{row['cst_idx_provider']}', " \
                        f" cst_idx_customer = '{row['cst_idx_customer']}', " \
                        f" mms_idx = '{pri['mms_idx']}', " \
                        f" ori_idx = '{prd['ori_idx']}', " \
                        f" prd_idx = '{prd['prd_idx']}', " \
                        f" pri_idx = '{dic['pri_idx']}', " \
                        f" bom_idx = '{row['bom_idx']}', " \
                        f" shf_idx = '{shf_idx}', " \
                        f" mb_id = '{pri['mb_id']}', " \
                        f" mtr_part_no = '{row['bom_part_no']}', " \
                        f" mtr_name = '{bom_name}', " \
                        f" mtr_type = '{row['bom_type']}', " \
                        f" mtr_value = '{dic['count']}', " \
                        f" mtr_price = '{row['bom_price']}', " \
                        f" mtr_history = 'finish|{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}', " \
                        f" mtr_status = 'finish', " \
                        f" mtr_date = '{pic_date}', " \
                        f" mtr_reg_dt = now(), " \
                        f" mtr_update_dt = now() "
                    # print(sql2)
                    my1.execute(sql2)
                elif row['bom_type']=='material':
                    # 해당 usage 갯수만큼 update (2이면 2개 업데이트)
                    limit_count = dic['count'] * row['bit_count']

                    # 만약 레코드가 존재하지 않는다면 생성을 해 주고 update를 해야 함
                    sql3 = f"   SELECT COUNT(mtr_idx) AS cnt FROM g5_1_material " \
                           f"   WHERE bom_idx = '{row['bom_idx']}' AND prd_idx = '0' AND pri_idx = '0' " \
                           f"   AND mtr_type = 'material' AND mtr_status = 'ok' "
                    # print(sql3)
                    my1.execute(sql3)
                    one = my1.fetchone()
                    if one is not None:
                        replenish = dic['count'] - int(one[0])
                    else:
                        replenish = dic['count']
                        # print("No rows returned:", sql)
                    if replenish>0:
                        # 없는 만큼 부족분 자재(material)를 먼저 생성해 두고...
                        for j in range(row['bit_count']*replenish):
                            sql2 = f" INSERT INTO g5_1_material SET " \
                                f" com_idx = '13', " \
                                f" cst_idx_provider = '{row['cst_idx_provider']}', " \
                                f" cst_idx_customer = '{row['cst_idx_customer']}', " \
                                f" mms_idx = '{pri['mms_idx']}', " \
                                f" bom_idx = '{row['bom_idx']}', " \
                                f" mtr_part_no = '{row['bom_part_no']}', " \
                                f" mtr_name = '{addslashes(row['bom_name'])}', " \
                                f" mtr_type = '{row['bom_type']}', " \
                                f" mtr_value = '1', " \
                                f" mtr_price = '{row['bom_price']}', " \
                                f" mtr_history = 'ok|{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}', " \
                                f" mtr_status = 'ok', " \
                                f" mtr_reg_dt = now(), " \
                                f" mtr_update_dt = now() "
                            # print(sql2)
                            my1.execute(sql2)
                           
                    sql2 = f"   UPDATE g5_1_material SET " \
                           f"       mms_idx = '{pri['mms_idx']}', " \
                           f"       ori_idx = '{prd['ori_idx']}', " \
                           f"       prd_idx = '{pri['prd_idx']}', " \
                           f"       pri_idx = '{dic['pri_idx']}', " \
                           f"       shf_idx = '{shf_idx}', " \
                           f"       mb_id = '', " \
                           f"       mtr_history = '\nused|{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}', " \
                           f"       mtr_status = 'used', " \
                           f"       mtr_update_dt = now() " \
                           f"     WHERE bom_idx = '{row['bom_idx']}' AND prd_idx = '0' AND pri_idx = '0' AND mtr_type = 'material' AND mtr_status = 'ok' " \
                           f"     ORDER BY mtr_reg_dt LIMIT {limit_count}"
                    # print(sql2)
                    my1.execute(sql2)


        # 제품(item) 테이블에 레코드 생성해 줍니다. count 갯수만큼 생성
        for i in range(dic['count']):
            sql2 = f" INSERT INTO g5_1_item SET " \
                f" com_idx = '13', " \
                f" cst_idx_provider = '{bom['cst_idx_provider']}', " \
                f" cst_idx_customer = '{bom['cst_idx_customer']}', " \
                f" mms_idx = '{pri['mms_idx']}', " \
                f" ori_idx = '{prd['ori_idx']}', " \
                f" prd_idx = '{prd['prd_idx']}', " \
                f" pri_idx = '{dic['pri_idx']}', " \
                f" bom_idx = '{bom['bom_idx']}', " \
                f" shf_idx = '{shf_idx}', " \
                f" mb_id = '{pri['mb_id']}', " \
                f" itm_part_no = '{bom['bom_part_no']}', " \
                f" itm_name = '{addslashes(row['bom_name'])}', " \
                f" itm_type = '{bom['bom_type']}', " \
                f" itm_value = '{dic['count']}', " \
                f" itm_price = '{bom['bom_price']}', " \
                f" itm_history = 'finish|{datetime.now().strftime('%Y-%m-%d %H:%M:%S')}', " \
                f" itm_status = 'finish', " \
                f" itm_date = '{pic_date}', " \
                f" itm_reg_dt = now(), " \
                f" itm_update_dt = now() "
            # print(sql2)
            my1.execute(sql2)


    return dic['pri_idx']
    # return None


def statics_date(dt):
    dt_arr = dt.split(' ')
    date_str = dt_arr[0]

    # Convert date string to datetime object
    date_obj = datetime.strptime(date_str, "%Y-%m-%d")

    # Get the last shift end time
    sql_shift = f"SELECT shf_end_time FROM g5_1_shift " \
                f"WHERE com_idx = 13 " \
                f"AND shf_end_prevday = '1' " \
                f"AND shf_period_type = '1' " \
                f"AND shf_status = 'ok' " \
                f"ORDER BY shf_idx DESC LIMIT 1"
    my1.execute(sql_shift)
    res = my1.fetchone()

    start_time = datetime.strptime('00:00:00', "%H:%M:%S")
    end_time = res[0] if res else None

    if isinstance(end_time, timedelta):
        # If it's a timedelta, convert it to a datetime object
        end_time = datetime(1, 1, 1) + end_time

    if end_time:
        time_stamp = datetime.strptime(dt_arr[1], "%H:%M:%S")

        if start_time <= time_stamp <= end_time:
            date_obj -= timedelta(days=1)

    return date_obj.strftime("%Y-%m-%d")


def shift_idx(dt):
    # Assuming dt is a string in the format 'YYYY-MM-DD HH:mm:ss'
    date = dt[:10]
    time = dt[-8:]
    t_stamp = int(datetime.strptime(time, "%H:%M:%S").timestamp())

    shf_idx = 0

    sql = f"SELECT shf_idx, shf_start_time, shf_end_time FROM g5_1_shift " \
          f"WHERE com_idx = '13' " \
          f"AND shf_period_type = '1' " \
          f"AND shf_status = 'ok' " \
          f"ORDER BY shf_idx"
    my1.execute(sql)
    rows = my1.fetchall()
    for row in rows:
        # s_time = row[1].strftime('%H:%M:%S')
        # e_time = row[2].strftime('%H:%M:%S')
        s_stamp = int(datetime.strptime(str(row[1]), "%H:%M:%S").timestamp())
        l_stamp = int(datetime.strptime('23:59:59', "%H:%M:%S").timestamp())
        f_stamp = int(datetime.strptime('00:00:00', "%H:%M:%S").timestamp())
        e_stamp = int(datetime.strptime(str(row[2]), "%H:%M:%S").timestamp())

        if e_stamp > s_stamp:
            if s_stamp <= t_stamp <= e_stamp:
                shf_idx = row[0]
        else:
            if (s_stamp <= t_stamp <= l_stamp) or (f_stamp <= t_stamp <= e_stamp):
                shf_idx = row[0]

    return shf_idx


# 특정 날짜에 일수를 더한 날짜를 반환해 주는 함수
def get_day_add_date(date_obj, day_num):
    return date_obj + timedelta(days=day_num)

def get_table(table_name, db_field, db_id, db_fields='*'):
    
    sql = f" SELECT {db_fields} FROM {table_name} WHERE {db_field} = {db_id} LIMIT 1 "
    # print(sql)
    my1.execute(sql)
    fields = [column[0] for column in my1.description]
    one = my1.fetchone()
    if one is not None:
        row = dict(zip(fields, one))
    else:
        row = {db_field:0}
    # print(row)
    # print(row[db_field])
    return row
    
def read_count_files(parent_top_path):
    data_count = {}

    for root, dirs, files in os.walk(parent_top_path):
        # Assuming 'count' files are present at the last level
        if 'count' in files:
            count_file_path = os.path.join(root, 'count')
            with open(count_file_path, 'r') as file:
                count_value = int(file.read())
            
            # Extract IP address, port, and subfolder from the path
            components = root.split(os.sep)
            ip_address = components[-3]
            port = components[-2]
            subfolder = components[-1]
            
            # Update data_count dictionary
            if ip_address not in data_count:
                data_count[ip_address] = {}
            if port not in data_count[ip_address]:
                data_count[ip_address][port] = {}
            
            data_count[ip_address][port][subfolder] = count_value
    
    return data_count
    
    
def read_file(file_path):
    try:
        with open(file_path, 'r') as file:
            content = file.read()
            return content
    except FileNotFoundError:
        print(f"File not found: {file_path}")
        return 0
    except Exception as e:
        print(f"An error occurred while reading the file: {e}")
        return 0

def get_most_recent_file_and_content(folder_path):
    try:
        # Get the list of files in the folder
        files = [f for f in os.listdir(folder_path) if os.path.isfile(os.path.join(folder_path, f))]

        # Find the most recently modified file
        most_recent_file = max(files, key=lambda f: os.path.getmtime(os.path.join(folder_path, f)))

        # Get the content of the most recently modified file
        most_recent_file_path = os.path.join(folder_path, most_recent_file)
        with open(most_recent_file_path, 'r') as file:
            file_content = file.read()

        return most_recent_file, file_content

    except Exception as e:
        print(f"An error occurred: {e}")
        return None, None

def get_most_recent_file(folder_path):
    try:
        # Get the list of files in the folder
        files = [f for f in os.listdir(folder_path) if os.path.isfile(os.path.join(folder_path, f))]

        # Find the most recently modified file
        most_recent_file = max(files, key=lambda f: os.path.getmtime(os.path.join(folder_path, f)))

        # Print the most recently modified file
        # print(f"Most recently modified file in {folder_path}: {most_recent_file}")
        return most_recent_file

    except Exception as e:
        print(f"An error occurred: {e}")
        return None

# get all the file names in the specific folder.
def list_files_in_folder(folder_path):
    try:
        # Get the list of files in the folder
        files = os.listdir(folder_path)

        # Filter only files and return the list
        file_names = [file_name for file_name in files if os.path.isfile(os.path.join(folder_path, file_name))]

        return file_names

    except Exception as e:
        print(f"An error occurred: {e}")
        return None

def delete_all_files(folder_path):
    try:
        # Get the list of files in the folder
        files = os.listdir(folder_path)

        # Iterate over the files and delete each one
        for file_name in files:
            file_path = os.path.join(folder_path, file_name)
            if os.path.isfile(file_path):
                os.remove(file_path)
                # print(f"Deleted: {file_path}")

        # print(f"All files in {folder_path} have been deleted.")
    except Exception as e:
        print(f"An error occurred while deleting file: {e}")
        return None

def get_int_1word(val):
  # 1word = 2bytes
  # int = 1234
  b1 = format(val,'016b')
  # print(b)
  c1 = b1[:8]
  c2 = b1[8:]
  # print(b1,'-',b2)
  d1 = int(c1,2)
  d2 = int(c2,2)
  return [d2,d1]

def get_int_2word(val):
  # 2words = 4bytes
  # dint1 = 123456789
  dint2 = format(val,'032b')
  # print(dint2)
  dint3 = dint2[:8]
  dint4 = dint2[8:16]
  dint5 = dint2[16:24]
  dint6 = dint2[24:32]
  # print(dint6,'-',dint5,'-',dint4,'-',dint3)
  dint7 = int(dint3,2)
  dint8 = int(dint4,2)
  dint9 = int(dint5,2)
  dint10 = int(dint6,2)
  # print(dint10,'-',dint9,'-',dint8,'-',dint7)
  return [dint10,dint9,dint8,dint7]

def get_str_ascii(str):
  # A=65(1byte), B=66(1byte)...
  # str1 = 65
  # print(ord(str))
  if(str):
    return ord(str)
  else:
    return 32

def get_str_1word(lst):
  # 1word = 2bytes (2개 문자), ex)AB CD
  # A=65(1byte), B=66(1byte)...
  lst2 = []
  for i,v in enumerate(lst):
    # print(i, v, ord(v))
    lst2.append(get_str_ascii(v))
  # print(lst2)
  return lst2


def get_bit_1word(str):
  # bit 처리
  # 0001000000010001 0001111100011111
  # bit1 = '0001000000010001'
  bit2 = str[:8]
  bit3 = str[8:]
  bit4 = int(bit2,2)
  bit5 = int(bit3,2)
  return [bit5,bit4]

