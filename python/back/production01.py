import threading
import psycopg2
import mysql.connector
import myfunction
from datetime import datetime, timedelta
import json
import os
import sys
# data 디렉토리를 sys.path에 추가합니다.
sys.path.append(os.path.join(os.getcwd(), "/home/daechang/daechang_www/data/python"))
# plc protocal 정의된 dictionary 배열 삽입
from data_socket import data_socket
from data_jig import data_jig

# the folder which has the most latest sck_idx.
pg_path = f'./latest/pgsql'
demo = 1

# PostgreSQL database connection
pg1_config = {
    'host': '172.18.0.5',
    'database': 'daechang_www',
    'user': 'postgres',
    'password': 'super@ingglobal*',
    'port': '5432',
}

# MySQL database connection
my1_config = {
    'host': '172.18.0.3',
    'database': 'daechang_www',
    'user': 'daechang',
    'password': 'daechang@ingglobal'
}

def handle_data():
    try:
        
        # database connection
        try:
            pgcon1 = psycopg2.connect(**pg1_config)
            pg1 = pgcon1.cursor()
        except Exception as e:
            print(e)
            
        try:
            myconn1 = mysql.connector.connect(**my1_config)
            my1 = myconn1.cursor()
        except Exception as e:
            print(e)
            
        d1 = datetime.now().strftime('%Y-%m-%d %H:%M:%S') # 2023-02-27 11:11:11
        d2 = datetime.now() + timedelta(minutes=10) # 10minutes after
        d3 = datetime.now() - timedelta(minutes=10) # 10minutes ago
        # print(d2)
        
        # PostgreSQL -------------------------------------------------------
        # get the previous counter
        sck_idx_last, sck_idx_dt = myfunction.get_most_recent_file_and_content(pg_path)
        if sck_idx_last is not None and sck_idx_dt is not None:
            # print(f"Contents of {sck_idx_last}:\n{sck_idx_dt}")
            sql_where = f" WHERE sck_idx > '{sck_idx_last}' AND sck_dt <= '{d2}' "
            # print(sck_idx_last)
            d4 = datetime.strptime(sck_idx_dt, '%Y-%m-%d %H:%M:%S') # Convert the string to a datetime object
            d5 = d4 + timedelta(hours=1)   # Calculate the datetime 1 hour later
            # if time difference is too much(1 hour), start recent ones.
            if d5.strftime('%Y-%m-%d %H:%M:%S') < d1:
                print('too old and need recent one.')
                sql_where = f" WHERE sck_dt >= '{d3}' "
        else:
            # print(f"No files found in {pg_path} or an error occurred.")
            sql_where = f" WHERE sck_dt >= '{d3}' "
        # print(sql_where)
            
        # ---------
        if demo:
            sql_where = f" WHERE sck_dt >= '{d3}' "
        # ---------
        sql = f" SELECT * FROM g5_1_socket {sql_where} ORDER BY sck_dt "
        # print(sql)
        pg1.execute(sql)
        data = pg1.fetchall()

        # make folder for variable of the previous count
        ip = '192.168.0.0'
        port = '1233'
        path0 = f'./addup/{ip}/{port}'
        if not os.path.isdir(path0):
            os.makedirs(path0)
        
        # get the previous counter
        # print(myfunction.get_most_recent_file(path0))

        # Close PostgreSQL cursor
        pg1.close()




        # reset prev counter for next use.
        myfunction.delete_all_files(path0)
            
        # create file and write.
        t3 = 77
        f = open(f'{path0}/{t3}', 'w')
        f.write(str(t3))
        f.close() # 쓰기모드 닫기


        # MySQL -------------------------------------------------------------
        idx = 0
        for row in data:
            # ---------
            if demo and idx>2:
                break
            # ---------
            # print(row)
            sck_idx = row[0]
            sck_dt = row[1].strftime('%Y-%m-%d %H:%M:%S')
            ip = row[2]
            port = row[3]
            print(sck_dt, ip, port, '--------------------------------')
            # get the list of values from json
            sck_values = json.loads(row[4])
            # print(sck_values)
            for i, v in enumerate(sck_values):
                # print(f"Index {i}: {v}")
                # print(end='')
                data_type = 'tag'
                # data_socket 에 선언되지 않은 것들은 except를 통해서 빠져버리고 있는 것만 표시됨 (no need for if.. else..)
                try:
                    if isinstance(data_socket[str(ip)][str(port)][str(i)], dict):
                        # print('dict')
                        data_type = data_socket[str(ip)][str(port)][str(i)]["0"]['ppr_data_type']
                    else:
                        # print('list')
                        data_type = data_socket[str(ip)][str(port)][str(i)][0]['ppr_data_type']
                    
                    # print(f'{i}[0][\'ppr_data_type\'] ------> {data_type}')
                    if data_type=='count':
                        # print(end='')
                        print(i, data_type)
                        
                    elif data_type=='addup':
                        print(end='')
                        # print(i, data_type)
                    elif data_type=='countercheck':
                        print(end='')
                        # print(i, data_type)
                    elif data_type=='runtime':
                        print(end='')
                        # print(i, data_type)
                    elif data_type=='alarm':
                        print(end='')
                        # print(i, data_type)
                    elif data_type=='tag':
                        print(end='')
                        # print(i, data_type)
                    
                except:
                    # print(i, '------> not defined.')
                    pass

            # my1.execute("INSERT INTO your_mysql_table (column1, column2) VALUES (%s, %s)", (row[0], row[1]))
            
            idx += 1

        # Commit changes in MySQL
        myconn1.commit()

        # Close MySQL cursor
        my1.close()

    except Exception as e:
        print(e)

    finally:
        # Close database connections in the 'finally' block to ensure they are closed even if an exception occurs
        pgcon1.close()
        myconn1.close()
        
        # leave the last idx of pgsql for next work.
        # make folder for variable of the previous count
        # sck_idx = '101123'
        myfunction.delete_all_files(pg_path)  # reset prev one
        f = open(f'{pg_path}/{sck_idx}', 'w')
        f.write(str(sck_dt))
        f.close() # 쓰기모드 닫기

def main():
    # Create a thread for handling data
    thread_handle_data = threading.Thread(target=handle_data)

    # Start the thread
    thread_handle_data.start()

    # Wait for the thread to finish
    thread_handle_data.join()

if __name__ == "__main__":
    main()
