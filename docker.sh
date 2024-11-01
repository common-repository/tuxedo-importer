#!/bin/bash

if [ ! -f /etc/apache2/conf-enabled/8888.conf ]; then

	echo "Listen 8080" >> /etc/apache2/conf-enabled/8888.conf
	service apache2 restart

fi