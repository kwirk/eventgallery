<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>



<html>
<body>
<table width=500 border=1 bordercolor="#000000" cellspacing=0>
	<tr valign=top>
		<td width="100" bgcolor="#EEEEEE"><img
			src="<?php echo $this->file->getImageUrl(200, 200, false);?>"></td>

		<td>
		<table cellspacing=0 cellpadding=0 width="100%" bgcolor="#EEEEEE">
			<tr>
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_NAME') ?>:</td>
				<td><?php echo $this->newComment->name?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_FOLDER') ?>:</td>
				<td><?php echo $this->newComment->folder?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_IP') ?>:</td>
				<td><?php echo $this->newComment->ip?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ID') ?>:</td>
				<td><?php echo $this->newComment->id?> / <?php echo $this->newComment->user_id?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_MAIL') ?>:</td>
				<td><a href="mailto: <?php echo $this->newComment->email?>"><?php echo $this->newComment->email?></a>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_LINK') ?>:</td>
				<td><a href:"<?php echo $this->newComment->link?>"><?php echo $this->newComment->link?></a>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_FILE') ?>:</td>
				<td><?php echo $this->newComment->file?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_TEXT') ?>:</td>
				<td><?php echo $this->newComment->text?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#EEEEEE" colspan="2">
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=editComment&cid[]=".$this->newComment->id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ACTION_EDIT') ?></a> <br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=comments&filter=",false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ACTION_SHOW_ALL') ?></a> <br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=comments&filter=folder=".$this->newComment->folder,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ACTION_SHOW_ALL_FOR_FOLDER') ?> <?php echo $this->newComment->folder?></a> <br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=comments&filter=file=".$this->newComment->file.";folder=".$this->newComment->folder,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ACTION_SHOW_ALL_FOR_IMAGE') ?></a> <br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=comments&filter=user_id=".$this->newComment->user_id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ACTION_SHOW_ALL_FOR_USER') ?> <?php echo $this->newComment->user_id?></a> <br>
		<br>
		<br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=unpublishCommentByMail&format=raw&cid[]=".$this->newComment->id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_ACTION_DELETE') ?> <?php echo $this->newComment->id?></a> <br>
		<br>
		<br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=disallowCommentsForFileByMail&format=raw&cid[]=".$this->file->id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_COMMENT_DISALLOW') ?></a><br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=allowCommentsForFileByMail&format=raw&cid[]=".$this->file->id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_COMMENT_ALLOW') ?></a> <br>
		<br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=unpublishFileByMail&format=raw&cid[]=".$this->file->id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_IMAGE_UNPUBLISH') ?></a><br>
		<a
			href="<?php echo JURI::base().JRoute::_("administrator/index.php?option=com_eventgallery&task=publishFileByMail&format=raw&cid[]=".$this->file->id,false,-1) ?>"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_MAIL_IMAGE_PUBLISH') ?></a></td>
	</tr>
</table>
</td>
</tr>
</table>
</body>
</html>

