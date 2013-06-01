#!/bin/bash
DOCROOT=www
APPPATH=application
GCLOSURE="compilers/compiler.jar"
YUI="compilers/yuicompressor-2.4.7.jar"

#check deps
if [ "`which java`" == '' ] ; then echo You should have Java installed to compress JS and CSS; exit 1; fi

#compile resources
echo Compiling CSS...
rm -f $DOCROOT/css/compiled.css
cat `ls $DOCROOT/css/*.css` > $DOCROOT/css/compiled.css
java -jar $YUI --type=css --charset=utf-8 -o $DOCROOT/css/compiled.css $DOCROOT/css/compiled.css

echo Compiling JS...
rm -f $DOCROOT/js/compiled.js
JS=""
for file in $DOCROOT/js/*; do
    JS="$JS --js=\"$file\"";
done
java -jar $GCLOSURE $JS --js_output_file="$DOCROOT/js/compiled.js" > /dev/null 2>&1

#prepare kohana
chmod g+w $APPPATH/cache $APPPATH/logs

#launch data fetching
echo Fetching routes...
./minion fetch

echo All done!