import threading
import psycopg2
# import mysql.connector # it is located in myfunction.py file.
import myfunction
from datetime import datetime, timedelta
# from config import data_path, pg_path, addup_path, pg1_config, my1_config, myconn1, my1
from config import *
import json
import os
import sys
# data 디렉토리를 sys.path에 추가합니다.
sys.path.append(os.path.join(os.getcwd(), f'{data_path}/python'))
# plc protocal 정의된 dictionary 배열 삽입
from data_socket import data_socket
from data_jig import data_jig
from data_worker import data_worker

# ------------------------------------------> 실제 운영시 0 으로 세팅해 주세요.
demo = 0

def handle_data():
    try:
        
        # database connection
        try:
            pgcon1 = psycopg2.connect(**pg1_config)
            pg1 = pgcon1.cursor()
        except Exception as e:
            print(e)

        d1 = datetime.now().strftime('%Y-%m-%d %H:%M:%S') # 2023-02-27 11:11:11
        d2 = datetime.now() + timedelta(minutes=11) # 11minutes after
        d3 = datetime.now() - timedelta(minutes=10) # 10minutes ago
        # print(d2)
        
        # get the last count once from folder before starting -------------- 아직은 사용하지 않아요. 부하가 높아진다 싶을 때 활용 예정!
        last_count = myfunction.read_count_files(f'{addup_path}')
        # print(last_count)
        
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
                # print(f'{d5} < {d1}')
                print('too old and need recent one.')
                sql_where = f" WHERE sck_dt >= '{d3}' "
        else:
            # print(f"No files found in {pg_path} or an error occurred.")
            sql_where = f" WHERE sck_dt >= '{d3}' "
        # print(sql_where)
            
        # ---------
        if demo:
            sql_where = f" WHERE sck_dt >= '{d3}' "
            sql_where = f" WHERE sck_dt >= '2024-01-20 15:41:32' "
        # ---------
        sql = f" SELECT * FROM g5_1_socket {sql_where} ORDER BY sck_dt "
        # print(sql)
        pg1.execute(sql)
        data = pg1.fetchall()


        # MySQL -------------------------------------------------------------
        idx = 0
        for row in data:
            # ---------
            if demo and idx>3:
                break
            # ---------
            # print(row)
            sck_idx = row[0]
            sck_dt = row[1].strftime('%Y-%m-%d %H:%M:%S')
            sck_date = sck_dt[:10]
            sck_minute = sck_dt[-2:]
            # print(sck_minute)
            ip = row[2]
            port = row[3]
            print(f'\n{sck_dt} {ip} {port} --------------------------------')
            # get the list of values from json
            sck_values = json.loads(row[4])
            # print(sck_values)
            for i, v in enumerate(sck_values):
                # print(f"Index {i}: {v}")
                # print(end='')
                # bit ======================================== 알람, 카운터체크
                if isinstance(v, str):
                    # print(f'\n{i} {len(v)} {v} --------')
                    # 각 bit 를 순회하면서 1인 값만 처리함
                    for i1, v1 in enumerate(v):
                        # print(f'{i1}-{v1} / ', end='')
                        if int(v1):
                            # print(end='')
                            data_type = 'alarm' # default
                            try:
                                if isinstance(data_socket[str(ip)][str(port)][str(i)], dict):
                                    # print('dict')
                                    data_type = data_socket[str(ip)][str(port)][str(i)][str(i1)]['ppr_data_type']
                                    mms_idx = data_socket[str(ip)][str(port)][str(i)][str(i1)]['mms_idx']
                                    cod_idx = data_socket[str(ip)][str(port)][str(i)][str(i1)]['cod_idx']
                                else:
                                    # print('list')
                                    data_type = data_socket[str(ip)][str(port)][str(i)][i1]['ppr_data_type']
                                    mms_idx = data_socket[str(ip)][str(port)][str(i)][i1]['mms_idx']
                                    cod_idx = data_socket[str(ip)][str(port)][str(i)][i1]['cod_idx']
                            except:
                                # print(i, '------> not defined.')
                                pass
                            
                            # print(f'{i} {v} bit{i1} - {data_type} {mms_idx} {cod_idx}')

                # integer ==================================== 카운터, 측정값 등..
                else:
                    # print(end='')
                    # print(i, v, '--------')
                    if int(v):
                        data_type = 'tag'
                        try:
                            data_type = data_socket[str(ip)][str(port)][str(i)][0]['ppr_data_type']
                            mms_idx = data_socket[str(ip)][str(port)][str(i)][0]['mms_idx']
                            jig_code = data_socket[str(ip)][str(port)][str(i)][0]['ppr_jig_code']
                        except:
                            # print(i, '------> not defined.')
                            pass

                        # print(f'{i} {v} - {data_type} {mms_idx} {jig_code}')
                        if data_type=='count':
                            prev = {}
                            prev[ip] = {}
                            prev[ip][port] = {}
                            # make folder for variable of the previous count
                            # ip = '192.168.0.0'
                            # port = '1233'
                            path0 = f'{addup_path}/{ip}/{port}/{i}'
                            if not os.path.isdir(path0):
                                os.makedirs(path0)
                            
                            # get the count compared to the previous one
                            # print(myfunction.read_file(f'{path0}/count'))
                            old_count = int(myfunction.read_file(f'{path0}/count'))
                            now_count = int(v)
                            count = now_count - old_count
                            count = 1 if abs(count)>10 else abs(count)   # 30000 이상에서 다시 초기화되는 부분이 있어서 추가 (터무니 없는 값이면 일단 1로 설정)
                            # (테스트 카운터)해당 지그에 1로 세팅된 것이 하나라도 있으면 1분마다 count 넣어줌
                            try:
                                # print(f'{i}. now:{v} old:{old_count} - [{data_type}] mms_idx={mms_idx} jig_code={jig_code} count: {count}')
                                for row1 in data_jig[str(mms_idx)][str(jig_code)]:
                                    # print(row1['boj_test_yn'])
                                    if row1['boj_test_yn']=='1' and sck_minute=='00':
                                        count = 1
                            except:
                                pass
                                
                            if count>0: #----------------------------------
                                # print(f'{i}. now:{v} old:{old_count} - [{data_type}] mms_idx={mms_idx} jig_code={jig_code} count: {count}')

                                # get bom_idxs from data_jig dict. (/data/python/data_jig.py)
                                # This is originated by the db table g5_1_bom_jig
                                bom_idxs = []
                                try:
                                    # print(len(data_jig[str(mms_idx)][str(jig_code)]))
                                    for row1 in data_jig[str(mms_idx)][str(jig_code)]:
                                        # print(row1)
                                        bom_idxs.append(row1['bom_idx'])
                                except Exception as e:
                                    # print(f"error: {e} When mms_idx={mms_idx} jig_code={jig_code} count: {count}")
                                    pass
                                    
                                # print(bom_idxs)
                                bom_idxs_string = ','.join(map(str, bom_idxs))
                                # print(bom_idxs_string)
                                
                                # now get the worker info from production_item table.
                                if bom_idxs_string:
                                    # sql1 =  f" SELECT * FROM g5_1_production_item " \
                                    #         f" WHERE pri_ing = 1 AND bom_idx IN ({bom_idxs_string}) AND pri_date = '{sck_date}' " \
                                    #         f" ORDER BY pri_idx DESC LIMIT 1 "
                                    sql1 =  f" SELECT * FROM g5_1_production_item " \
                                            f" WHERE bom_idx IN ({bom_idxs_string}) AND pri_date = '{sck_date}' " \
                                            f" ORDER BY pri_idx DESC LIMIT 1 "
                                    # print(sql1)
                                    my1.execute(sql1)
                                    fields = [column[0] for column in my1.description]
                                    result = my1.fetchone()
                                    # print(result)
                                    if result is not None:
                                        print(f'{i}. now:{v} old:{old_count} - [{data_type}] mms_idx={mms_idx} jig_code={jig_code} count: {count}')
                                        # print(sql1)
                                        # Get column names from the description attribute
                                        pri = dict(zip(fields, result))
                                        # Now, pri is a dictionary with field names as keys
                                        # print(pri)
                                        # print(pri['bom_idx'])
                                        # 해당 설비, bom제품에 test_yn 1로 세팅된 작업자라면 pri_ing=1 인 걸로 보고 테스트 카운터 넣어줘야 함 (data/python/data_worker.py참고)
                                        try:
                                            if data_worker[str(mms_idx)][str(pri['bom_idx'])][str(pri['mb_id'])]["bmw_test_yn"]=='1':
                                                pri['pri_ing'] = 1
                                                print(pri['mb_id'], data_worker[str(mms_idx)][str(pri['bom_idx'])][str(pri['mb_id'])]["mb_name"], 'test 작업중')
                                        except Exception as e:
                                            print(f"data_worker.py read exception.(maybe not existed) {e}")

                                        if pri['pri_ing']:
                                            di = {}
                                            di['pri_idx'] = pri['pri_idx']
                                            di['bom_idx'] = pri['bom_idx']
                                            di['count'] = count
                                            di['sck_dt'] = sck_dt
                                            pri_idx = myfunction.production_count(di)
                                            del di
                                            print('pri_idx =',pri_idx)
                                    # else:
                                    #     print("No rows were fetched.")


                            # save prev sck_value for next calculation. not now, for next use.
                            prev[ip][port][i] = now_count
                            
                            # write current count for next use.
                            # ---------
                            if demo and ip=='192.168.100.137':
                                v -= 1 # (for test.)
                            # v -= 1 # (for test.)
                            # ---------
                            f = open(f'{path0}/count', 'w')
                            f.write(str(v))
                            f.close() # 쓰기모드 닫기
                            
                        elif data_type=='addup':
                            print(end='')
                            # print(i, data_type)
                        elif data_type=='countercheck':
                            print(end='')
                            # print(i, data_type)
                        elif data_type=='runtime':
                            print(end='')
                            # print(i, data_type)
                        elif data_type=='tag':
                            print(end='')
                            # print(i, data_type)
                        elif data_type=='alarm':
                            print(end='')
                            # print(i, data_type)

            # my1.execute("INSERT INTO your_mysql_table (column1, column2) VALUES (%s, %s)", (row[0], row[1]))
            
            idx += 1

        # Commit changes and close curosr in MySQL
        myconn1.commit()
        my1.close()

    except Exception as e:
        print(e)

    finally:
        # print(prev)
        # print(type(prev["192.168.100.137"]))
        # print(type(prev["192.168.100.137"][20480]))
        # Close database connections in the 'finally' block to ensure they are closed even if an exception occurs

        sql = f"DELETE FROM g5_1_socket WHERE sck_dt < NOW() - INTERVAL '1 day' "
        print(sql)
        pg1.execute(sql)

        # Commit changes and close curosr in Postgres
        pgcon1.commit()
        pg1.close()
        pgcon1.close()
        myconn1.close()
        
        # leave the last idx of pgsql for next work.
        # make folder for variable of the previous count
        # sck_idx = '101123'
        myfunction.delete_all_files(pg_path)  # reset prev one
        if sck_idx:
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
