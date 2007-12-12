#!/bin/sh
#phing clean; phing;
cp ~/public_html/eclipse-workspace/advancedtools/install.php advancedtools/install.php
cp ~/public_html/eclipse-workspace/advancedtools/advancedtools.xml advancedtools/advancedtools.xml
#phing advtoolszip
cd advancedtools
zip -r ../packages/com_advancedtools.zip *
cd ..
