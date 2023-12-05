#!/bin/bash

# 로그파일경로
log_file="/home/daechang/www/user/log/cpu_check_log.txt"
# log_file="../log/cpu_check_log.txt"

# CPU 사용량 확인
cpu_usage_php=$(top -bn1 | grep "php-fpm7\.4" | head -n 1 | awk '{print $9}')
cpu_usage_mysql=$(top -bn1 | grep "mysqld" | head -n 1 | awk '{print $9}')
cpu_usage_postgres=$(top -bn1 | grep "postgres" | head -n 1 | awk '{print $9}')

# 소수점 제거 및 비교
cpu_percentage_php=${cpu_usage_php%.*}
cpu_percentage_mysql=${cpu_usage_mysql%.*}
cpu_percentage_postgres=${cpu_usage_postgres%.*}


# 로그 데이터 기록 함수
write_log() {
    local message=$1
    local timestamp=$(date +"%Y-%m-%d %H:%M:%S")
    # echo "$timestamp - $message" >> $log_file
}
top_15=$(top -bn1 | head -n 15)
timestamp=$(date +"%Y-%m-%d %H:%M:%S")
echo -e "$timestamp ---------------------------------------------------------\n$top_15" >> $log_file


# 로그 데이터 기록
write_log "php-fpm7.4 CPU 사용량: $cpu_usage_php"
write_log "mysqld CPU 사용량: $cpu_usage_mysql"
write_log "postgres CPU 사용량: $cpu_usage_postgres"


if [ $cpu_percentage_php -ge 100 ]; then
    systemctl restart php7.4-fpm.service
fi

if [ $cpu_percentage_mysql -ge 100 ]; then
    systemctl restart mysql.service
fi

if [ $cpu_percentage_postgres -ge 100 ]; then
    systemctl restart postgresql.service
fi

# 로그 파일 최근 1000줄만 남기고 오래된 행은 삭제
if [ $(wc -l < "$log_file") -gt 10000 ]; then
    tail -n 1000 "$log_file" > "$log_file.tmp"
    mv "$log_file.tmp" "$log_file"
fi
