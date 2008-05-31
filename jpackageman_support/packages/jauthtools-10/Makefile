# JAuthTools for 1.0 Makefile
# Builds all packages

all: directories ldaptools_export httpsso joomla_ldap ldap_sso ldap_ssi ldap_sync ldap_advsync soapsso	

clean:
	echo Cleaning directories
	rm -rf soapsso
	rm -rf httpsso
	rm -rf joomla_ldap
	rm -rf packages
	
directories:
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
	zip -r packages/httpsso.zip httpsso
	
joomla_ldap: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP Library
	zip -r packages/joomla.ldap.bot.zip joomla_ldap/joomla.ldap.bot
	
ldap_sso: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP SSO Mambot
	zip packages/ldap.sso.bot.zip joomla_ldap/ldap.sso.bot
		
ldap_ssi: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP SSI Mambot
	zip -r packages/ldap.ssi.bot.zip joomla_ldap/ldap.ssi.bot
	
ldap_sync: ldaptools_export directories
	echo Building Joomla! 1.0.x LDAP Sync Mambot
	zip -r packages/ldap.sync.bot.zip joomla_ldap/ldap.sync.bot

soapsso: soapsso_export directories
	echo Building SOAP SSO System
	zip -r packages/com_soapsso.zip soapsso/component
	zip -r packages/mod_soapsso.zip soapsso/module

ldap_advsync: ldaptools_export directories
	echo Buidling Joomla! 1.0.x LDAP Advanced Synchronisation Mambot
	zip -r packages/ldap.advsync.bot.zip joomla_ldap/ldap.advsync.bot
	
