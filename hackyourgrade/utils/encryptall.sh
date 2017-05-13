#!/bin/bash
#
# Quick and dirty multiple recipient encrypt-for-all script by null
#

echo "Gathering pubkeys fingerprints..."
gpg --no-default-keyring --keyring em2017.gpg -k | awk '{ print $1 }' | grep '^[0-9A-F][0-9A-F]' > /tmp/fing

echo "Encrypting..."
echo -n "gpg --no-default-keyring --keyring em2017.gpg "
while read fing; do 
	echo -n "-r $fing " 
done </tmp/fing
echo "-a --encrypt msg1 -o msg.asc"
echo "Done, message encrypted for all your keyring!"
rm /tmp/fing
sleep 3;
