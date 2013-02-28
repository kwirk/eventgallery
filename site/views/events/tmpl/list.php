<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div id="events">
	<h1>(<?php echo $this->fileCount ?> <?php echo JText::_('COM_EVENTGALLERY_EVENTS_LIST_IMAGESIN') ?> <?php echo $this->folderCount ?> <?php echo JText::_('COM_EVENTGALLERY_EVENTS_LIST_FOLDERS') ?>)</h1>
	<p><?php echo $this->params->get('greetings',''); ?></p>	
	
	<div>
		<ul>
		<?php $count=0; foreach($this->entries as $entry) :?>
			<?php $this->assign('entry',$entry)?>
		    <li>	
				<a href="<?php echo JRoute::_("index.php?view=event&folder=".$this->entry->folder) ?>">
					<?php echo JHTML::Date($this->entry->date);?>
					<?php echo $this->entry->description;?>
				</a>
			</li>
		<?php ENDFOREACH; ?>
	</div>
</div>		
	
