#!/bin/bash
#
# ECorp POST REST API Consumer
#

# URL
url=http://e.corp/api.php

# Auth info
user=snakamoto
pass="151819921980"

#Logfile
log=/home/$user/unread.txt

# api function (use only one!)
# f=get_unread_msg
f=get_unread_msg_count

# contact api
if [ -f $log ]; then
	rm $log
	echo $(curl -X POST --data="apiuser=$user&apikey=$(echo -n $pass | base64)&api=$f" $url) > $log
else
	echo $(curl -X POST --data="apiuser=$user&apikey=$(echo -n $pass | base64)&api=$f" $url) > $log
fi

## EOF
