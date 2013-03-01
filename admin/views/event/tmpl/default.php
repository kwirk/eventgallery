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
				
					Published:
				
			</td>
			<td>				
				<select name="published" id="published">
						<option value="0">unpublished</option>
						<option value="1" <?php if ($this->event->published==1) echo "selected=\"selected\"" ; ?>>published</option>						
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
			<?php $editor = JFactory::getEditor(); 
			echo $editor->display( 'text',  $this->event->text , '100%', '250', '75', '20' ) ;
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
<!--</form>
	
<form method="POST" name="adminForm" id="adminForm">
-->
<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="folderid" value="<?php echo $this->event->id; ?>" />	
	
</style>


<?php echo $this->pageNav->getListFooter(); ?>



	
	<table class="table table-striped adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ID' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_FOLDERNAME' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->files ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ORDER' ); ?> 
				<?php echo JHTML::_('grid.order',  $this->files, 'filesave.png', 'saveFileOrder' ); ?>	
			</th>
			<th width="105">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PUBLISHED' ); ?>
			</th>		
				
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_DESCRIPTION' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_COMMENTS' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_MODIFIED_BY' ); ?>
			</th>
			
		</tr>			
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->files ); $i < $n; $i++)
	{
		$row = &$this->files[$i];		
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$published =  JHTML::_('jgrid.published', $row->published, $i );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>				
				<a name="<?php echo $row->id; ?>"></a>		
				<?php echo $row->id; ?>
			</td>
			<td>
				<img class="thumbnail" src="<?php echo JURI::base().("../components/com_eventgallery/helpers/image.php?view=resizeimage&folder=".$row->folder."&file=".$row->file."&option=com_eventgallery&width=100&height=50")?>" />
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td class="order">
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>						
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="input-mini" style="text-align: center" />
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'orderFileUp', 'JLIB_HTML_MOVE_UP', $this->ordering); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'orderFileDown', 'JLIB_HTML_MOVE_UP', $this->ordering ); ?></span>
				
			</td>
			<td>
				<div class="btn-group">	
					<a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_PUBLISH' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&task=".($row->published==0?"Filepublish":"Fileunpublish")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->published==1? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-published"></i>
				    </a>

					<a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_ALLOWCOMMENTS' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&task=".($row->allowcomments==0?"allowComments":"disallowComments")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->allowcomments==1? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-comments"></i>
				    </a>

				    <a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_MAINIMAGE' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&task=".($row->ismainimage==0?"isMainImage":"isNotMainImage")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->ismainimage==1? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-mainimage"></i>
				    </a>

				    <a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_MAINIMAGEONLY' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&task=".($row->ismainimageonly==0?"isMainImageOnly":"isNotMainImageOnly")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->ismainimageonly==0? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-mainimageonly"></i>
				    </a>


			    </div>	
			</td>
			<td>
				<?php echo $row->caption; ?>
			</td>
			<td class="center">
				<a href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=folder='.$row->folder) ?>">
					<?php echo $row->commentCount ?>
				</a>
			</td>
			<td>
				<?php $user = JFactory::getUser($row->userid); echo $user->name;?>, <?php echo JHTML::Date($row->lastmodified,JText::_('DATE_FORMAT_LC4')) ?>
			</td>
			
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
	
</form>

<div style="clear:both">
	

	
				

</form>
</div>

