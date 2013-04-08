<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 

$editor = JFactory::getEditor();
			

?>


<script type="text/javascript">
	Joomla.submitbutton = function(task) {	

			<?php 
				echo $editor->save("text");
			?>		
			Joomla.submitform(task, document.getElementById('adminForm'));
	}
</script>

<form method="POST" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_DETAILS' ); ?></legend>

		<table class="admintable table-striped table">
		<tr>
			<td width="100" align="right" class="key">
				
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_FOLDERNAME' ); ?>:
				
			</td>
			<td>				
					<input class="text_area" type="text" name="folder" id="folder" size="150" maxlength="250" value="<?php echo  $this->event->folder; ?>" />
			</td>	
		</tr>	
		<tr>
			<td width="100" align="right" class="key">
				
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PICASA_KEY' ); ?>:
				
			</td>
			<td>				
					<input class="text_area" type="text" name="picasakey" id="picasakey" size="150" maxlength="250" value="<?php echo  $this->event->picasakey; ?>" />
			</td>	
		</tr>
    	<tr>
			<td width="100" align="right" class="key">
				
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PASSWORD' ); ?>:
				
			</td>
			<td>				
					<input class="text_area" type="text" name="password" id="password" size="150" maxlength="250" value="<?php echo  $this->event->password; ?>" />
			</td>	
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_TAGS' ); ?>:
				
			</td>
			<td>				
					<input class="text_area" type="text" name="tags" id="tags" size="150" maxlength="250" value="<?php echo  $this->event->tags; ?>" />
			</td>	
		</tr>	
		<tr>
			<td width="100" align="right" class="key">
				<label for="date">
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_EVENT_DATE' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_('calendar',$this->event->date,'date','date');?>							
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_DESCRIPTION' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="description" id="description" size="150" maxlength="250" value="<?php echo $this->event->description;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">		
				<label for="published">		
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PUBLISHED' ); ?>:				
				</label>
			</td>
			<td>				
				<select name="published" id="published">
						<option value="0"><?php echo JText::_( 'COM_EVENTGALLERY_EVENT_UNPUBLISHED' ); ?></option>
						<option value="1" <?php if ($this->event->published==1) echo "selected=\"selected\"" ; ?>><?php echo JText::_( 'COM_EVENTGALLERY_EVENT_PUBLISHED' ); ?></option>						
				</select>
			</td>	
		</tr>
		<tr>
			<td width="100" align="right" class="key">		
				<label for="cartable">		
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_CARTABLE' ); ?>:				
				</label>
			</td>
			<td>				
				<select name="cartable" id="cartable">
						<option value="1"><?php echo JText::_( 'COM_EVENTGALLERY_EVENT_CARTABLE' ); ?></option>
						<option value="0" <?php if ($this->event->cartable==0) echo "selected=\"selected\"" ; ?>><?php echo JText::_( 'COM_EVENTGALLERY_EVENT_NOT_CARTABLE' ); ?></option>						
				</select>
			</td>	
		</tr>	

		<tr>
			<td width="100" align="right" class="key">
				<label for="text">
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_TEXT' ); ?>:
				</label>
			</td>
			<td>
			
			<?php echo $editor->display( 'text',  htmlspecialchars($this->event->text, ENT_COMPAT, 'UTF-8') , '100%', '250', '75', '20' ) ;
			?>

			</td>
		</tr>			
	</table>
	</fieldset>
</div>



<div class="clr"></div>

<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="id" value="<?php echo $this->event->id; ?>" />
<input type="hidden" name="task" value="edit" />
</form>