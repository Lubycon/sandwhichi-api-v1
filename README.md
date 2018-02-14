# README #

##### 본 문서에는 sandwhichi API 서버 구동부터 배포까지의 기술이 명세되어있습니다

### 서버 최소 실행시 ###

> .env 파일 내에는 보안과 밀접한 요소들이 존재합니다. 민감한 정보들에 대해서는 .env.example에 명시되어 있지 않고, 백엔드 개발자에게 문의하여 값을 채워 넣으시기 바랍니다.

> Homestead 작동법에 대한 설명은 제외되어있습니다.

#### 로컬 데이터베이스 생성
>sandwhichi-Docker-Master 프로젝트를 이용하여 설치하였다면 패스하세요
1. mysql -uroot -p
2. 비밀번호 = secret
3. create database sdw_service;
4. exit

#### 글로벌 설정
1. composer install
2. cp .env.example .env
3. php artisan key:generate

#### 디렉토리 권한 설정
> 로컬서버에서만 777로 운용할것
* chmod -R 777 storage bootstrap/cache

### PHP 필수 모듈 안내
* sudo apt-get install php7.0-gd
* sudo apt-get install php7.0-pdo
* sudo apt-get install php7.0-mysqlnd
* sudo apt-get install php7.0-mbstring
* sudo apt-get install php7.0-pdo_mysql
* sudo apt-get install php7.0-mysql
* sudo apt-get install php7.0-curl
* sudo apt-get install php7.0-mcrypt
* sudo apt-get install php7.0-tokenizer
* sudo apt-get install php7.0-iconv
* sudo apt-get install php7.0-dom
* sudo apt-get install php7.0-xml

### 데이터 시딩
> composer가 업데이트 된 seed파일을 읽지 않을경우 
* composer dump-autoload

> 마이그레이션과 시딩을 같이 할 때
* php artisan migrate --seed

> 더미데이터 시딩 할때
1. APP_ENV=local일 경우 더미 데이터 삽입됩니다
2. APP_ENV=staging 혹은 production일 경우 운영데이터만 삽입됩니다.
* php artisan db:seed


### 스웨거 문서 업데이트 ###
>프로젝트 내의 API 정적문서를 업데이트합니다.
>업데이트된 문서는 git으로 버전관리 되지 않으며 각 프로젝트마다 실행해야합니다
* php artisan l5-swagger:publish // 최초 실행시 html, css clone
* php artisan l5-swagger:generate // json data update


### 서버 재시작 ###
> 세팅이 완료 된 후에는 서버를 재 시작해주는것이 좋습니다.
> homestead에서는 머신을 재시작해야하고 supervisorctl 구문만 실행하면 됩니다.
* sudo service nginx restart;
* sudo service php7.0-fpm restart;
* sudo service mysql restart;
* sudo supervisorctl restart all;

### 패키지 버전관리 ###
> composer install
>> composer.lock의 해시값을 참조하여 모든 프로젝트의 패키지 버전을 통일 관리합니다.

> composer update
>> 모든 패키지를 최신 버전으로 업데이트하고 composer.lock의 해시값을 업데이트합니다.

> composer require
>> 새로운 패키지를 추가할때 사용합니다. 패키지에 의존성이 필요할경우 자동으로 추가됩니다.
* 모든 서버 개발자의 패키지 버전을 통일하기 위해 반드시 composer install을 이용합니다.

### Mysql Client Tool에서 homestead DB 접속하는법 ###
>vagrant가 구동되고 있는 상태에서만 접근 가능합니다.
* host 127.0.0.1
* user homestead
* password secret
* database sdw_service
* port 33060

### Mysql Client Tool에서 docker DB 접속하는법 ###
>docker가 구동되고 있는 상태에서만 접근 가능합니다.
* host database
* user master
* password secret
* database sdw_service
* port 33061