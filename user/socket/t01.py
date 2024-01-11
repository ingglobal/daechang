import psycopg2

# 첫 번째 데이터베이스 연결 정보
db1_config = {
    'host': 'localhost',
    'database': 'daechang_www',
    'user': 'postgres',
    'password': 'super@ingglobal*',
    'port': '5432',
}

# # 두 번째 데이터베이스 연결 정보
db2_config = {
    'host': '110.10.129.208',
    'database': 'daechang_www',
    'user': 'postgres',
    'password': 'super@ingglobal*',
    'port': '10432',
}

try:
    # 첫 번째 데이터베이스 연결
    conn1 = psycopg2.connect(**db1_config)
    print("첫 번째 데이터베이스 연결 성공!")

    # 두 번째 데이터베이스 연결
    conn2 = psycopg2.connect(**db2_config)
    print("두 번째 데이터베이스 연결 성공!")

    # 각 데이터베이스에 대한 추가 작업 수행 가능

except psycopg2.Error as e:
    print(f"데이터베이스 연결 오류: {e}")

finally:
    # 연결 해제
    if conn1:
        conn1.close()
        print("첫 번째 데이터베이스 연결 닫힘")
    if conn2:
        conn2.close()
        print("두 번째 데이터베이스 연결 닫힘")
