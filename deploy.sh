#!/bin/bash
#This is a deployment script for capistrano-like directory layout
DATE=`date +%s`
git clone --recursive git://github.com/synchrone/busstop-pnz.git releases/$DATE

#current release symlink
rm -f releases/current
ln -s $DATE releases/current

cd releases/current
./bootstrap.sh
cd ../..

#webroot symlink
rm -f current
ln -s releases/$DATE/www current