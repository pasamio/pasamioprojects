SVNPATH=http://svn.joomlacode.org/svn/jauthtools/sso/joomla10x/soapsso
SVNTARGET=svn/soapsso
FILESTARGET=files/soapsso

all: svn build

svn: force
	svn co $(SVNPATH) $(SVNTARGET)
	
prepare-svn:
	mkdir -p $(FILESTARGET)	
	cp -rf $(SVNTARGET)/* $(FILESTARGET)
	./clean-svn.sh $(FILESTARGET)
	
build: prepare-svn
	mkdir -p packages
	cd files/soapsso; tar -zcf ../../packages/com_soapsso.tgz component
	cd files/soapsso; tar -zcf ../../packages/mod_soapsso.tgz module

clean:
	rm -rf svn files packages
	
	
force: ; # The below was forced to run and will run every time
.PHONY: clean 
