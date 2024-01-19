import os
from time import sleep

def production_count(dic, myconn1, my1):

    # print(lst)

    # # Example for MySQL
    # sql = f" INSERT INTO g5_5_meta SET " \
    #     f" mta_country = '', " \
    #     f" mta_db_table = 'test', " \
    #     f" mta_db_id = '01000001111', " \
    #     f" mta_key = 'mb_employee_memo', " \
    #     f" mta_value = '', " \
    #     f" mta_title = '', " \
    #     f" mta_number = 0, " \
    #     f" mta_status = 'ok', " \
    #     f" mta_reg_dt = '2024-01-16 10:16:50', " \
    #     f" mta_update_dt = '2024-01-16 10:16:50' "
    # # my1.execute(sql)
    # myconn1.commit()  # commit() does in production.py file. not every each excution()
    
    # get related info form db
    pri = get_table('g5_1_production_item','pri_idx',dic['pri_idx'], myconn1, my1)
    bom = get_table('g5_1_bom', 'bom_idx', dic['bom_idx'], myconn1, my1)
    print(bom)
    
    # 작업자 생산제품 입력(production_item_count)
    sql = f" INSERT INTO g5_1_production_item_count SET " \
        f" pri_idx = '{dic['pri_idx']}', " \
        f" mb_id = '{pri['mb_id']}', " \
        f" pic_ing = '{pri['pri_ing']}', " \
        f" pic_value = '{dic['count']}', " \
        f" pic_date = '{arr['pic_date']}', " \
        f" pic_reg_dt = '{dic['sck_dt']}', " \
        f" pic_update_dt = now(), "
    # my1.execute(sql)

    return 4
    # return None

def get_table(table_name, db_field, db_id, myconn1, my1, db_fields='*'):
    
    sql = f" SELECT {db_fields} FROM {table_name} WHERE {db_field} = {db_id} LIMIT 1 "
    # print(sql)
    my1.execute(sql)
    one = my1.fetchone()
    if one is not None:
        columns = [column[0] for column in my1.description]
        row = dict(zip(columns, one))
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

