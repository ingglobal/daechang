from datetime import datetime, timedelta


d1 = datetime.now().strftime('%Y-%m-%d %H:%M:%S') # 2023-02-27 11:11:11
# print(d1)

data_path = f'/home/daechang/daechang_www/data'
pg_path = f'{data_path}/python/latest/pgsql'

f = open(f'{pg_path}/{d1}', 'w')
f.write(str(d1))
f.close() # 쓰기모드 닫기

