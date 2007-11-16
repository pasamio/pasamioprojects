<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on May 25, 2007
 * 
 * @package Migrator
 * @subpackage Tests
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Department
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */

$enumerator = new ETLEnumerator();
echo '<pre>';
print_r($enumerator->getPlugins(true));
echo '<hr />';
print_r($enumerator->includePlugins(true));
echo '<hr />';
print_r($enumerator->createPlugins(true));
?>
