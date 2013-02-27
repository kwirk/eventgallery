<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td colspan=2>
				<img class="thumbnail" src="<?php echo JURI::base().("../components/com_eventgallery/helpers/image.php?view=resizeimage&folder=".$this->comment->folder."&file=".$this->comment->file."&option=com_eventgallery&width=100&height=50")?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->comment->name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
					<?php echo JText::_( 'Email' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo $this->comment->email;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="link">
					<?php echo JText::_( 'Link' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="link" id="link" size="32" maxlength="250" value="<?php echo $this->comment->link;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="ip">
					<?php echo JText::_( 'IP' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="ip" id="ip" size="32" maxlength="250" value="<?php echo $this->comment->ip;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="user_id">
					<?php echo JText::_( 'UserID' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="user_id" id="user_id" size="32" maxlength="250" value="<?php echo $this->comment->user_id;?>" />
			</td>
		</tr>		
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Text' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="text_area" name="text" id="text" size="32" cols="50" rows="5" maxlength="450"><?php echo $this->comment->text;?></textarea>
			</td>
		</tr>	
		<tr>
			<td width="100" align="right" class="key">
				<label for="date">
					<?php echo JText::_( 'Date' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="date" id="date" size="32" maxlength="250" value="<?php echo $this->comment->date;?>" />
			</td>
		</tr>			
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="id" value="<?php echo $this->comment->id; ?>" />
<input type="hidden" name="file" value="<?php echo $this->comment->file; ?>" />
<input type="hidden" name="folder" value="<?php echo $this->comment->folder; ?>" />
<input type="hidden" name="task" value="saveComment" />


</form>
