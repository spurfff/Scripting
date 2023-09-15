FROM php:latest

RUN apt update && apt install -y iputils-ping vim openssh-server unzip zip sudo

RUN useradd -m -c "Lain Iwakura" -s /bin/bash -u 1000 -G sudo lain \
	&& echo 'lain:P4ssw0rd!' | chpasswd \
	&& chage -d 0 lain

RUN service ssh start

WORKDIR /home/lain

COPY . /home/lain

EXPOSE 22

CMD [ "/usr/sbin/sshd", "-D" ]
