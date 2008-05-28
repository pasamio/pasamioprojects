#!/bin/sh
# Process libraries and generate manifest files

HEADERS=/home/moffats/public_html/eclipse-workspace/jlibman_tools/headers
#OUTPUT=/home/moffats/public_html/eclipse-workspace/jlibman/admin/manifests
OUTPUT=/home/moffats/public_html/eclipse-workspace/joomla_update/administrator/manifests/libraries
FOOTER=/home/moffats/public_html/eclipse-workspace/jlibman_tools/data/footer.dat
TMP=/home/moffats/public_html/eclipse-workspace/jlibman_tools/tmp

STARTPOINT=/home/moffats/public_html/eclipse-workspace/joomla_update/libraries
ORIGINAL_PWD=`pwd`
cd $STARTPOINT
for i in `find * -maxdepth 0 -type d `
do
	DIRNAME=`basename $i` 
	echo Processing $DIRNAME
	if [ -f $HEADERS/$DIRNAME.header ]; then
		cat $HEADERS/$DIRNAME.header > $OUTPUT/$DIRNAME.xml
	else
		echo "WARNING: No header found for this directory: $DIRNAME"; 
		echo "WARNING: $DIRNAME has been skipped, please create a header for it.";
		continue;
	fi
	cd $DIRNAME
	# Use -type f so that we ignore symlinks
	find -type f | grep -v svn | sed -e 's/.\/\(.*\)/\t\t<file>\1<\/file>/g' >> $OUTPUT/$DIRNAME.xml
	cat $FOOTER >> $OUTPUT/$DIRNAME.xml
	cd ..
done
cd $ORIGINAL_PWD
