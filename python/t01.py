from datetime import datetime, timedelta
import mysql.connector

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




sck_dt = '2024-01-19 11:41:32'
result = shift_idx(sck_dt)
print(result)