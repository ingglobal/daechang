def production_count(dic, myconn1, my1):

    # get related info form db
    pri = get_table('g5_1_production_item','pri_idx',dic['pri_idx'], myconn1, my1)
    bom = get_table('g5_1_bom', 'bom_idx', dic['bom_idx'], myconn1, my1)
    print(bom)
    
    # 작업자 생산제품 입력(production_item_count)
    sql = f" INSERT INTO g5_1_production_item_count SET " \
        f" pri_idx = '{dic['pri_idx']}', " \
        ....
        f" pic_update_dt = now(), "
    my1.execute(sql)

    return None

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
    
