디비 export (mysql접속하지 않고 명령어 실행)
# mysqldump -u root -psuper@ingglobal* daechang_www > /tmp/daechang_www.sql


디비 삭제하고 재생성
# mysql -u root -psuper@ingglobal*
> DROP DATABASE daechang_test;
> CREATE DATABASE daechang_test
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;


디비 import (mysql접속한다)
# mysql -u root -psuper@ingglobal*
> USE daechang_test;
> source /tmp/daechang_www.sql;


임지 저장한 /tmp/daechang_www.sql을 삭제
# rm -rf /tmp/daechang_www.sql