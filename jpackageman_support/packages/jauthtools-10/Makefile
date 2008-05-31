# JAuthTools for 1.0 Makefile
# Builds all packages
#
# Notes:
# * mkdir -p ensures that the directory is there, creating only if it doesn't exist
# * for zip, -j removes the paths and -r is recursive


all: directories ldaptools_export joomla_ldap ldap_ssi ldap_sync ldap_advsync ldap_sso httpsso soapsso	

clean:
	echo Cleaning directories
	rm -rf soapsso
	rm -rf httpsso
	rm -rf joomla_ldap
	rm -rf packages
	
directories: packages

packages:
	mkdir -p packages		
	
ldaptools_export:
	svn export --force http://svn.joomlacode.org/svn/jauthtools/ldap/joomla_10x/trunk/ joomla_ldap
	rm -rf joomla_ldap/.project
	rm -rf joomla_ldap/CHANGELOG

soapsso_export:
	svn export --force http://svn.joomlacode.org/svn/jauthtools/sso/joomla10x/soapsso/ soapsso
	
httpsso: directories
	echo Building HTTP SSO...
	svn export --force http://svn.joomlacode.org/svn/jauthtools/sso/joomla10x/trunk/ httpsso
	rm -rf httpsso/.project
	zip -jr packages/httpsso.zip httpsso
	
joomla_ldap: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP Library
	zip -jr packages/joomla.ldap.bot.zip joomla_ldap/joomla.ldap.bot
	
ldap_sso: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP SSO Mambot
	zip -jr packages/ldap.sso.bot.zip joomla_ldap/ldap.sso.bot
		
ldap_ssi: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP SSI Mambot
	zip -jr packages/ldap.ssi.bot.zip joomla_ldap/ldap.ssi.bot
	
ldap_sync: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP Sync Mambot
	zip -jr packages/ldap.sync.bot.zip joomla_ldap/ldap.sync.bot

soapsso: soapsso_export directories
	echo Building SOAP SSO System
	cd soapsso/component; zip -r ../../packages/com_soapsso.zip *
	zip -jr packages/mod_soapsso.zip soapsso/module

ldap_advsync: ldaptools_export directories
	echo Buidling Joomla! 1.0.x LDAP Advanced Synchronisation Mambot
	zip -jr packages/ldap.advsync.bot.zip joomla_ldap/ldap.advsync.bot
	
