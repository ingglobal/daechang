import socket
import sys
import threading
import struct
from datetime import datetime, timedelta
import time
import os
import sys
# data 디렉토리를 sys.path에 추가합니다.
# sys.path.append(os.path.join(os.getcwd(), "/home/hanho/hanho_www/data/python"))
sys.path.append(os.path.join(os.getcwd(), "../../data/python"))

import shutil
import json

# plc protocal 정의된 dictionary 배열 삽입
from data_socket import data_socket
# print (data_socket)
# ppr_name_value = data_socket['192.168.123.251']['20480']['0'][0]['ppr_name']
# print(ppr_name_value)

# 호스트와 포트 지정
host = ''
# ports = [20480, 20481, 20482]
ports = [20482]

clients = []
sockets = []

# 소켓에 호스트와 포트 바인딩
for i, port in enumerate(ports):
    # 소켓 생성
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    try:
        sock.bind((host, port))
        sockets.append(sock)
        print(f"Listening on port {port}...")
        # 클라이언트의 연결 요청을 기다림
        sock.listen()
    except socket.error as msg:
        sock.close()
        print ('Bind fallido. Error Code : ' + str(msg[0]) + ' Message ' + msg[1])
        sys.exit()

def handle_client(client_socket, addr):
    # 클라이언트 정보 초기화 코드 추가
    # clients.append((addr[0], addr[1], client_socket))
 
    while True:
        # 클라이언트가 보낸 데이터 수신
        rec = client_socket.recv(1024)
        if not rec: break
        
        # print (len(rec)) # 배열의 크기
        # print (rec)
        
        d2 = datetime.now().strftime('%Y-%m-%d %H:%M:%S') # 2023-02-27 11:11:11
        ip = addr[0]
        port = client_socket.getsockname()[1]
        # print(d2, addr[0] ,'/', port ,' => ', f"Received {addr}: {rec}", list[0:16])
        try:
            lst = []    # 최종 변수 list
            # 2byte 단위로 for 문장 순회하면서 2byte 단위로 처리함
            for i in range(0, len(rec), 2):
                idx = int(i/2) # 배열변호 = byte 단위의 1/2배
                # print(i,'-----------------')
                # print(rec[i],' / ',rec[i+1])
                # print(rec[i:i+2])
                # print(ip, port, idx)
                # print(d2, ip, port, idx)
                # print(idx,': ',len(data_socket[str(ip)][str(port)][str(idx)]), end='')
                # print(idx,': ',len(data_socket[str(ip)][str(port)][str(150)]))
                # print(data_socket[str(ip)][str(port)][str(150)][str(0)])
                # print(idx,': ',data_socket[str(ip)][str(port)][str(idx)])
                # if idx==220:
                #     print(idx,': ',data_socket[str(ip)][str(port)][str(idx)][1])
                #     print('=============')
                try:
                    if data_socket[str(ip)][str(port)][str(idx)][1]:
                        print(idx,': ',data_socket[str(ip)][str(port)][str(idx)][1])
                        print('=============')
                    elif data_socket[str(ip)][str(port)][str(idx)][str(1)]:
                        print(idx,': ',data_socket[str(ip)][str(port)][str(idx)][str(1)])
                        print('=============')
                except:
                    pass
                if i<4: # 정수
                    st = format(rec[i+1],'08b')
                    st += format(rec[i],'08b')
                    t1 = int(st,2)
                    # print(t1)
                    lst.append(t1)
                elif i<8:   # bit 구조
                    st = format(rec[i+1],'08b')
                    st += format(rec[i],'08b')
                    reversed_str = st[::-1]  # 문자열 뒤집기
                    reversed_binary = int(reversed_str, 2)  # 2진수 문자열을 10진수 정수로 변환
                    st2 = bin(reversed_binary)[2:].zfill(16)  # 10진수 정수를 2진수 문자열로 변환하고 16자리로 맞추기
                    # print(st2)
                    lst.append(st2)
                else:   # 부호가 있는 정수형
                    num = struct.unpack('!h', rec[i:i+2])[0]
                    lst.append(num)
            
            # print (lst)
            # lst[0] = ip
            print(d2, addr[0], port, '=>', lst[0:6],'...')
            # print(d2, addr[0], port, '\n', lst[0:6],'...')
            # print ('\n------------------------------------')
            
        except ValueError:
            print('ValueError occured.')
        except Exception as e:
            print(e)

    # 클라이언트가 연결을 끊었음
    print(f"Client {addr} disconnected.")
    clients.append((addr[0], addr[1], client_socket))
    client_socket.close()

while True:
    for sock in sockets:
        try:
            # Set a timeout for the accept call
            sock.settimeout(0.2)
            client_socket, addr = sock.accept()
            print(f"Accepted connection from {addr} on port {sock.getsockname()[1]}")

            # 연결된 클라이언트를 리스트에 추가
            clients.append((addr[0], addr[1], client_socket))

            # 클라이언트를 처리하는 스레드 생성
            client_thread = threading.Thread(target=handle_client, args=(client_socket, addr))
            client_thread.start()
        except socket.timeout:
            # Ignore timeout and continue to the next iteration
            continue
        except Exception as e:
            print(e)

    # Sleep for a short duration to avoid high CPU usage
    time.sleep(0.2)
