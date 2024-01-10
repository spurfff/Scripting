#!/bin/bash

bind_dir="/etc/bind"
zone_dir="/etc/bind/zones"
forward_zone_file="forward.wired.net"
reverse_zone_file="reverse.wired.net"
options_file="named.conf.options"
local_file="named.conf.local"
green='\033[0;32m'
reset='\033[0m'

cd "${bind_dir}" || exit 1

if file "$bind_dir/$options_file" && named-checkconf "${options_file}"; then
	echo -e "$options_file: ${green}GOOD${reset}\n"
fi

sleep 1

if file "$bind_dir/$local_file" && named-checkconf "${local_file}"; then
	echo -e "$local_file: ${green}GOOD${reset}\n"
fi

sleep 1

cd "${zone_dir}" || exit 1

if file "$zone_dir/$forward_zone_file" && named-checkzone "${forward_zone_file}" "${forward_zone_file}"; then
	echo -e "$forward_zone_file: ${green}GOOD${reset}\n"
fi

sleep 1

if file "$zone_dir/$reverse_zone_file" && named-checkzone "${reverse_zone_file}" "${reverse_zone_file}"; then
	echo -e "$reverse_zone_file: ${green}GOOD${reset}\n"
fi

sleep 1

cd $HOME || exit 1
