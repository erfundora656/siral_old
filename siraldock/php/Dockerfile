FROM php:5.6.40-fpm-stretch

ENV TZ America/Havana

RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "deb http://repos.uclv.edu.cu/debian stretch main non-free contrib" >  /etc/apt/sources.list
RUN echo "deb http://repos.uclv.edu.cu/debian stretch-updates main non-free contrib" >>  /etc/apt/sources.list
RUN echo "deb http://repos.uclv.edu.cu/debian-security stretch/updates main non-free contrib" >>  /etc/apt/sources.list

#opcionales
#RUN echo "deb http://repos.uclv.edu.cu/debian stretch-proposed-updates main non-free contrib" >>  /etc/apt/sources.list
#RUN echo "deb http://repos.uclv.edu.cu/debian stretch-backports main non-free contrib" >>  /etc/apt/sources.list
#RUN echo "deb http://repos.uclv.edu.cu/debian-security stretch/updates main non-free contrib" >>  /etc/apt/sources.list

# Dependencies
RUN apt-get update

#ldap
RUN apt-get install -y libldap2-dev && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && docker-php-ext-install ldap

# Git
RUN apt-get install -y git

# Curl
RUN apt-get install -y libcurl3-dev curl && docker-php-ext-install curl

# GD
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd

# MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# BC Math
RUN docker-php-ext-install bcmath

# Custom php.ini config
COPY php.ini /usr/local/etc/php/php.ini

RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set up default directory
WORKDIR /var/www/app