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


<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="id" value="<?php echo $this->event->id; ?>" />
<input type="hidden" name="task" value="" />


<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="folderid" value="<?php echo $this->event->id; ?>" />	
	

<?php echo $this->pageNav->getListFooter(); ?>
	
	
	<table class="table table-striped adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ID' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_FILENAME' ); ?>
			</th>
			<th width="20">			
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>			
			<th>
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ORDER' ); ?> 
				<?php echo JHTML::_('grid.order',  $this->files, 'filesave.png', 'saveFileOrder' ); ?>	
			</th>
			<th width="105">
				<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_OPTIONS' ); ?>
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
				<!-- <a name="<?php echo $row->id; ?>"></a> -->
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
					<a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_PUBLISH' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=files&task=".($row->published==0?"Filepublish":"Fileunpublish")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->published==1? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-published"></i>
				    </a>

					<a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_ALLOWCOMMENTS' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=files&task=".($row->allowcomments==0?"allowComments":"disallowComments")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->allowcomments==1? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-comments"></i>
				    </a>

				    <a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_MAINIMAGE' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=files&task=".($row->ismainimage==0?"isMainImage":"isNotMainImage")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->ismainimage==1? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-mainimage"></i>
				    </a>

				    <a title="<?php echo JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_MAINIMAGEONLY' ); ?>" onClick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=files&task=".($row->ismainimageonly==0?"isMainImageOnly":"isNotMainImageOnly")."&folderid=".$this->event->id."&cid[]=".$row->id."&limitstart=".JRequest::getVar('limitstart', '0', '', 'int')."#".$row->id) ?>'"
				        class="<?php echo $row->ismainimageonly==0? "btn btn-micro active" : "btn btn-micro";?>">
				        <i class="eg-icon-mainimageonly"></i>
				    </a>


			    </div>	
			</td>
			<td>
				<span class="caption" data-value="<?php echo htmlentities($row->caption); ?>" data-id="<?php echo $row->id ?>">
					<span class="caption-content">
					<?php echo strlen($row->caption)>0?$row->caption:'----'; ?>
					<span>
				</span>
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

<script type="text/javascript">


window.addEvent("domready", function(){

	$$('.caption-content').addEvent('click', function(e){

		var content = e.target;
		var span = content.getParent('span');
		var id = span.getAttribute('data-id');
		var form = new Element('div', {
			class: 'input-append',
		});
		var input = new Element('textarea', {
			name: 'caption',
			value: span.getAttribute('data-value'),
		});
		var buttonCancel = new Element('button',{
			text: '<?php echo JText::_('COM_EVENTGALLERY_COMMON_CANCEL')?>',
			class: 'btn btn-small',
			events: {
				click: function(e) {
					content.setStyle('display','inline');
					form.dispose();
					e.preventDefault();

				}
			}
		});

		var buttonSave = new Element('button', {
			text: '<?php echo JText::_('COM_EVENTGALLERY_COMMON_SAVE')?>',
			class: 'btn btn-small',
			events: {
				click: function(e) {

					var myRequest = new Request({
					    url: '<?php echo JRoute::_('index.php?task=saveFileCaption&option=com_eventgallery&format=raw&cid=', false); ?>'+id,
					    data: 'caption='+input.value,

					}).post();

					span.setAttribute('data-value',input.value);
					content.innerHTML = input.value.length>0?input.value:'----';
					content.setStyle('display','inline');
					form.dispose();
					e.preventDefault();
				}
			}
		})
		form.grab(input);;
		form.grab(buttonCancel);
		form.grab(buttonSave);
		span.grab(form);
		content.setStyle('display','none');

	});

});

</script>