<?php defined('_JEXEC') or die('typical 60s music'); ?>
<form action="index.php" method="post" name="adminForm">
<?php

if (!count($this->messages)) {
        echo '<p>'. JText::_('No warnings detected').'</p>';
} else {
        jimport('joomla.html.pane');
        $pane =& JPane::getInstance('sliders');
        echo $pane->startPane("warning-pane");
        foreach($this->messages as $message) {
                echo $pane->startPanel($message['message'], str_replace(' ','', $message['message']));
                echo '<div style="padding: 5px;" >'.$message['description'].'</div>';
                echo $pane->endPanel();
        }
        echo $pane->startPanel(JText::_('WARNINGFURTHERINFO'),'furtherinfo-pane');
        echo '<div style="padding: 5px;" >'. JText::_('WARNINGFURTHERINFODESC') .'</div>';
        echo $pane->endPanel();
        echo $pane->endPane();
}
// the below br fixes a formatting issue in ff3 on mac
?><br />
<input type="hidden" name="task" value="display" />
<input type="hidden" name="option" value="com_jupdateman" />
</form>