greengrass 참고메뉴얼


재생성 하려면 지우고 다시 해야 합니다.
get-session-token was failing for me because I still had the environment variables AWS_SESSION_TOKEN and AWS_SECURITY_TOKEN set.
These should be unset first or AWS will try to use them implicitly and fail because they're invalid.

sudo rm -rf ~/.aws/credentials
sudo rm -rf ~/.aws/config
unset AWS_DEFAULT_REGION
unset AWS_ACCESS_KEY_ID
unset AWS_SECRET_ACCESS_KEY
unset AWS_SESSION_TOKEN

기본 설정부터 하고..
james:~/environment $ aws configure
AWS Access Key ID [None]: AKIATERIUY45ARCN7L4V
AWS Secret Access Key [None]: 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
Default region name [None]: 
Default output format [None]: 

없으면 IAM에서 그냥 다시 만들어 주세요.

james에 할당된 MFA 디바이스 확인해서 저장!!
arn:aws:iam::215907354426:mfa/james

Google OTP에서 확인해야 번호 확인해야 함 6자리 숫자

james:~/environment $ aws sts get-session-token --serial-number arn:aws:iam::215907354426:mfa/james --token-code 536610
{
    "Credentials": {
        "AccessKeyId": "ASIATERIUY45POFZEIIH",
        "SecretAccessKey": "qXsLqABkOhGjDSeZZ5AZ1HWY5s/uP5uJUL4q73M2",
        "SessionToken": "FwoGZXIvYXdzEKb//////////wEaDIkERKhIbW7jhivsSCKGAXbKI5Vu8YGXG+AbYvDL2jKojCXrAOZEj8dlyeaDDoT62N/caneCBJVnVONuwkORyuZaDihNmRXCdtYamZHHjhA/ubzY3SPvJUjhoLssQWigF5J+zUpPfzOe6T2sc0hEJYEj7B6LWPQxZ5pxKTZI/si38qzEnPgy25ZUgek5I56ELBK56Qp7KMHq4ZwGMih7X8AN6HS4a1Ff6pATNJt+JMGkCDoPBgJv5ywQfCR4nY69Grxt9Fth",
        "Expiration": "2022-12-14T00:51:13Z"
    }
}
받은 코드를 넣어서 환경설정 파일로 저장
export AWS_DEFAULT_REGION=us-east-1
export AWS_ACCESS_KEY_ID=ASIATERIUY45POFZEIIH
export AWS_SECRET_ACCESS_KEY=qXsLqABkOhGjDSeZZ5AZ1HWY5s/uP5uJUL4q73M2
export AWS_SESSION_TOKEN=FwoGZXIvYXdzEKb//////////wEaDIkERKhIbW7jhivsSCKGAXbKI5Vu8YGXG+AbYvDL2jKojCXrAOZEj8dlyeaDDoT62N/caneCBJVnVONuwkORyuZaDihNmRXCdtYamZHHjhA/ubzY3SPvJUjhoLssQWigF5J+zUpPfzOe6T2sc0hEJYEj7B6LWPQxZ5pxKTZI/si38qzEnPgy25ZUgek5I56ELBK56Qp7KMHq4ZwGMih7X8AN6HS4a1Ff6pATNJt+JMGkCDoPBgJv5ywQfCR4nY69Grxt9Fth


환경설정 파일 확인!!
echo $AWS_DEFAULT_REGION
echo $AWS_ACCESS_KEY_ID
echo $AWS_SECRET_ACCESS_KEY
echo $AWS_SESSION_TOKEN

다른 방법 확인
env | grep AWS 


이건 잘 되는데..
james:~/environment $ curl -s https://d2s8p88vqu9w66.cloudfront.net/releases/greengrass-nucleus-latest.zip > greengrass-nucleus-latest.zip && unzip greengrass-nucleus-latest.zip -d GreengrassInstaller
Archive:  greengrass-nucleus-latest.zip
  inflating: GreengrassInstaller/LICENSE  
  inflating: GreengrassInstaller/NOTICE  
  inflating: GreengrassInstaller/README.md  
  inflating: GreengrassInstaller/THIRD-PARTY-LICENSES  
  inflating: GreengrassInstaller/bin/greengrass.exe  
  inflating: GreengrassInstaller/bin/greengrass.service.template  
  inflating: GreengrassInstaller/bin/greengrass.xml.template  
  inflating: GreengrassInstaller/bin/loader  
  inflating: GreengrassInstaller/bin/loader.cmd  
  inflating: GreengrassInstaller/conf/recipe.yaml  
  inflating: GreengrassInstaller/lib/Greengrass.jar  

이제 이거 실행하면 에러나요..
sudo -E java -Droot="/greengrass/v2" -Dlog.store=FILE -jar ./GreengrassInstaller/lib/Greengrass.jar --aws-region us-east-1 --thing-name GreengrassQuickStartCore-184dcf24fe1 --thing-group-name GreengrassQuickStartGroup --component-default-user ggc_user:ggc_group --provision true --setup-system-service true --deploy-dev-tools true
에러나는 이유는 설정을 몇 가지 빠뜨렸기 때문이죠.
단계 3에서 1.2.3.4 특히 2번 항목을 잘 읽어보고
james 권한을 다 줘야 하고..
사용자 및 그룹 권한도 생성해서 만들어야 해요.


....
TES role alias "GreengrassV2TokenExchangeRoleAlias" does not exist, creating new alias...
Error while trying to setup Greengrass Nucleus
software.amazon.awssdk.services.iam.model.IamException: The security token included in the request is invalid (Service: Iam, Status Code: 403, Request ID: d71276c1-1b55-4aa6-b747-e590cfc53d45)
        at software.amazon.awssdk.core.internal.http.CombinedResponseHandler.handleErrorResponse(CombinedResponseHandler.java:125)
        at software.amazon.awssdk.core.internal.http.CombinedResponseHandler.handleResponse(CombinedResponseHandler.java:82)
        at com.aws.greengrass.easysetup.GreengrassSetup.performSetup(GreengrassSetup.java:324)
        at com.aws.greengrass.easysetup.GreengrassSetup.main(GreengrassSetup.java:274)


참고: https://docs.aws.amazon.com/greengrass/v2/developerguide/configure-greengrass-core-v2.html#configure-system-service
참고: https://nasanx2001.tistory.com/entry/%EC%9A%B0%EB%B6%84%ED%88%AC-1804-%EC%9E%90%EB%8F%99%EC%8B%A4%ED%96%89-%EC%84%9C%EB%B9%84%EC%8A%A4%EB%93%B1%EB%A1%9D

james:~/environment $ sudo vi /etc/systemd/system/greengrass.service
---------
[Unit]
Description=Greengrass Core

[Service]
Type=simple
PIDFile=/greengrass/v2/alts/loader.pid
RemainAfterExit=no
Restart=on-failure
RestartSec=10
ExecStart=/bin/sh /greengrass/v2/alts/current/distro/bin/loader

[Install]
WantedBy=multi-user.target
------------

To check the status of the service (systemd)
# sudo systemctl status greengrass.service

To enable the nucleus to start when the device boots.
# sudo systemctl enable greengrass.service

To stop the nucleus from starting when the device boots.
# sudo systemctl disable greengrass.service

To start the AWS IoT Greengrass Core software.
# sudo systemctl start greengrass.service

To stop the AWS IoT Greengrass Core software.
# sudo systemctl stop greengrass.service


사용자 생성
greengrass
AKIATERIUY45AT42WBLW
0cAhpkUJ0ckzCGTX2kESbJWEOw4fnJhKic9TcP9w
us-east-1
json


/greengrass/v2/bin/greengrass-cli -V
이거 안 나오더라.. 
그래서 찾아봤더니.. cli 설정을 먼저 해 줘야 하더라.

greengrass CLI 설정하기
https://docs.aws.amazon.com/greengrass/v2/developerguide/install-gg-cli.html

중간쯤
Deploy the Greengrass CLI component
여기 부분 아래를 실행하세요.



215907354426
aws s3 mb s3://mybucket-215907354426
mybucket-215907354426

aws s3 cp --recursive /home/ubuntu/GGv2Dev/ s3://mybucket-215907354426/
