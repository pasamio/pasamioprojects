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

class Menu_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array('params','alias','title_alias');
	var $newfieldlist = Array('alias');
	
	function getName() { return "Menu ETL Plugin"; }
	function getAssociatedTable() { return 'menu'; }
	
	function getWhere() { return " WHERE published != -2 "; }
	
	function mapvalues($key,$value) {
		switch($key) {
			case 'params':
				$value = str_replace('author=','show_author=',$value);
				$value = str_replace('readmore=','show_readmore=',$value);
				$value = str_replace('pdf=','show_pdf_icon=',$value);
				$value = str_replace('print=','show_print_icon=',$value);
				$value = str_replace('email=','show_email_icon=',$value);
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
				// Conditional
				// If there isn't a http run the url swapper
				if(stristr($value,'http') === FALSE) $value = str_replace('url=','url=http://', $value);
				// Wack in show_title for good measure				
				$value .= "\nshow_title=1\n";
				// Show page title for places where show title isn't relevant
				if(stristr($value,'show_page_title') === FALSE) $value .= "show_page_title=1\n";
				return $value;
				break;
			case 'alias':
				if(!strlen(trim($value))) {
					// Name + Menu type to ensure that the alias is unique
					return stringURLSafe($this->_currentRecord['name'] .' '.$this->_currentRecord['menutype'].' '. $this->_currentRecord['id']);
				}
				return $value;
				break; // could really let this drop down here but anyway				
			default:
				return $value;
				break;
		}
	}
}
?>
