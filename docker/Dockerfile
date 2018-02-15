FROM ubuntu:16.04
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get clean && \
    apt-get update && \
    apt-get install -y --no-install-recommends apt-utils locales && \
    locale-gen en_US.UTF-8 && update-locale
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8
RUN apt-get -y upgrade && apt-get update --fix-missing

RUN apt-get -y install \
        vim \
        git-flow \
        htop \
        mcrypt \
        composer \
        cron \
        php7.0-fpm \
        php7.0-dev \
        php7.0-mcrypt \
        php7.0-mbstring \
        php7.0-gd \
        php7.0-pdo \
        php7.0-mysqlnd \
        php7.0-mysql \
        php7.0-curl \
        php7.0-tokenizer \
        php7.0-iconv \
        zip \
        unzip \
        php7.0-zip

RUN sed -i -e "s/pid =.*/pid = \/var\/run\/php7.0-fpm.pid/" /etc/php/7.0/fpm/php-fpm.conf && \
    sed -i -e "s/error_log =.*/error_log = \/proc\/self\/fd\/2/" /etc/php/7.0/fpm/php-fpm.conf && \
    sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/7.0/fpm/php-fpm.conf && \

    sed -i "s/listen = .*/listen = 9000/" /etc/php/7.0/fpm/pool.d/www.conf && \
    sed -i "s/;catch_workers_output = .*/catch_workers_output = yes/" /etc/php/7.0/fpm/pool.d/www.conf

COPY php_setting/php.ini /etc/php/7.0/fpm/php.ini

COPY entrypoint/ /var/entrypoint/
RUN chmod +x /var/entrypoint/*

RUN usermod -u 1000 www-data

EXPOSE 9000
