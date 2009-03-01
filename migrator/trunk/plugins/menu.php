<?php
/**
 * Menu ETL Plugin
 * 
 * Menu ETL Plugin for #__menu
 * 
 * MySQL 4.0
 * PHP4
 *  
 * Created on 23/05/2007
 * 
 * @package Migrator
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Sam Moffatt
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioproject
 */

defined('_VALID_MOS') or die('Restricted Access');

/**
 * Menu Table Migration Plugin
 */
class Menu_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array('params','alias','title_alias','link');
	var $newfieldlist = Array('alias');
	
	function getName() { return "Menu ETL Plugin"; }
	function getAssociatedTable() { return 'menu'; }
	
	function getWhere() { return " WHERE published != -2 "; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'params':
				if(strstr($this->_currentRecord['link'],'option=com_contact')) {
					// com_contact does a few things a little differently
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
				} else {
					// Default option, typically content
					$value = str_replace('author=','show_author=',$value);
					$value = str_replace('readmore=','show_readmore=',$value);
					$value = str_replace('pdf=','show_pdf_icon=',$value);
					$value = str_replace('print=','show_print_icon=',$value);
					$value = str_replace('leading=','num_leading_articles=',$value);
					$value = str_replace('page_title=','show_page_title=',$value);
					$value = str_replace('header=','page_title=',$value);
					$value = str_replace('intro=','num_intro_articles=',$value);
					$value = str_replace('columns=','num_columns=',$value);
					$value = str_replace('link=','num_links=',$value);
					$value = str_replace('pagination=','show_pagination=',$value);
					$value = str_replace('pagination_results=','show_pagination_results=',$value);
					$value = str_replace('item_title=','show_title=',$value);
					$value = str_replace('category=','show_category=',$value);
					$value = str_replace('category_link=','link_category=',$value);
					$value = str_replace('rating=','show_vote=',$value);
					$value = str_replace('createdate=','show_create_date=',$value);
					$value = str_replace('modifydate=','show_modify_date=',$value);
					$value = str_replace('description=','show_description=',$value);
					$value = str_replace('description_image=','show_description_image=',$value);
					$value = str_replace('introtext=','show_intro=',$value);
					$value = str_replace('section=','show_section=',$value);
					$value = str_replace('section_link=','link_section=',$value);
					$value = str_replace('description_cat=','show_category_description=',$value);
					$value = str_replace('date=','show_date=',$value);
					$value = str_replace('hits=','show_hits=',$value);
					$value = str_replace('headings=','show_headings=',$value);
					$value = str_replace('empty_cat=','show_empty_categories=',$value);
					$value = str_replace('other_cat=','show_categories=',$value);
					$value = str_replace('cat_items=','show_cat_num_articles=',$value);
					$value = str_replace('display=','show_pagination_limit=',$value);
					$value = str_replace('navigation=','show_item_navigation=',$value);
					$value = str_replace('email=','show_email_icon=',$value);
					
					// Conditional
					// If there isn't a http run the url swapper
					if(stristr($value,'http') === FALSE) $value = str_replace('url=','url=http://', $value);
					// Wack in show_title for good measure				
					$value .= "\nshow_title=1\n";
					// Show page title for places where show title isn't relevant
					if(stristr($value,'show_page_title') === FALSE) $value .= "show_page_title=1\n";
				}
				return $value;
				break;
			case 'alias':
				if(!strlen(trim($value))) {
					// Name + Menu type to ensure that the alias is unique
					return stringURLSafe($this->_currentRecord['name'] .' '.$this->_currentRecord['menutype'].' '. $this->_currentRecord['id']);
				}
				return $value;
				break;
			case 'link':
				if (strstr($value,'option=com_contact')) {
					if (preg_match('/catid=(\d+)\n/',$this->_currentRecord['params'],$cat_id)) {
						if (strstr($value,'?')) {
							$value .= '&view=category&catid=' . $cat_id[1];
						}
						else {
							$value .= '?view=category&catid=' . $cat_id[1];
						}
					}
				}
				return $value;
				break; // could really let this drop down here but anyway				
			default:
				return $value;
				break;
		}
	}
}

