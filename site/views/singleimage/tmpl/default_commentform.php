<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<?php IF ($this->model->file->allowcomments==1 && $this->use_comments==1): ?>	
	<div class="commentform" id="commentform">
		
			<h1><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_NEW') ?></h1>

			<div class="error">
				<?php
					    foreach($this->getErrors() as $error)
					    echo $error.'<br>';
				?>
			</div>
		
		
			<form method="post" action="<?php echo JRoute::_("index.php?option=com_eventgallery&task=save_comment&view=singleimage")?>">
				<div><label for="name"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_FORM_NAME') ?></label><input type="text" name="name" value="<?php echo JRequest::getVar('name') ?>"></div>
				<div><label for="email"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_FORM_EMAIL') ?></label><input type="text" name="email" value="<?php echo JRequest::getVar('email') ?>"></div>
				<div><label for="link"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_FORM_LINK') ?></label><input type="text" name="link" value="<?php echo JRequest::getVar('link') ?>"></div>
				<div><label for="text"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_FORM_TEXT') ?></label><textarea name="text"><?php echo JRequest::getVar('text') ?></textarea></div>
				
				<div class="captcha">
					<label class="captcha" for="password"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_FORM_CAPTCHA') ?></label>
					<img class="captcha" alt="captcha" src="<?php echo JRoute::_("index.php?option=com_eventgallery&task=displayCaptcha&format=raw&random=".microtime())?>">
					<input class="captcha" type="text" maxlength="3" name="password" value="<?php echo JRequest::getVar('password') ?>">
				</div>
				
				<div><label for="submit"></label><input type="submit" name="submit" value="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_FORM_SAVE') ?>"></div>
				<input type="hidden" name="folder" value="<?php echo JRequest::getVar('folder') ?>">
				<input type="hidden" name="file"   value="<?php echo JRequest::getVar('file') ?>">
			</form>
		
	</div>		
<?php ENDIF ?>