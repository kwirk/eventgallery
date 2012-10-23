<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" id="adminForm" name="adminForm">

<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ID' ); ?>
			</th>
			<th width="20">
				<?php echo JText::_('COM_EVENTGALLERY_EVENTS_UPLOAD'); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_FOLDERNAME' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ORDER' ); ?> 
				<?php echo JHTML::_('grid.order',  $this->items, 'filesave.png', 'saveEventOrder' ); ?>	
			</th>
			<th width="50">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PUBLISHED' ); ?>
			</th>		
				
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_DESCRIPTION' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_TAGS' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PICASA_KEY' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_EVENT_DATE' ); ?>
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
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];		
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$published =  JHTML::_('grid.published', $row, $i );
		$link 		= JRoute::_( 'index.php?option=com_eventgallery&task=edit&cid[]='. $row->id );
		$uploadlink = JRoute::_( 'index.php?option=com_eventgallery&task=upload&cid[]='. $row->id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->id; ?></a>
			</td>
			<td>
				<a href="<?php echo $uploadlink; ?>">
					<?php echo JHTML::_('image','menu/icon-16-upload.png', 'sss', array('border' => 0), true); ?>
				</a>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->folder; ?></a>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td class="order">
				
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'orderEventUp', 'JLIB_HTML_MOVE_UP', $this->ordering); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'orderEventDown', 'JLIB_HTML_MOVE_UP', $this->ordering ); ?></span>
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>						
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
			</td>
			<td>
				<?php echo $published; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->description; ?></a>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->tags; ?></a>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->picasakey; ?></a>
			</td>
			<td>
				<?php echo JHTML::Date($row->date, JText::_('DATE_FORMAT_LC1')); ?><br>		
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
</div>

<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo $this->pageNav->getListFooter(); ?>


		
</form>
<br>