<?php defined('_JEXEC') or die('Restricted access'); ?>

<form method="POST" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_DETAILS' ); ?></legend>

		<table class="admintable">
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
<style type="text/css">
	
	.thumbnail {
		padding: 5px;
		float:left;
		margin:2px;
		background-position: 30px 20px;
		background-repeat: no-repeat;
		width: 180px; 
		height: 180px; 
	}
	
	
	.published, .unpublished, .allowcomments, .disallowcomments {
		
		width: 32px;
		height: 32px;
		border: 0px solid white;
		background-repeat: no-repeat;
	}
	
	.published {
		border: lightgreen;
	
	}
	
	.unpublished {
		border: red;
	}
	
	.allowcomments {
		border: lightgreen;
	}
	
	.disallowcomments {
		border-color: red;
	}
	
	.icon-32-article-add                    {   background-image: url(../images/toolbar/icon-32-publish.png);   }
	.icon-32-article-add.active {
		background-position: 0px -32px;
	}
	
	.thumbnail img {
		
	}
	
</style>


<?php echo $this->pageNav->getListFooter(); ?>



	
	<table class="adminlist">
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
			<th width="50">
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
		$published =  JHTML::_('grid.published', $row, $i,'tick.png', 'publish_x.png','File' );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<img src="<?php echo JURI::base().("../components/com_eventgallery/helpers/thumbnail.php?view=thumbnail&folder=".$row->folder."&file=".$row->file."&option=com_eventgallery&width=100&height=50")?>" />
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td class="order">
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'orderFileUp', 'JLIB_HTML_MOVE_UP', $this->ordering); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'orderFileDown', 'JLIB_HTML_MOVE_UP', $this->ordering ); ?></span>
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>						
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
			</td>
			<td>
				<?php echo $published; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->caption; ?></a>
			</td>
			<td>
				<a href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=folder='.$row->folder) ?>">
					<?php echo $row->commentCount ?> Kommentare
				</a>
			</td>
			<td>
				<?php $user = JFactory::getUser($row->userid); echo $user->name;?>, <?php echo JHTML::Date($row->lastmodified,JText::_('DATE_FORMAT_LC2')) ?>
			</td>
			
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
	
</form>

<div style="clear:both">
	
<!--
	<?php foreach($this->files as $entry) :?>
		    <?php $this->assign('entry',$entry)?>		    
	        <a name="<?php echo $this->entry->id?>"></a>
			<div class="thumbnail" 
	            
	   style="background-image: url('<?php echo JURI::base().("../components/com_eventgallery/helpers/thumbnail.php?view=thumbnail&folder=".$entry->folder."&file=".$entry->file."&option=com_eventgallery&width=150&height=130")?>');"
	>			<div style="padding-left: 30px;"> Comments: <?php echo $this->entry->commentCount ?> / Hits: <?php echo $this->entry->hits ?> </div>	
				<div onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&task=".($this->entry->published==0?"publishFile":"unpublishFile")."&folderid=".$this->event->id."&cid[]=".$this->entry->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$this->entry->id) ?>'"
			         class="<?php  echo $this->entry->published==1? "icon-32-publish published" : "icon-32-unpublish unpublished";?>"></div>
				<div onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&task=".($this->entry->allowcomments==0?"allowComments":"disallowComments")."&folderid=".$this->event->id."&cid[]=".$this->entry->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$this->entry->id) ?>'"
			         class="<?php  echo $this->entry->allowcomments==1? "icon-32-edit active allowcomments" : "icon-32-edit toolbar-inactive disallowcomments";?>"></div>
				<span><?php echo $this->pageNav->orderUpIcon( '2', true, 'orderEventUp', 'JLIB_HTML_MOVE_UP', true); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon( '2', '4', true, 'orderEventDown', 'JLIB_HTML_MOVE_UP', true ); ?></span>

				<input type="text" name="order[]" size="5" value="<?php echo $this->entry->ordering; ?>" class="text_area" style="text-align: center" />
			</div>		    
			
	<?php endforeach?>
-->
</form>
</div>

