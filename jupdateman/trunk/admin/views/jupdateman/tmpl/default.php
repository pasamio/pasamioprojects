<?php
/**
 * View: JUpdateMan Default Template 
 * 
 * PHP4/5
 *  
 * Created on Sep 28, 2007
 * 
 * @package JUpdateMan
 * @author Sam Moffatt <pasamio@gmail.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2009 Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/pasamioprojects
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
	<div align="left">
		<p><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_WELCOME') ?></p>
		<p><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_PROXY') ?></p>
		<p><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_SIMPLEPROCESS') ?></p>
		<ol>
			<li><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_STEP1') ?></li>
			<li><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_STEP2') ?></li>			
			<li><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_STEP3') ?></li>
		</ol>
		<?php if($this->http_support || $this->curl_support) : ?>	
			<p><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_CONTINUE') ?></p>
		<?php else: ?>
			<p><?php echo JText::_('COM_JUPDATEMAN_JUPDATEMAN_NOSUPPORT') ?></p>
		<?php endif; ?>
	</div>