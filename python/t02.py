from datetime import datetime, timedelta
import mysql.connector
import myfunction

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

sql1 = f"SELECT bom.bom_idx, bom.bom_type, bom.bom_name, bom_part_no " \
        f"FROM g5_1_bom AS bom " \
        f"WHERE bom_idx IN (2010,1986)"
# print(sql1)
my1.execute(sql1)
columns = [column[0] for column in my1.description]

rows = my1.fetchall()
for row in rows:
    row = dict(zip(columns, row))
    print(row)
    print(row.get('bom_type', 'Key not found'))
    
    sql3 = f"SELECT COUNT(mtr_idx) AS cnt FROM g5_1_material " \
            f"WHERE bom_idx = '{row['bom_idx']}' AND prd_idx = '0' AND pri_idx = '0' " \
            f"AND mtr_type = 'material' AND mtr_status = 'ok'"
    # print(sql3)
    my1.execute(sql3)
    one = my1.fetchone()
