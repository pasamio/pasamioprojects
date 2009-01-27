# Standard Operations
all: common core sso sso-plugins tokenlogin usersource extras openid-plugins # base #sso-sampleplugins


clean:
	rm -rf packages

clean-all:
	rm -rf sso
	rm -rf usersource
	rm -rf ldap 
	rm -rf packages
	rm -rf gmail
	rm -rf mod_contextlogin
	rm -rf svnexport

clean-svnexport:
	rm -rf svnexport

# Common Operations
svnexport:	
	svn export --force http://joomlacode.org/svn/jauthtools/sso/joomla15/trunk sso
	svn export --force http://joomlacode.org/svn/jauthtools/usersources/joomla15/trunk usersource
	svn export --force http://joomlacode.org/svn/jauthtools/ldap/joomla_15x/trunk ldap
	svn export --force http://joomlacode.org/svn/jauthtools/google/plgauthenticationadvgmail/ gmail
	svn export --force http://joomlacode.org/svn/jauthtools/mod_contextlogin/trunk/ mod_contextlogin
	# fake this to prevent it regrabbing stuff constantly
	touch svnexport
	
svnexport-refresh: clean-svnexport svnexport

packages:
	mkdir -p packages 
	
common: svnexport packages

# Core Package and Dependencies
core: common sso-library usersource-library helper-library
	tar zcf packages/pkg_jauthtools_core.tgz pkg_jauthtools_core.xml packages/lib_jauthtools_sso.tgz packages/lib_jauthtools_usersource.tgz packages/lib_jauthtools_helper.tgz

sso-library: common
	cd sso/libraries/jauthtools/; tar zcf ../../../packages/lib_jauthtools_sso.tgz lib_jauthtools_sso.xml sso.php 

usersource-library: common
	cd usersource/libraries/jauthtools; tar zcf ../../../packages/lib_jauthtools_usersource.tgz lib_jauthtools_usersource.xml usersource.php

helper-library:
	cd ldap/libraries/jauthtools; tar zcf ../../../packages/lib_jauthtools_helper.tgz lib_jauthtools_helper.xml helper.php

# SSO Package
sso: common sso-plugin-system sso-module sso-helpermodule sso-component 
	tar zcf packages/pkg_jauthtools_sso.tgz pkg_jauthtools_sso.xml packages/com_ssomanager.tgz packages/mod_sso.tgz packages/mod_ssohelper.tgz packages/plgSystemSSO.tgz
	
sso-plugin-system: common
	cd sso/plugins/system/; tar zcf ../../../packages/plgSystemSSO.tgz sso.php sso.xml
	
sso-module: common
	cd sso/module/mod_sso; tar zcf ../../../packages/mod_sso.tgz mod_sso.php mod_sso.xml

sso-helpermodule: common
	cd sso/module/mod_ssohelper; tar zcf ../../../packages/mod_ssohelper.tgz mod_ssohelper.php mod_ssohelper.xml
	
sso-component: common
	cd sso/component/com_ssomanager; tar zcf ../../../packages/com_ssomanager.tgz admin site ssomanager.xml
	

# SSO Plugins
sso-plugins: common sso-plugin-edirldap sso-plugin-http sso-plugin-ip sso-plugin-simplesso
	tar zcf packages/pkg_jauthtools_ssoplugins.tgz pkg_jauthtools_ssoplugins.xml packages/plgSSOeDirLDAP.tgz packages/plgSSOHTTP.tgz packages/plgSSOIP.tgz packages/plgSSOSimpleSSO.tgz

sso-plugin-edirldap: common
	cd sso/plugins/sso; tar zcf ../../../packages/plgSSOeDirLDAP.tgz edirldap.*
	
sso-plugin-http: common
	cd sso/plugins/sso; tar zcf ../../../packages/plgSSOHTTP.tgz http.*
	
sso-plugin-ip: common
	cd sso/plugins/sso; tar zcf ../../../packages/plgSSOIP.tgz ip.*
	
sso-plugin-simplesso: common
	cd sso/plugins/sso; tar zcf ../../../packages/plgSSOSimpleSSO.tgz simplesso.*
	
# OpenID Plugins
openid-plugins: common
	cd sso/plugins/sso; tar zcf ../../../packages/plgSSOOpenID.tgz openid.* openid

# Token Login
tokenlogin: common tokenlogin-library tokenlogin-component tokenlogin-ssoplugin
	tar zcf packages/pkg_jauthtools_tokenlogin.tgz pkg_jauthtools_tokenlogin.xml packages/com_tokenlogin.tgz packages/lib_jauthtools_token.tgz packages/plgSSOTokenLogin.tgz
	
tokenlogin-library: common
	cd sso/libraries/jauthtools; tar zcf ../../../packages/lib_jauthtools_token.tgz lib_jauthtools_token.xml token.php
	
tokenlogin-component: common
	cd sso/component/com_tokenlogin; tar zcf ../../../packages/com_tokenlogin.tgz admin site tokenlogin.xml
	
tokenlogin-ssoplugin: common
	cd sso/plugins/sso/; tar zcf ../../../packages/plgSSOTokenLogin.tgz tokenlogin.*
	
# User Sources
usersource: common usersource-plugin-system usersource-plugin-ldap usersource-plugin-session
	tar zcf packages/pkg_jauthtools_usersource.tgz pkg_jauthtools_usersource.xml packages/plgSystemSync.tgz packages/plgUserSourceLDAP.tgz packages/plgUserSourceSession.tgz

usersource-plugin-system: common
	cd usersource/plugins/system; tar zcf ../../../packages/plgSystemSync.tgz sync.*
	
usersource-plugin-ldap: common
	cd usersource/plugins/usersource; tar zcf ../../../packages/plgUserSourceLDAP.tgz ldap.*
	
usersource-plugin-session: common
	cd usersource/plugins/usersource; tar zcf ../../../packages/plgUserSourceSession.tgz session.*
	
# Extras
extras: common ldap-plugin-advldap ldap-plugin-user gmail-plugin-auth contextlogin-module-site
	tar zcf packages/pkg_jauthtools_extras.tgz pkg_jauthtools_extras.xml packages/plgAuthenticationAdvLDAP.tgz packages/plgUserLDAP.tgz packages/plgAuthenticationAdvGMail.tgz packages/mod_contextlogin.tgz
	
ldap-plugin-advldap: common
	cd ldap/plugins/authentication; tar zcf ../../../packages/plgAuthenticationAdvLDAP.tgz advldap.*
	
ldap-plugin-user: common
	cd ldap/plugins/user; tar zcf ../../../packages/plgUserLDAP.tgz ldap.*
	
gmail-plugin-auth: common
	cd gmail; tar zcf ../packages/plgAuthenticationAdvGMail.tgz advgmail.*
	
contextlogin-module-site: common
	cd mod_contextlogin; tar zcf ../packages/mod_contextlogin.tgz *
