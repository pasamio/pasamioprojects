# Login Redirect Builder
# Builds all packages
#
# Notes:
# * mkdir -p ensures that the directory is there, creating only if it doesn't exist
# * for zip, -j removes the paths and -r is recursive


all: directories files build

clean:
	echo Cleaning directories
	rm -rf files
	rm -rf packages
	
directories: packages

packages:
	mkdir -p packages		
	
files:
	svn export --force http://svn.joomlacode.org/svn/pasamioprojects/mod_loginredirect files
	rm -rf files/.project
	rm -rf files/CHANGELOG

build:
	zip -jr packages/mod_loginredirect.zip files/
	
