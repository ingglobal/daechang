import mysql.connector

# the folder definition
data_path = f'/home/daechang/daechang_www/data'
pg_path = f'{data_path}/python/latest/pgsql'
addup_path = f'{data_path}/python/addup'

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

try:
    myconn1 = mysql.connector.connect(**my1_config)
    my1 = myconn1.cursor()
except Exception as e:
    print(e)


# 세팅정보 추출
setting = {}
sql1 =  f" SELECT set_name, set_value FROM g5_5_setting " \
        f" WHERE set_name IN ('set_worker_test_yn','set_hp_test_yn','set_production_test_yn') "
# print(sql1)
my1.execute(sql1)
rows = my1.fetchall()
# data_jig = [{"set_name": row[0], "set_value": row[1]} for row in rows]
# print(data_jig)
for row in rows:
    setting[row[0]] = row[1]
    # print(row[0], row[1])
