<?php
/**
 * Google Search Module
 *  
 * Created on Nov 8, 2006
 * 
 * @package TCC Tools
 * @author Samuel Moffatt <Sam.Moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see Joomla!Forge Project: http://forge.joomla.org
 */
 
$size = $params->get('search_width', 13);
$text = $params->get('search_button_text', 'Go');
?>
<div><form method="GET" action="http://www.google.com/search" id="google">
<input type="text"   name="q" maxlength="256" id="google-input" class="inputbox" size="<?php echo $size ?>" />
<input type="submit" value="<?php echo $text ?>" class="button"></form></div>
