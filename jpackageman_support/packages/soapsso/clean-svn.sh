#!/bin/sh
# Removes SVN directories
if [ $1 != '' ]; then cd $1; fi
for i in `find -type d  | grep \.svn | rm -rf $i`
do
	rm -rf $i
done	