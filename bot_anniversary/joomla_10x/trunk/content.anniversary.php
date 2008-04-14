<?php
/**
 * Anniversary Content Mambot
 * 
 * Puts in the years since a given date automatically and substitute it in.
 * 
 * PHP4/5
 *  
 * Created on Dec 7, 2007
 * 
 * @package Anniversary-Content
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botAnniversaryReplacer' );

/**
*/
function botAnniversaryReplacer( $published, &$row, &$params, $page=0 ) {
	global $database, $_MAMBOTS;
	
	// simple performance check to determine whether bot should process further
	if ( strpos( $row->text, 'anniversary' ) === false ) {
		return true;
	}
	
 	// expression to search for
	$regex = '/{anniversary\s*.*?}/i';	
	$row->text = preg_replace_callback( $regex, 'botanniversaryReplacerCalculator', $row->text );
}

function botAnniversaryReplacerCalculator(&$matches) {
	$d1 = str_replace('}','',str_replace('{anniversary ','',$matches[0]));
	$parts = explode('-',$d1);
	$yx = date('Y',time());
	$mx = date('m',time());
	$dx = date('d',time());
	$yeardiff = $yx - $parts[0];
	if($mx < $parts[1]) $yeardiff--;
	else if($dx < $parts[2]) $yeardiff--;
	return $yeardiff;
}


