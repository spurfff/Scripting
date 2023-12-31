FROM php:latest

RUN apt-get update && apt-get install -y openssh-server vim iputils-ping zip libzip-dev sudo

RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-enable zip

RUN service ssh start

RUN useradd -m -c "Lain Iwakura" -s /bin/bash -G sudo -u 1000 lain \
    && echo "lain:P4ssw0rd!" | chpasswd \
    && chage -d 0 lain

WORKDIR /home/lain

COPY . /home/lain/

EXPOSE 22

CMD [ "/usr/sbin/sshd", "-D" ]
