
all: svnexport package

clean:
	rm -rf migrationassistant 
	rm -rf packages

svnexport:	
	svn export --force http://joomlacode.org/svn/pasamioprojects/migrationassistant

package: svnexport
	mkdir -p packages
	cd migrationassistant; tar -zcf ../packages/migrationassistant.tgz * 
