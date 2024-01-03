#!/bin/bash

target_file="index.html"
wget -q -O ${target_file} ipchicken.com
grep -E -o "([0-9]{1,3}\.){3}[0-9]{1,3}" index.html
rm "${target_file}"
