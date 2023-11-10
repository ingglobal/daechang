#!/bin/bash

# CPU 사용량 확인
cpu_usage_php=$(top -bn1 | grep "php7\.4-fpm" | awk '{print $9}')
cpu_usage_mysql=$(top -bn1 | grep "mysqld" | awk '{print $9}')
cpu_usage_postgres=$(top -bn1 | grep "postgres" | awk '{print $9}')

# 소수점 제거 및 비교
cpu_percentage_php=${cpu_usage_php%.*}
cpu_percentage_mysql=${cpu_usage_mysql%.*}
cpu_percentage_postgres=${cpu_usage_postgres%.*}

if [ $cpu_percentage_php -ge 100 ]; then
    systemctl restart php7.4-fpm.service
fi

if [ $cpu_percentage_mysql -ge 100 ]; then
    systemctl restart mysql.service
fi

if [ $cpu_percentage_postgres -ge 100 ]; then
    systemctl restart postgresql.service
fi


