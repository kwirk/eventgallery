<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" id="adminForm" name="adminForm">

<div id="editcell">
	<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ID' ); ?>
			</th>
			<th class="nowrap" width="1%">
				
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_FOLDERNAME' ); ?>
			</th>
			<th width="20">
				<!--<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />-->
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>			
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ORDER' ); ?> 
				<?php echo JHTML::_('grid.order',  $this->items, 'filesave.png', 'saveEventOrder' ); ?>	
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
			<th class="nowrap">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_EVENT_DATE' ); ?>
			</th>
			<th class="nowrap">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_COMMENTS' ); ?>
			</th>
			<th class="nowrap">
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
		$published =  JHTML::_('jgrid.published', $row->published, $i );
		#$published = JHtml::_('jgrid.published', $row->published, $i, 'articles.', true, 'cb', $row->publish_up, $row->publish_down);
		$link 		= JRoute::_( 'index.php?option=com_eventgallery&task=edit&cid[]='. $row->id );
		$uploadlink = JRoute::_( 'index.php?option=com_eventgallery&task=upload&cid[]='. $row->id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->id; ?></a>
			</td>
			<td>
				<div class="btn-group">
					<?php echo $published; ?>
					<?php /*the following mix of jgrid and btn is for being compatible with joomla 2.5 and 3.0*/ ?>
					<a href="<?php echo $uploadlink; ?>" class="btn btn-micro jgrid">
						<span class="state icon-16-newcategory "><i class="icon-upload"></i>	<span class="text"></span></span>					
					</a>
				</div>				
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->folder; ?></a>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td class="order nowrap">
				
				
				<?php $disabled = $this->ordering ?  '' : 'disabled="disabled"'; ?>						
				<input type="number" name="order[]" size="3"  value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="input-mini" style="text-align: center" />
				<span><?php echo $this->pageNav->orderUpIcon( $i, true, 'orderEventUp', 'JLIB_HTML_MOVE_UP', $this->ordering); ?></span>
				<span><?php echo $this->pageNav->orderDownIcon( $i, $n, true, 'orderEventDown', 'JLIB_HTML_MOVE_UP', $this->ordering ); ?></span>
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
			<td class="nowrap">
				<?php echo JHTML::Date($row->date, JText::_('DATE_FORMAT_LC3')); ?><br>		
			</td>			
			<td class="center">
				<a href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=folder='.$row->folder) ?>">
					<?php echo $row->commentCount ?>
				</a>
			</td>
			<td>
				<?php $user = JFactory::getUser($row->userid); echo $user->name;?>, <?php echo JHTML::Date($row->lastmodified,JText::_('DATE_FORMAT_LC3')) ?>
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