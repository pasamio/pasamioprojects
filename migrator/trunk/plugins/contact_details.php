<?php
/**
 * Contact Details Table ETL
 * 
 * This plugin handles ETL for the Contact component 
 * 
 * PHP4
 *  
 * Created on May 22, 2007
 * 
 * @package Migrator
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

defined('_VALID_MOS') or die('Restricted Access');

/**
 * Contact ETL Plugin
 */
class Contact_Details_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	
	var $valuesmap = Array('alias','params');
	
	var $newfieldlist = Array('alias');
	
	function getName() { return "Contact Details ETL Plugin"; }
	
	function getAssociatedTable() { return 'contact_details'; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'params':
				$value = str_replace('name=','show_name=',$value);
				$value = str_replace('position=','show_position=',$value);
				$value = str_replace('email=','show_email=',$value);
				$value = str_replace('street_address=','show_street_address=',$value);
				$value = str_replace('suburb=','show_suburb=',$value);
				$value = str_replace('state=','show_state=',$value);
				$value = str_replace('postcode=','show_postcode=',$value);
				$value = str_replace('country=','show_country=',$value);
				$value = str_replace('telephone=','show_telephone=',$value);
				$value = str_replace('mobile=','show_mobile=',$value);
				$value = str_replace('fax=','show_fax=',$value);
				$value = str_replace('misc=','show_misc=',$value);
				$value = str_replace('menu_image=','show_image=',$value);
				$value = str_replace('vcard=','allow_vcard=',$value);
				$value = str_replace('icons=','contact_icons=',$value);
				$value = str_replace('address=','icon_address=',$value);
				$value = str_replace('email=','icon_email=',$value);
				$value = str_replace('telephone=','icon_telephone=',$value);
				$value = str_replace('mobile=','icon_mobile=',$value);
				$value = str_replace('fax=','icon_fax=',$value);
				$value = str_replace('email_form=','show_email_form=',$value);
				$value = str_replace('email_description_text=','email_description=',$value);
				$value = str_replace('email_copy=','show_email_copy=',$value);
				return $value;
				break;
			case 'alias':
				if(!strlen(trim($value))) {
					return stringURLSafe($this->_currentRecord['name']);
				}
				return $value;
				break; // could really let this drop down here but anyway
			default:
				return $value;
				break;
		}
	}
}
