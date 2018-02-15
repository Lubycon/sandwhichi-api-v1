#!/bin/bash

PROJECT_ROOT_PATH=$1
BITBUCKET_TEAM_NAME=$2
BITBUCKET_USERNAME=$3
BITBUCKET_PASSWORD=$4

artisanFile=$PROJECT_ROOT_PATH/artisan
envFile=$PROJECT_ROOT_PATH/.env


cd $PROJECT_ROOT_PATH
echo "[API] Start entrypoint."

if [ -f "$artisanFile" ];then
	echo "[API] $artisanFile found!"
else
	echo "[API] Try clone sandwhichi-api project!"
	echo "[API] Please wait for clone project. for 5~10min"
	git clone https://$BITBUCKET_USERNAME:$BITBUCKET_PASSWORD@bitbucket.org/$BITBUCKET_TEAM_NAME/sandwhichi-api.git $projectDir
	echo "[API] Clone sandwhichi-api Done!"
	echo "[API] Check .env file with daniel!"
fi

if [ -f "$artisanFile" ];then
	composer install --prefer-dist
	chmod -R 777 $PROJECT_ROOT_PATH/storage;
	chmod -R 777 $PROJECT_ROOT_PATH/bootstrap/cache;

	if [ -f "$envFile" ];then
		echo "[API] .env file exists"
	else
		cp .env.example .env
		php artisan key:generate
	fi

	php artisan ide-helper:generate
	php artisan ide-helper:meta
	php artisan migrate
	php artisan l5-swagger:publish
	php artisan l5-swagger:generate
	echo "[API] Runnning"
else
	echo "[API] $artisanFile not found. Sorry.. Check your Bitbucket credential"
fi
php-fpm7.0
