#!/bin/bash

programs=("nmap" "tcpdump" "git" "bind9-dnsutils" "neofetch" "vim")
for item in "${programs[@]}"; do
	if [[ ! -f "/usr/bin/$item" || ! -f "/usr/sbin/$item" ]]; then
		sudo apt-get update && sudo apt-get install -y "$item"
	fi
done

# Because we gotta have it...
install_vim() {
	echo -e "Installing vim...\n"
	sleep 1
	sudo apt-get update && sudo apt-get install -y vim
}

install_neofetch() {
	echo -e "Installing neofetch\n"
	sleep 1
	sudo apt-get update && sudo apt-get install -y neofetch
}

if [[ -f /etc/vim/vimrc ]]; then
	if ! grep -q "set number" /etc/vim/vimrc; then
		echo -e "\nset number\n" | sudo tee -a /etc/vim/vimrc >/dev/null
	fi
	if ! grep -q "color desert" /etc/vim/vimrc; then
		echo -e "\ncolor desert\n" | sudo tee -a /etc/vim/vimrc >/dev/null
	fi
else
	echo -e "vim is not installed...\n"
	sleep 1
	while true; do
		read -p "Would you like to install it? [Y/N]: " install_vim
		case "$install_vim" in
			[Yy]|[Yy][Ee][Ss])
				install_vim
				break
				;;
			[Nn]|[Nn][Oo])
				echo -e "Exiting...\n"
				sleep 1
				exit 0
				;;
			*)
				echo -e "Invalid selection...Please try again...\n"
				sleep 1
				;;
		esac
	done
fi

if [[ -f /usr/bin/neofetch ]]; then
	if ! grep -q "/usr/bin/neofetch" $HOME/.bashrc; then
		echo -e "\n/usr/bin/neofetch\n" | tee -a $HOME/.bashrc >/dev/null
	fi
fi

sed -e 's/#force_color_prompt=yes/force_color_prompt=yes/g' $HOME/.bashrc



if [[ ! -f $HOME/.hushlogin ]]; then
	touch $HOME/.hushlogin
	if [[ ! -f /etc/skel/.hushlogin ]]; then
		sudo touch /etc/skel/.hushlogin
	fi
fi



