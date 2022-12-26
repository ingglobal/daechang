greengrass 참고메뉴얼


재생성 하려면 지우고 다시 해야 합니다.
get-session-token was failing for me because I still had the environment variables AWS_SESSION_TOKEN and AWS_SECURITY_TOKEN set.
These should be unset first or AWS will try to use them implicitly and fail because they're invalid.

unset AWS_DEFAULT_REGION
unset AWS_ACCESS_KEY_ID
unset AWS_SECRET_ACCESS_KEY
unset AWS_SESSION_TOKEN

기본 설정부터 하고..
$ aws configure
AWS Access Key ID [None]: AKIATERIUY45ARCN7L4V
AWS Secret Access Key [None]: 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
Default region name [None]: us-east-1
Default output format [None]: 

없으면 IAM에서 그냥 다시 만들어 주세요.

james에 할당된 MFA 디바이스 확인해서 저장!!
arn:aws:iam::215907354426:mfa/james

Google OTP에서 확인해야 번호 확인해야 함 6자리 숫자

james:~/environment $ aws sts get-session-token --serial-number arn:aws:iam::215907354426:mfa/james --token-code 588901
{
    "Credentials": {
        "AccessKeyId": "ASIATERIUY45MYUUJIX5",
        "SecretAccessKey": "YoEVcvVLs5SQzOlHQy4MxmnBZTLONKTkaZ/mqHj/",
        "SessionToken": "FwoGZXIvYXdzEAYaDNICNbzb5DP2YwQpFSKGAZKbIztYFSrbRsCJafsJNtlM970kpLKrPTdvI6LPrleg81vh6MnZRFxJFCHYFmM24aLVY7+b0szg1wFCI0ktJD/izZ6V/W9M4LUoD8ZxTnhxtaFRNk7hLCBc6Y4sDmTDakyLcC3g8bEcj0tc4jp+XxTs+wGeMDqVinCG+CDeU2r2piUkBsJnKJDz9pwGMig7qUMZIUzwh5ITWSGA0Yc244BXwHW5TmWP43gUhd/+SZffAsCnH5g4",
        "Expiration": "2022-12-18T00:44:00Z"
    }
}
받은 코드를 넣어서 환경설정 파일로 저장
export AWS_DEFAULT_REGION=us-east-1
export AWS_ACCESS_KEY_ID=ASIATERIUY45MYUUJIX5
export AWS_SECRET_ACCESS_KEY=YoEVcvVLs5SQzOlHQy4MxmnBZTLONKTkaZ/mqHj/
export AWS_SESSION_TOKEN=FwoGZXIvYXdzEAYaDNICNbzb5DP2YwQpFSKGAZKbIztYFSrbRsCJafsJNtlM970kpLKrPTdvI6LPrleg81vh6MnZRFxJFCHYFmM24aLVY7+b0szg1wFCI0ktJD/izZ6V/W9M4LUoD8ZxTnhxtaFRNk7hLCBc6Y4sDmTDakyLcC3g8bEcj0tc4jp+XxTs+wGeMDqVinCG+CDeU2r2piUkBsJnKJDz9pwGMig7qUMZIUzwh5ITWSGA0Yc244BXwHW5TmWP43gUhd/+SZffAsCnH5g4


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



  james:~/environment $ sudo -E java -Droot="/greengrass/v2" -Dlog.store=FILE -jar ./GreengrassInstaller/lib/Greengrass.jar --aws-region us-east-1 --thing-name GreengrassQuickStartCore-185201d7e58 --thing-group-name GreengrassQuickStartGroup --component-default-user ggc_user:ggc_group --provision true --setup-system-service true --deploy-dev-tools true
Provisioning AWS IoT resources for the device with IoT Thing Name: [GreengrassQuickStartCore-185201d7e58]...
Creating new IoT policy "GreengrassV2IoTThingPolicy"
Creating keys and certificate...
Attaching policy to certificate...
Creating IoT Thing "GreengrassQuickStartCore-185201d7e58"...
Attaching certificate to IoT thing...
Successfully provisioned AWS IoT resources for the device with IoT Thing Name: [GreengrassQuickStartCore-185201d7e58]!
Adding IoT Thing [GreengrassQuickStartCore-185201d7e58] into Thing Group: [GreengrassQuickStartGroup]...
Successfully added Thing into Thing Group: [GreengrassQuickStartGroup]
Setting up resources for aws.greengrass.TokenExchangeService ... 
TES role alias "GreengrassV2TokenExchangeRoleAlias" does not exist, creating new alias...
IoT role policy "GreengrassTESCertificatePolicyGreengrassV2TokenExchangeRoleAlias" for TES Role alias not exist, creating policy...
Attaching TES role policy to IoT thing...
No managed IAM policy found, looking for user defined policy...
No IAM policy found, will attempt creating one...
IAM role policy for TES "GreengrassV2TokenExchangeRoleAccess" created. This policy DOES NOT have S3 access, please modify it with your private components' artifact buckets/objects as needed when you create and deploy private components 
Attaching IAM role policy for TES to IAM role for TES...
Configuring Nucleus with provisioned resource details...
Downloading Root CA from "https://www.amazontrust.com/repository/AmazonRootCA1.pem"
Created device configuration
Successfully configured Nucleus with provisioned resource details!
Creating a deployment for Greengrass first party components to the thing group
Configured Nucleus to deploy aws.greengrass.Cli component
Creating user ggc_user 
ggc_user created 
Creating group ggc_group 
ggc_group created 
Added ggc_user to ggc_group 
Successfully set up Nucleus as a system service
james:~/environment $ 

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


// 참고 install with manual provisioning... 
https://docs.aws.amazon.com/greengrass/v2/developerguide/manual-installation.html

AWS IoT thing:::::::::::
GreengrassQuickStartCore-1850f814abc
aws iot create-thing --thing-name GreengrassQuickStartCore-1850f814abc

AWS IoT thing group:::::::::::
GreengrassQuickStartGroup
aws iot create-thing-group --thing-group-name GreengrassQuickStartGroup

aws iot add-thing-to-thing-group --thing-name GreengrassQuickStartCore-1850f814abc --thing-group-name GreengrassQuickStartGroup

mkdir greengrass-v2-certs

aws iot create-keys-and-certificate --set-as-active --certificate-pem-outfile greengrass-v2-certs/device.pem.crt --public-key-outfile greengrass-v2-certs/public.pem.key --private-key-outfile greengrass-v2-certs/private.pem.key
james:~/.aws $ aws iot create-keys-and-certificate --set-as-active --certificate-pem-outfile greengrass-v2-certs/device.pem.crt --public-key-outfile greengrass-v2-certs/public.pem.key --private-key-outfile greengrass-v2-certs/private.pem.key
{
    "certificateArn": "arn:aws:iot:us-east-1:215907354426:cert/cf3e2162e453863957e7faac1d6818447cd4930d2f0161a475fdb0f7e3a2419d",
    "certificateId": "cf3e2162e453863957e7faac1d6818447cd4930d2f0161a475fdb0f7e3a2419d",
    "certificatePem": "-----BEGIN CERTIFICATE-----\nMIIDWjCCAkKgAwIBAgIVAP+/Z+Ia0JAFLWSxhGVY4oKm5KrqMA0GCSqGSIb3DQEB\nCwUAME0xSzBJBgNVBAsMQkFtYXpvbiBXZWIgU2VydmljZXMgTz1BbWF6b24uY29t\nIEluYy4gTD1TZWF0dGxlIFNUPVdhc2hpbmd0b24gQz1VUzAeFw0yMjEyMTQwODM5\nMDVaFw00OTEyMzEyMzU5NTlaMB4xHDAaBgNVBAMME0FXUyBJb1QgQ2VydGlmaWNh\ndGUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDi/WVHdjOEc0lJ300Q\nrz4jC1IsM55oFYBJXVXF4tL4elTdr8cCy8E/hMBeDT9alOvhm7VRysWMMP8OOSjg\n6+hLeoV4e1v0I9OCtHI1VMMUKsSA8+Ltv+T+eJcWKvp4jgTSNaPSQyLOX24VBHbr\n7F9yNMIkEKjajOUL5AkDMRqnMU5phYeiChVmAEms9hsHH01O1mKefn4KsDteHdvi\nupY06kgiWufNVse61VC9nqLXjFY60CCnwyniRWUmdvAY0FHxm2t0drkOuil65hoT\n4hTJ3ZCg+ACt1y2UfelnySdeFXhs4x8iWDLsV1qwsfkEsA2u1+i0IZwgKBkaD2Sz\nPo6nAgMBAAGjYDBeMB8GA1UdIwQYMBaAFEiGFZhporEvDGstl6tg1ScqmfVqMB0G\nA1UdDgQWBBTG1pLRdokyU3yRYRSW5IgetBOrKjAMBgNVHRMBAf8EAjAAMA4GA1Ud\nDwEB/wQEAwIHgDANBgkqhkiG9w0BAQsFAAOCAQEAn3eBkU5D5Wrf28A/tPxhQBN1\n3UHNloxGTcnqn8/QFF6PseoxJNtsgK4kNqonzPceADihbsjbD3dZxFMn+rkM6u44\nBczSPgGfEkjRiuPI+2pdaOtlFH3ioGXYLwGURllw+Tvqrs3zYBLB2RWDoNRN2N9d\nLdSsMyyyK3mSid4PVBx+3QHPg65Vrfot0CUuwxQO6N25epAlvaMzRXVPMFjJtrPw\nJ7l3u2jpnUT0orUXrttYO6Ip9jCxRw91Xx1ymyoMvYpYr/jc1mu5of8NlIaUbCQd\ngdXY+vhDRB0mGPjMehCY6GUQS08T2bE3z9mo0eoUW+2DMMo/Vw1FhJGey1kSdQ==\n-----END CERTIFICATE-----\n",
    "keyPair": {
        "PublicKey": "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4v1lR3YzhHNJSd9NEK8+\nIwtSLDOeaBWASV1VxeLS+HpU3a/HAsvBP4TAXg0/WpTr4Zu1UcrFjDD/Djko4Ovo\nS3qFeHtb9CPTgrRyNVTDFCrEgPPi7b/k/niXFir6eI4E0jWj0kMizl9uFQR26+xf\ncjTCJBCo2ozlC+QJAzEapzFOaYWHogoVZgBJrPYbBx9NTtZinn5+CrA7Xh3b4rqW\nNOpIIlrnzVbHutVQvZ6i14xWOtAgp8Mp4kVlJnbwGNBR8ZtrdHa5DropeuYaE+IU\nyd2QoPgArdctlH3pZ8knXhV4bOMfIlgy7FdasLH5BLANrtfotCGcICgZGg9ksz6O\npwIDAQAB\n-----END PUBLIC KEY-----\n",
        "PrivateKey": "-----BEGIN RSA PRIVATE KEY-----\nMIIEowIBAAKCAQEA4v1lR3YzhHNJSd9NEK8+IwtSLDOeaBWASV1VxeLS+HpU3a/H\nAsvBP4TAXg0/WpTr4Zu1UcrFjDD/Djko4OvoS3qFeHtb9CPTgrRyNVTDFCrEgPPi\n7b/k/niXFir6eI4E0jWj0kMizl9uFQR26+xfcjTCJBCo2ozlC+QJAzEapzFOaYWH\nogoVZgBJrPYbBx9NTtZinn5+CrA7Xh3b4rqWNOpIIlrnzVbHutVQvZ6i14xWOtAg\np8Mp4kVlJnbwGNBR8ZtrdHa5DropeuYaE+IUyd2QoPgArdctlH3pZ8knXhV4bOMf\nIlgy7FdasLH5BLANrtfotCGcICgZGg9ksz6OpwIDAQABAoIBAEFeCaGHt5RIAu4E\nIVRRswoyg2p5Pv/oWTZHa6D+DhVCXVgt+5ihhrg9CYzMMddrFXa1+YRhaXxSy9CT\nw2LqbM33raIhnDx1aL62KkGTdE5FdqtQEKS440ApCBF5NQIsm9TRX6j4bniR4Miq\nrXiJbH8eFF6Aca/mbTt8YuAJbwyASp9ufyZrUBDOxjTA4TI0YN3T8PulCGrKPTht\ni73ijA43BvNuzg35TbiIBQYF1dG1AJd4bn5z1vZHHc5I8OyMueAi9bsnh1TWi9FM\nEh5fDW7rKo40eT6ggr0Gh3WZFauCCmJxYD/UobVauhbYw7ynxa9w+SaQYijR1yDP\nB5IpCPECgYEA9eZqXMTZg6KrmbPs45pb3OL/mBf0Q1OeNL1WCpqmvpI3mjY5uwPf\ncWIWxFg4M9oD/fRuR2CFOWpRWQMwqLYm/4ApUpBkb+eXnr4JEhB6VyAGY5vW9VAI\nXpUmgd+OFvO3Fe/1FymrNxZFXy6YYoyukDOWuH8lgIgpm6gaZPAJz68CgYEA7FAk\nqwxcOfkPtmUoAh2l+fYCE9iy8t39vz5Od/DeTfzb7SO7sYRkQaLZkxwgnrdJBbq8\npiRq8LKTzqUoGGfHqOP/tfplE3JWXMiqeKjEE3jPksnP0KGlgAuubT2zZ0YIoeGY\nF3INF+oju2+dHQV9GPQCVi4L0a3HSKhOp38otokCgYBKHWXHiklpJJmGHTX0L+0q\nOX0CMY4c0NIpYTvSvGQvolCB+YAS/wrU2NS130UpU7fKTmcCunPNJ19Xbd+3qhc9\nATrzOLQdCnenj+2Z9T96jRt/4FO84p9zKo9Nq7ze7Bwtz4JWted2Fg9YremeInrm\n2bW+7Au4ntGzqcoyzCqY3wKBgHt1d2LBp2FGRLY27QsF8FPB+d3ihcMqxUnnxFkX\nRx0ZcNDYrVOV5kcATIl11Xj2KrlKkQp/1jsAIQaB9IujlIUg8NRjB/F797CryZwW\nF7JQmKmItAC8otldwPgjlxnqVdI1KR9+n2aSFa70JfImju+9c4cQsW4PSyEjhUwu\nu9d5AoGBAJXe53CWMnU5zZ56J/sOQuHH9vmIzlJc3HZJE+XFHIo87MA8gvcvk1hT\nkId5K71Z+MDf46OfQnfjbwXS0cbXj6PkpwmB0XL8En1mP1mzXvwlXOdVpTpBKcPc\nWVIzFxdjfq4cTbAE/ZIN4z92Cxr28uR4YbUSk29jd9CD5xvzVXPT\n-----END RSA PRIVATE KEY-----\n"
    }
}
james:~/.aws $ 

Create the certificate from a private key in an HSM
이걸로 안 하고 그냥 위에거 한개만 실행했어요.


thing name: GreengrassQuickStartCore-1850f814abc
arn: arn:aws:iot:us-east-1:215907354426:cert/cf3e2162e453863957e7faac1d6818447cd4930d2f0161a475fdb0f7e3a2419d
aws iot attach-thing-principal --thing-name GreengrassQuickStartCore-1850f814abc --principal arn:aws:iot:us-east-1:215907354426:cert/cf3e2162e453863957e7faac1d6818447cd4930d2f0161a475fdb0f7e3a2419d

nano greengrass-v2-iot-policy.json



====================================================================
AWS IoT Greengrass with Fleet Provisioning Workshop
https://catalog.us-east-1.prod.workshops.aws/workshops/81ed110e-e31e-4c7d-aaed-e6263bc53e51
====================================================================
james:~/environment $ ls -al
total 24
drwxr-xr-x  3 ec2-user ec2-user  326 Dec 17 01:51 .
drwx------ 11 ec2-user ec2-user 4096 Dec 17 01:51 ..
-rw-r--r--  1 ec2-user ec2-user 1220 Dec 17 01:51 13a67165488da91ebe4e0941cf10a496037cec935c4aba70df34bf02d9d98b68-certificate.pem.crt
-rw-r--r--  1 ec2-user ec2-user 1679 Dec 17 01:51 13a67165488da91ebe4e0941cf10a496037cec935c4aba70df34bf02d9d98b68-private.pem.key
-rw-r--r--  1 ec2-user ec2-user  451 Dec 17 01:51 13a67165488da91ebe4e0941cf10a496037cec935c4aba70df34bf02d9d98b68-public.pem.key
-rw-r--r--  1 ec2-user ec2-user 1187 Dec 17 01:51 AmazonRootCA1.pem
drwxr-xr-x  4 ec2-user ec2-user  177 Dec 17 01:43 .c9
-rw-r--r--  1 ec2-user ec2-user  569 Dec 13 04:24 README.md
james:~/environment $ sudo mkdir -p /greengrass/v2/claim-certs

sudo mv 13a67165488da91ebe4e0941cf10a496037cec935c4aba70df34bf02d9d98b68-certificate.pem.crt /greengrass/v2/claim-certs/claim.crt
sudo mv 13a67165488da91ebe4e0941cf10a496037cec935c4aba70df34bf02d9d98b68-private.pem.key /greengrass/v2/claim-certs/claim-private.key

// Root CA 인증서를 다운로드합니다. 또는, 앞서 다운로드한 Root CA 인증서를 업로드하여 /greengrass/v2/로 이동시키고 root 권한으로 변경합니다.
// 이거 좀 이상함!!
sudo curl -o /greengrass/v2/AmazonRootCA1.pem https://www.amazontrust.com/repository/AmazonRootCA1.pem

sudo chown -R root:root /greengrass/v2/claim-certs/*

// Template Sample
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "iot:Connect",
      "Resource": "*"
    },
    {
      "Effect": "Allow",
      "Action": [
        "iot:Publish",
        "iot:Receive"
      ],
      "Resource": [
        "arn:aws:iot:[region]:[accountID]:topic/$aws/certificates/create/*",
        "arn:aws:iot:[region]:[accountID]:topic/$aws/provisioning-templates/[ProvisioningTemplate]/provision/*"
      ]
    },
    {
      "Effect": "Allow",
      "Action": "iot:Subscribe",
      "Resource": [
        "arn:aws:iot:[region]:[accountID]:topicfilter/$aws/certificates/create/*",
        "arn:aws:iot:[region]:[accountID]:topicfilter/$aws/provisioning-templates/[ProvisioningTemplate]/provision/*"
      ]
    }
  ]
}

// example
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "iot:Connect",
      "Resource": "*"
    },
    {
      "Effect": "Allow",
      "Action": [
        "iot:Publish",
        "iot:Receive"
      ],
      "Resource": [
        "arn:aws:iot:us-east-1:215907354426:topic/$aws/certificates/create/*",
        "arn:aws:iot:us-east-1:215907354426:topic/$aws/provisioning-templates/FleetProvisioningTemplate/provision/*"
      ]
    },
    {
      "Effect": "Allow",
      "Action": "iot:Subscribe",
      "Resource": [
        "arn:aws:iot:us-east-1:215907354426:topicfilter/$aws/certificates/create/*",
        "arn:aws:iot:us-east-1:215907354426:topicfilter/$aws/provisioning-templates/FleetProvisioningTemplate/provision/*"
      ]
    }
  ]
}


{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "iot:Publish",
        "iot:Subscribe",
        "iot:Receive",
        "iot:Connect",
        "greengrass:*"
      ],
      "Resource": [
        "*"
      ]
    },
    {
      "Effect": "Allow",
      "Action": "iot:AssumeRoleWithCertificate",
      "Resource": "arn:aws:iot:us-east-1:215907354426:rolealias/GreengrassCoreTokenExchangeRoleAlias"
    }
  ]
}


// Registration code
// This registration code uniquely identifies your verification certificate for the registration process. 
When prompted by the procedure to create a verification certificate, copy this value to use in your certificate signing request (CSR).
e5a20327297a235f6bb6b4b6c787482b8cdd4aa574ed4c0cea5c84af17b2cd76

기본 설정부터 하고..
james:~/environment $ aws configure
AWS Access Key ID [None]: AKIATERIUY45ARCN7L4V
AWS Secret Access Key [None]: 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
Default region name [None]: us-east-1
Default output format [None]: 

없으면 IAM에서 그냥 다시 만들어 주세요.

james에 할당된 MFA 디바이스 확인해서 저장!!
arn:aws:iam::215907354426:mfa/james

Google OTP에서 확인해야 번호 확인해야 함 6자리 숫자

james:~/environment $ aws sts get-session-token --serial-number arn:aws:iam::215907354426:mfa/james --token-code 272918
{
    "Credentials": {
        "SecretAccessKey": "mCM8FBQPVTi5NPK/KoiEsbw9eeGRQI+lFNkYZm+K", 
        "SessionToken": "FwoGZXIvYXdzEP///////////wEaDNdOPcBbqeHHeg65nCKGAROXb+F7TJIBMaZJE4rxXA4kLTQce/ubMm0/6recW9fHOWj33BITSYf5PtwGmdmP8JZ944eSd7o2pWpgspGUXPTVDMV8UYQjqCFf2OQWn8mbFFmGJeGhds+yttW9KsnQ1uhUXe7ODs1TXoOAMy1KXpp/ilZ590XObra9mgBYfc8xszdiiGSRKPWm9ZwGMijhzCLzI6vXtkqEvutDubVUQY5MzR2+mCgcEQGzvCbktAEJCmn8W81Q", 
        "Expiration": "2022-12-17T17:28:21Z", 
        "AccessKeyId": "ASIATERIUY45JVDBOSUV"
    }
}
받은 코드를 넣어서 환경설정 파일로 저장
export AWS_DEFAULT_REGION=us-east-1
export AWS_ACCESS_KEY_ID=ASIATERIUY45JVDBOSUV
export AWS_SECRET_ACCESS_KEY=mCM8FBQPVTi5NPK/KoiEsbw9eeGRQI+lFNkYZm+K
export AWS_SESSION_TOKEN=FwoGZXIvYXdzEP///////////wEaDNdOPcBbqeHHeg65nCKGAROXb+F7TJIBMaZJE4rxXA4kLTQce/ubMm0/6recW9fHOWj33BITSYf5PtwGmdmP8JZ944eSd7o2pWpgspGUXPTVDMV8UYQjqCFf2OQWn8mbFFmGJeGhds+yttW9KsnQ1uhUXe7ODs1TXoOAMy1KXpp/ilZ590XObra9mgBYfc8xszdiiGSRKPWm9ZwGMijhzCLzI6vXtkqEvutDubVUQY5MzR2+mCgcEQGzvCbktAEJCmn8W81Q



james:~/environment $ aws iam create-role --role-name GreengrassV2TokenExchangeRole --assume-role-policy-document file://device-role-trust-policy.json
{
    "Role": {
        "AssumeRolePolicyDocument": {
            "Version": "2012-10-17", 
            "Statement": [
                {
                    "Action": "sts:AssumeRole", 
                    "Effect": "Allow", 
                    "Principal": {
                        "Service": "credentials.iot.amazonaws.com"
                    }
                }
            ]
        }, 
        "RoleId": "AROATERIUY45JBVVUUBVH", 
        "CreateDate": "2022-12-17T05:32:03Z", 
        "RoleName": "GreengrassV2TokenExchangeRole", 
        "Path": "/", 
        "Arn": "arn:aws:iam::215907354426:role/GreengrassV2TokenExchangeRole"
    }
}
james:~/environment $ 


ames:~/environment $ aws iam create-policy --policy-name GreengrassV2TokenExchangeRoleAccess --policy-document file://device-role-access-policy.json
{
    "Policy": {
        "PolicyName": "GreengrassV2TokenExchangeRoleAccess", 
        "PermissionsBoundaryUsageCount": 0, 
        "CreateDate": "2022-12-17T05:34:06Z", 
        "AttachmentCount": 0, 
        "IsAttachable": true, 
        "PolicyId": "ANPATERIUY45HADZQRUQH", 
        "DefaultVersionId": "v1", 
        "Path": "/", 
        "Arn": "arn:aws:iam::215907354426:policy/GreengrassV2TokenExchangeRoleAccess", 
        "UpdateDate": "2022-12-17T05:34:06Z"
    }
}
james:~/environment $ 


aws iam attach-role-policy --role-name GreengrassV2TokenExchangeRole --policy-arn arn:aws:iam::215907354426:policy/GreengrassV2TokenExchangeRoleAccess

aws iot create-role-alias --role-alias GreengrassCoreTokenExchangeRoleAlias --role-arn arn:aws:iam::215907354426:role/GreengrassV2TokenExchangeRole
james:~/environment $ aws iot create-role-alias --role-alias GreengrassCoreTokenExchangeRoleAlias --role-arn arn:aws:iam::215907354426:role/GreengrassV2TokenExchangeRole
{
    "roleAlias": "GreengrassCoreTokenExchangeRoleAlias", 
    "roleAliasArn": "arn:aws:iot:us-east-1:215907354426:rolealias/GreengrassCoreTokenExchangeRoleAlias"
}


### AWS IoT Greengrass 디바이스 프로비저닝

cd ~/environment/
curl -s https://d2s8p88vqu9w66.cloudfront.net/releases/greengrass-nucleus-latest.zip > greengrass-nucleus-latest.zip && unzip greengrass-nucleus-latest.zip -d GreengrassInstaller

curl -s https://d2s8p88vqu9w66.cloudfront.net/releases/aws-greengrass-FleetProvisioningByClaim/fleetprovisioningbyclaim-latest.jar > GreengrassInstaller/aws.greengrass.FleetProvisioningByClaim.jar

touch ~/environment/GreengrassInstaller/config.yaml

james:~/environment $ aws iot describe-endpoint --endpoint-type iot:Data-ATS
{
    "endpointAddress": "a2fp0dsf5tehz0-ats.iot.us-east-1.amazonaws.com"
}

james:~/environment $ aws iot describe-endpoint --endpoint-type iot:CredentialProvider
{
    "endpointAddress": "cul3b38l98z3n.credentials.iot.us-east-1.amazonaws.com"
}

james:~/environment $ aws iot describe-endpoint --endpoint-type iot:Data-ATS
{
    "endpointAddress": "a2fp0dsf5tehz0-ats.iot.us-east-1.amazonaws.com"
}
james:~/environment $ aws iot describe-endpoint --endpoint-type iot:CredentialProvider
{
    "endpointAddress": "cul3b38l98z3n.credentials.iot.us-east-1.amazonaws.com"
}

---
services:
  aws.greengrass.Nucleus:
    version: "2.7.0"
  aws.greengrass.FleetProvisioningByClaim:
    configuration:
      rootPath: "/greengrass/v2"
      awsRegion: "us-east-1"
      iotDataEndpoint: "a2fp0dsf5tehz0-ats.iot.us-east-1.amazonaws.com"
      iotCredentialEndpoint: "cul3b38l98z3n.credentials.iot.us-east-1.amazonaws.com"
      iotRoleAlias: "GreengrassCoreTokenExchangeRoleAlias"
      provisioningTemplate: "FleetProvisioningTemplate"
      claimCertificatePath: "/greengrass/v2/claim-certs/claim.crt"
      claimCertificatePrivateKeyPath: "/greengrass/v2/claim-certs/claim-private.key"
      rootCaPath: "/greengrass/v2/AmazonRootCA1.pem"
      templateParameters:
        ThingName: "MyGreengrassCore2"
        SerialNumber: "123456222"
---
services:
  aws.greengrass.Nucleus:
    version: "2.7.0"
  aws.greengrass.FleetProvisioningByClaim:
    configuration:
      rootPath: "/greengrass/v2"
      awsRegion: "us-east-1"
      iotDataEndpoint: "a2fp0dsf5tehz0-ats.iot.us-east-1.amazonaws.com"
      iotCredentialEndpoint: "cul3b38l98z3n.credentials.iot.us-east-1.amazonaws.com"
      iotRoleAlias: "GreengrassCoreTokenExchangeRoleAlias"
      provisioningTemplate: "FleetProvisioningTemplate"
      claimCertificatePath: "/greengrass/v2/claim-certs/claim.crt"
      claimCertificatePrivateKeyPath: "/greengrass/v2/claim-certs/claim-private.key"
      rootCaPath: "/greengrass/v2/AmazonRootCA1.pem"
      templateParameters:
        ThingName: "MyGreengrassCore1"
        SerialNumber: "123456789"


sudo -E java -Droot="/greengrass/v2" -Dlog.store=FILE \
-jar ./GreengrassInstaller/lib/Greengrass.jar \
--trusted-plugin ./GreengrassInstaller/aws.greengrass.FleetProvisioningByClaim.jar \
--init-config ./GreengrassInstaller/config.yaml \
--component-default-user ggc_user:ggc_group \
--setup-system-service true



### AWS IoT Greengrass 디바이스 프로비저닝 자동화 확인

---
services:
  aws.greengrass.Nucleus:
    version: "2.7.0"
  aws.greengrass.FleetProvisioningByClaim:
    configuration:
      rootPath: "/greengrass/v2"
      awsRegion: "us-east-1"
      iotDataEndpoint: "a2fp0dsf5tehz0-ats.iot.us-east-1.amazonaws.com"
      iotCredentialEndpoint: "cul3b38l98z3n.credentials.iot.us-east-1.amazonaws.com"
      iotRoleAlias: "GreengrassCoreTokenExchangeRoleAlias"
      provisioningTemplate: "FleetProvisioningTemplate"
      claimCertificatePath: "/greengrass/v2/claim-certs/claim.crt"
      claimCertificatePrivateKeyPath: "/greengrass/v2/claim-certs/claim-private.key"
      rootCaPath: "/greengrass/v2/AmazonRootCA1.pem"
      templateParameters:
        ThingName: "MyGreengrassCore2"
        SerialNumber: "122222222"
---

aws greengrassv2 delete-core-device --core-device-thing-name MyGreengrassCore1
aws greengrassv2 delete-core-device --core-device-thing-name MyGreengrassCore2



sudo mv 112ead30593e7532ad330bca8e8d18dd0c6039b80566f1bc6579a3bd281f13c2-certificate.pem.crt /greengrass/v2/claim-certs/claim.crt
sudo mv 112ead30593e7532ad330bca8e8d18dd0c6039b80566f1bc6579a3bd281f13c2-private.pem.key /greengrass/v2/claim-certs/claim-private.key


total 20
drwxr-xr-x  3 ec2-user ec2-user  326 Dec 17 05:10 .
drwx------ 11 ec2-user ec2-user  335 Dec 17 05:08 ..
-rw-r--r--  1 ec2-user ec2-user 1224 Dec 17 05:10 112ead30593e7532ad330bca8e8d18dd0c6039b80566f1bc6579a3bd281f13c2-certificate.pem.crt
-rw-r--r--  1 ec2-user ec2-user 1679 Dec 17 05:10 112ead30593e7532ad330bca8e8d18dd0c6039b80566f1bc6579a3bd281f13c2-private.pem.key
-rw-r--r--  1 ec2-user ec2-user  451 Dec 17 05:10 112ead30593e7532ad330bca8e8d18dd0c6039b80566f1bc6579a3bd281f13c2-public.pem.key
-rw-r--r--  1 ec2-user ec2-user 1187 Dec 17 05:10 AmazonRootCA1.pem


james:~/environment $ sudo /greengrass/v2/bin/greengrass-cli deployment create --recipeDir ~/GGv2Dev/recipes   --artifactDir ~/GGv2Dev/artifacts --merge "com.example.HelloMqtt=1.0.0"

james:~/environment $ aws sts get-caller-identity --query Account --output text
215907354426

aws s3 mb s3://mybucket-215907354426

aws s3 cp --recursive /home/ubuntu/GGv2Dev/ s3://mybucket-215907354426/

cd /home/ubuntu/GGv2Dev/recipes && aws greengrassv2 create-component-version  --inline-recipe fileb://com.example.HelloMqtt-1.0.0.json --region $AWS_DEFAULT_REGION
james:~/environment $ cd /home/ubuntu/GGv2Dev/recipes && aws greengrassv2 create-component-version  --inline-recipe fileb://com.example.HelloMqtt-1.0.0.json --region $AWS_DEFAULT_REGION
{
    "arn": "arn:aws:greengrass:us-east-1:215907354426:components:com.example.HelloMqtt:versions:1.0.0",
    "componentName": "com.example.HelloMqtt",
    "componentVersion": "1.0.0",
    "creationTimestamp": 1671268641.502,
    "status": {
        "componentState": "REQUESTED",
        "message": "NONE",
        "errors": {},
        "vendorGuidance": "ACTIVE",
        "vendorGuidanceMessage": "NONE"
    }
}
james:~/GGv2Dev/recipes $ 


james:~/GGv2Dev/recipes $ aws sts get-caller-identity --query Account --output text
215907354426

cd && git clone https://github.com/aws-samples/aiot-e2e-sagemaker-greengrass-v2-nvidia-jetson


==--------Creating Component---------==
{
    "arn": "arn:aws:greengrass:us-east-1:215907354426:components:com.example.ImgClassification:versions:1.0.0",
    "componentName": "com.example.ImgClassification",
    "componentVersion": "1.0.0",
    "creationTimestamp": 1671269504.969,
    "status": {
        "componentState": "REQUESTED",
        "message": "NONE",
        "errors": {},
        "vendorGuidance": "ACTIVE",
        "vendorGuidanceMessage": "NONE"
    }
}
.
james:~/aiot-e2e-sagemaker-greengrass-v2-nvidia-jetson/ggv2-deploy-cloud (main) $ 


james:~/environment $ aws sts get-caller-identity --query Account --output text
215907354426
$ aws s3 mb s3://mybucket-215907354426
make_bucket: 
mybucket-215907354426

aws s3 mb s3://mybucket-[Your Account Number]

aws s3 cp --recursive /home/ubuntu/GGv2Dev/ s3://[YOUR BUCKET NAME]/

cd /home/ubuntu/GGv2Dev/recipes && aws greengrassv2 create-component-version  --inline-recipe fileb://com.example.HelloMqtt-1.0.0.json --region $AWS_DEFAULT_REGION
james:~/environment $ cd /home/ubuntu/GGv2Dev/recipes && aws greengrassv2 create-component-version  --inline-recipe fileb://com.example.HelloMqtt-1.0.0.json --region $AWS_DEFAULT_REGION
{
    "arn": "arn:aws:greengrass:us-east-1:215907354426:components:com.example.HelloMqtt:versions:1.0.0",
    "componentName": "com.example.HelloMqtt",
    "componentVersion": "1.0.0",
    "creationTimestamp": 1671284167.23,
    "status": {
        "componentState": "REQUESTED",
        "message": "NONE",
        "errors": {},
        "vendorGuidance": "ACTIVE",
        "vendorGuidanceMessage": "NONE"
    }
}
james:~/GGv2Dev/recipes $ 

==--------Creating Component---------==
{
    "arn": "arn:aws:greengrass:us-east-1:215907354426:components:com.example.ImgClassification:versions:1.0.0",
    "componentName": "com.example.ImgClassification",
    "componentVersion": "1.0.0",
    "creationTimestamp": 1671284659.062,
    "status": {
        "componentState": "REQUESTED",
        "message": "NONE",
        "errors": {},
        "vendorGuidance": "ACTIVE",
        "vendorGuidanceMessage": "NONE"
    }
}
.


// PhpSpreadsheet 설치하는 방법
https://teserre.tistory.com/19

composer 먼저 설치해야 함
참고: https://www.lesstif.com/php-and-laravel/php-composer-23757293.html

/lib/PhpSpreadsheet 디렉토리 만들고
권한을 707로 만들어 주고

composer require phpoffice/phpspreadsheet

이렇게 하니까 됩니다.

sudo systemctl restart php7.4-fpm


====================================================
AWS IoT Core workshop for beginners (Korean)
====================================================
https://catalog.us-east-1.prod.workshops.aws/workshops/f87a7c7a-0af8-416a-80ee-7c25c5789307/ko-KR

sdk/test/Python > 이거 안 되요. 대소문자 구분합니다.
sdk/test/python > 이게 됩니다.




curl -s -o /dev/null -w "%{http_code}" http://daechang.epcs.co.kr
curl -s -o /dev/null -w "%{http_code}" https://toe.kr

curl --output /dev/null --silent --write-out %{http_code} http://daechang.epcs.co.kr

엑셀 등록 10분 후 에러
504 Gateway Time-out

systemctl restart php7.4-fpm.service
php7.4-fpm 재시작하면 바로 502나옴
502 Bad Gateway

cron 설정을 해 줘야 합니다.
자동으로 재작하도록..


* * * * * root /home/user/dovecot_restart


#!/bin/bash
STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://daechang.epcs.co.kr)

if [ $STATUS -eq 502 ]; then
  /etc/init.d/php7.4-fpm restart
elif [ $STATUS -ne 200 ]; then
  /etc/init.d/nginx restart
fi


cc5b95
f08abe


대시보드
monitor
monitor-dashboard

수주/출하
truck-outline
truck

생산/재고
basket-fill
cart-arrow-down
stack-overflow
human-baby-changing-table

설비관리
factory

정비관리
wrench

통계보고
chart-line
chart-histogram
chart-pie

기준정보
folder-search-outline
feature-search-outline

환경설정
cogs
cog-box
cog-transfer-outline

