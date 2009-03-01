<?php
/**
 * Content Table ETL
 * 
 * This plugin handles ETL for the Content Component 
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
 * Content ETL Plugin
 */
class Content_ETL extends ETLPlugin {
	
	var $ignorefieldlist = Array();
	var $valuesmap = Array('introtext','fulltext','alias');
	var $newfieldlist = Array('alias');
	
	function getName() { return "Content ETL Plugin"; }	
	function getAssociatedTable() { return 'content'; }
	
	// Ignore items in the trash
	function getWhere() { return " WHERE state != -2 "; }
	
	function mapvalues($key,$value) {

		switch($key) {
			case 'introtext':
			case 'fulltext':
				//---------------------------------------------------
				// mospagebreak
				// Replace {mospagebreak} -> <hr class="system-pagebreak" />
				$value = str_replace('{mospagebreak}','<hr class="system-pagebreak" />',$value);
				// Replace {mospagebreak title=The page title} -> <hr class="system-pagebreak" title="The page title" />
				$value = preg_replace('/{mospagebreak title=([^\}]*)}/','<hr class="system-pagebreak" title="\1" />', $value);
				// Replace {mospagebreak heading=The first page} -> <hr class="system-pagebreak" alt="The first page" />
				$value = preg_replace('/{mospagebreak heading=([^\}]*)}/','<hr class="system-pagebreak" alt="\1" />', $value);
				// Replace {mospagebreak title=The page title&heading=The first page} -> <hr class="system-pagebreak" title="The page title" alt="The first page" />
				$value = preg_replace('/{mospagebreak title=([^\& ]*)&heading=([^\}]*)}/', '<hr class="system-pagebreak" title="\1" alt="\2" />', $value);
				// Replace {mospagebreak heading=The first page&title=The page title} -> <hr class="system-pagebreak" title="The page title" alt="The first page" />
				$value = preg_replace('/{mospagebreak heading=([^\&]*)&title=([^\}]*)}/', '<hr class="system-pagebreak" title="\2" alt="\1" />', $value);
				
				//---------------------------------------------------
				// mosloadposition
				$value = str_replace('{mosloadposition', '{loadposition',$value);
				
				//---------------------------------------------------
				// mosimage
				$value = handleMosImage($key, $value, $this);
				
				//---------------------------------------------------
				// moscode; turn it into geshi (not really appropriate but best match)
				$value = str_replace('{moscode}','<pre>', $value);
				$value = str_replace('{/moscode}','</pre>', $value);
				
				
				//---------------------------------------------------
				// Done, return result
				return $value;
				break;
			case 'alias':
				// Use the title_alias if it exists
				if(strlen(trim($this->_currentRecord['title_alias']))) {
					// clean it up appropriately
					return stringURLSafe($this->_currentRecord['title_alias']);
				// If it doesn't see if this is empty
				} else if(!strlen(trim($value))) {
					// use the title field
					return stringURLSafe($this->_currentRecord['title']);
				} 
				return $value; // shouldn't happen anyway...
				break; // could really let this drop down here but anyway
			default:
				return $value;
				break;
		}
	}

	function getSQLPrologue() {
		return "# Start of Content\n";
	}
	
	function getSQLEpilogue() {
		return "# End of Content\n";
	}
}

/**
 * Handle mosimage replacements...fun!
 * @param string key If its introtext or fulltext
 * @param string value The stuff we're replacing
 * @param ETLPlugin etlplugin An object of class or subclass of ETL Plugin
 * @return string result of $value being processed
 */
function handleMosImage($key, $value, $etlplugin) {
	global $database, $_MAMBOTS;
	if(!isset($_MAMBOTS)) {
		$_MAMBOTS = new stdClass();
	}
	
	$mambot = null;
	
	if(strpos($value, 'mosimage') === false) {
		return $value;
	}
	$params = new mosParameters($etlplugin->_currentRecord['attribs']);
	
	// set up compat layer
	$row = new stdClass();
	$row->introtext = $etlplugin->_currentRecord['introtext'];
	$row->fulltext = $etlplugin->_currentRecord['fulltext'];
	$row->text = $row->introtext. chr(13) . chr(13) . $row->fulltext;
	$row->images = $etlplugin->_currentRecord['images'];

 	// expression to search for
	$regex = '/{mosimage\s*.*?}/i';
	
	//count how many {mosimage} are in introtext if we're on fulltext
	$introCount=0;
	//count how many {mosimage} are in introtext if we're on fulltext
	if($key == 'fulltext') {	
		preg_match_all( $regex, $row->introtext, $matches );
		$introCount = count ( $matches[0] );
	}
	
	// find all instances of mambot and put in $matches
	preg_match_all( $regex, $row->text, $matches );

 	// Number of mambots
	$count = count( $matches[0] );
 	// mambot only processes if there are any instances of the mambot in the text
 	if ( $count ) {
		// check if param query has previously been processed
		// ^^ i think this is probably a certainty
		if ( !isset($_MAMBOTS->_content_mambot_params['mosimage']) ) {
			// load mambot params info
			$query = "SELECT params"
			. "\n FROM #__mambots"
			. "\n WHERE element = 'mosimage'"
			. "\n AND folder = 'content'"
			;
			$database->setQuery( $query );
			$mambot = null;
			$database->loadObject($mambot);
			
			// save query to class variable
			$_MAMBOTS->_content_mambot_params['mosimage'] = $mambot;
		}

		// pull query data from class variable
		$mambot = $_MAMBOTS->_content_mambot_params['mosimage'];
		
	 	$botParams = new mosParameters( $mambot->params );

	 	$botParams->def( 'padding' );
	 	$botParams->def( 'margin' );
	 	$botParams->def( 'link', 0 );

		$images 	= processImages( $row, $botParams, $introCount );

		// store some vars in globals to access from the replacer
		$GLOBALS['botMosImageCount'] 	= 0;
		$GLOBALS['botMosImageParams'] 	=& $botParams;
		$GLOBALS['botMosImageArray'] 	=& $images;
		//$GLOBALS['botMosImageArray'] 	=& $combine;
		
		// perform the replacement
		//$row->text = preg_replace_callback( $regex, 'botMosImage_replacer', $row->text );
		$value = preg_replace_callback($regex, 'botMosImage_replacer', $value);
		
		// clean up globals
		unset( $GLOBALS['botMosImageCount'] );
		unset( $GLOBALS['botMosImageMask'] );
		unset( $GLOBALS['botMosImageArray'] );
		unset( $GLOBALS['botJosIntroCount'] );
	}	
	return $value;

}

function processImages ( &$row, &$params, &$introCount ) {
	global $mosConfig_absolute_path, $mosConfig_live_site;

	$images 		= array();

	// split on \n the images fields into an array
	$row->images 	= explode( "\n", $row->images );
	$total 			= count( $row->images );

	$start = $introCount; 
	for ( $i = $start; $i < $total; $i++ ) {
		$img = trim( $row->images[$i] );

		// split on pipe the attributes of the image
		if ( $img ) {
			$attrib = explode( '|', trim( $img ) );
			// $attrib[0] image name and path from /images/stories

			// $attrib[1] alignment
			if ( !isset($attrib[1]) || !$attrib[1] ) {
				$attrib[1] = '';
			}

			// $attrib[2] alt & title
			if ( !isset($attrib[2]) || !$attrib[2] ) {
				$attrib[2] = 'Image';
			} else {
				$attrib[2] = htmlspecialchars( $attrib[2] );
			}

			// $attrib[3] border
			if ( !isset($attrib[3]) || !$attrib[3] ) {
				$attrib[3] = 0;
			}

			// $attrib[4] caption
			if ( !isset($attrib[4]) || !$attrib[4] ) {
				$attrib[4]	= '';
				$border 	= $attrib[3];
			} else {
				$border 	= 0;
			}

			// $attrib[5] caption position
			if ( !isset($attrib[5]) || !$attrib[5] ) {
				$attrib[5] = '';
			}

			// $attrib[6] caption alignment
			if ( !isset($attrib[6]) || !$attrib[6] ) {
				$attrib[6] = '';
			}

			// $attrib[7] width
			if ( !isset($attrib[7]) || !$attrib[7] ) {
				$attrib[7] 	= '';
				$width 		= '';
			} else {
				$width 		= ' width: '. $attrib[7] .'px;';
			}

			// image size attibutes
			$size = '';
			if ( function_exists( 'getimagesize' ) ) {
				$size 	= @getimagesize( $mosConfig_absolute_path .'/images/stories/'. $attrib[0] );
				if (is_array( $size )) {
					$size = ' width="'. $size[0] .'" height="'. $size[1] .'"';
				}
			}

			// assemble the <image> tag
			$image = '<img src="'. $mosConfig_live_site .'/images/stories/'. $attrib[0] .'"'. $size;
			// no aligment variable - if caption detected
			if ( !$attrib[4] ) {
				if ($attrib[1] == 'left' OR $attrib[1] == 'right') {
					$image .= ' style="float: '. $attrib[1] .';"';
				} else {
					$image .= $attrib[1] ? ' align="middle"' : '';
				}
			}
			$image .=' hspace="6" alt="'. $attrib[2] .'" title="'. $attrib[2] .'" border="'. $border .'" />';

			// assemble caption - if caption detected
			$caption = '';
			if ( $attrib[4] ) {				
				$caption = '<div class="mosimage_caption"';
				if ( $attrib[6] ) {
					$caption .= ' style="text-align: '. $attrib[6] .';"';
					$caption .= ' align="'. $attrib[6] .'"';
				}
				$caption .= '>';
				$caption .= $attrib[4];
				$caption .= '</div>';
			}
			
			// final output
			if ( $attrib[4] ) {
				// initialize variables
				$margin  		= '';
				$padding 		= '';
				$float			= '';
				$border_width 	= '';
				$style			= '';
				if ( $params->def( 'margin' ) ) {
					$margin 		= ' margin: '. $params->def( 'margin' ).'px;';
				}				
				if ( $params->def( 'padding' ) ) {
					$padding 		= ' padding: '. $params->def( 'padding' ).'px;';
				}				
				if ( $attrib[1] ) {
					$float 			= ' float: '. $attrib[1] .';';
				}
				if ( $attrib[3] ) {
					$border_width	= ' border-width: '. $attrib[3] .'px;';
				}
				
				if ( $params->def( 'margin' ) || $params->def( 'padding' ) || $attrib[1] || $attrib[3] ) {
					$style = ' style="'. $border_width . $float . $margin . $padding . $width .'"';
				}
				
				$img = '<div class="mosimage" '. $style .' align="center">'; 

				// display caption in top position
				if ( $attrib[5] == 'top' && $caption ) {
					$img .= $caption;
				}

				$img .= $image;

				// display caption in bottom position
				if ( $attrib[5] == 'bottom' && $caption ) {
					$img .= $caption;
				}
				$img .='</div>';
			} else {
				$img = $image;
			}

			$images[] = $img;
		}
	}

	return $images;
}

/**
* Replaces the matched tags an image
* @param array An array of matches (see preg_match_all)
* @return string
*/
function botMosImage_replacer( &$matches ) {
	$i = $GLOBALS['botMosImageCount']++;
	return @$GLOBALS['botMosImageArray'][$i];
}

