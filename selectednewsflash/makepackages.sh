#!/bin/sh
# Builds the packages
ORIGINAL=`pwd`
FILELIST=''
for i in `ls trunk`
do
	if [ -d trunk/$i ]; then
		echo Building $i
		cd trunk/$i
		tar -zcvf ../../packages/$i.tgz *
		echo
		FILELIST="$FILELIST $i.tgz "
		cd $ORIGINAL
	fi
done

echo Done building Selected Content.
