#!/bin/bash
#This is a deployment script for capistrano-like directory layout
DATE=`date +%s`
git clone --recursive git://github.com/synchrone/busstop-pnz.git releases/$DATE
APPPATH=releases/$DATE/application
chmod g+w $APPPATH/cache $APPPATH/logs

#current release symlink
rm -f releases/current
ln -s $DATE releases/current
php releases/current/www/index.php --task=fetch

#webroot symlink
rm -f current
ln -s releases/$DATE/www current