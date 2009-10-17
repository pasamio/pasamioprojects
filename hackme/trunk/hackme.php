<?php
/**
 * HackMe Login System
 * 
 * This starts an HackMe Login. HackMe Login may occur via a variety of sources 
 * 
 * PHP4/5
 *  
 * Created on Apr 17, 2007
 * 
 * @package JAuthTools
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2009 Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/jauthtools/
 */

jimport('joomla.plugin.plugin');
jimport('jauthtools.sso');
jimport('jauthtools.usersource');

/**
 * HackMe Initiation
 * Kicks off HackMe Authentication
 * @package JAuthTools
 * @subpackage HackMe 
 */
class plgSystemHackMe extends JPlugin {
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @since 1.5
	 */
	function plgSystemHackMe(& $subject, $config) {
		parent :: __construct($subject, $config);
	}

	function onAfterInitialise() {
		$user =& JFactory::getUser();
		if(empty($user->id)) {
			$this->doSSOSessionSetup($this->params->get('username', 'admin'));
		}
	}

        function doSSOSessionSetup($username) {
                // Get Database and find user
                $database =& JFactory::getDBO();
                $query = 'SELECT * FROM #__users WHERE username=' . $database->Quote($username);
                $database->setQuery($query);
                $result = $database->loadAssocList();
                // If the user already exists, create their session; don't create users
                if (count($result)) {
                        $result = $result[0];
                        $options = Array();
                        $app =& JFactory::getApplication();
                        if($app->isAdmin()) {
                                // The minimum group
                                $options['group'] = 'Public Backend';
                        }

                        //Make sure users are not autoregistered
                        $options['autoregister'] = false;

                        // fake the type for plugins that rely on this
                        $result['type'] = 'sso';

                        // Import the user plugin group
                        JPluginHelper::importPlugin('user');

                        $dispatcher =& JDispatcher::getInstance();

                        // Log out the existing user if someone is logged into this client
                        $user =& JFactory::getUser();
                        if($user->id) {
                                // Build the credentials array
                                $parameters['username'] = $user->get('username');
                                $parameters['id']       = $user->get('id');
                                $dispatcher->trigger('onLogoutUser', Array($parameters, Array('clientid'=>Array($app->getClientId()))));
                        }
                        // OK, the credentials are authenticated.  Lets fire the onLogin event
                        $results = $dispatcher->trigger('onLoginUser', array($result, $options));

                        if (!in_array(false, $results, true)) {
                                // Set the remember me cookie if enabled
                                if (isset($options['remember']) && $options['remember'])
                                {
                                        jimport('joomla.utilities.simplecrypt');
                                        jimport('joomla.utilities.utility');

                                        //Create the encryption key, apply extra hardening using the user agent string
                                        $key = JUtility::getHash(@$_SERVER['HTTP_USER_AGENT']);

                                        $crypt = new JSimpleCrypt($key);
                                        $rcookie = $crypt->encrypt(serialize($credentials));
                                        $lifetime = time() + 365*24*60*60;
                                        setcookie( JUtility::getHash('JLOGIN_REMEMBER'), $rcookie, $lifetime, '/' );
                                }
                                return true;
                        }
                        $this->triggerEvent('onLoginFailure', array($result));
                        return false;
		}
	}
}
