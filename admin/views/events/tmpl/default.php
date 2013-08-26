<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 

JHtml::_('behavior.tooltip');
$document = JFactory::getDocument();
$version =  new JVersion();
if ($version->isCompatible('3.0')) {
 
} else {
    $css=JURI::base().'components/com_eventgallery/media/css/legacy.css';
    $document->addStyleSheet($css);
}

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();

?>

<script type="text/javascript">
    Joomla.orderTable = function()
    {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>')
        {
            dirn = 'desc';
        }
        else
        {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form method="post" id="adminForm" name="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_EVENTGALLERY_EVENT_SEARCH_LABEL');?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_EVENTGALLERY_EVENT_SEARCH_PLACEHOLDER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_EVENTGALLERY_ORDERS_SEARCH_DESC'); ?>" />
            </div>
            <div class="btn-group pull-left">
                <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
                <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                    <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                    <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
                </select>
            </div>
            <div class="btn-group pull-right">
                <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                    <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                </select>
            </div>
        </div>
		<div class="clearfix"> </div>

	<table class="adminlist table table-striped">
		<thead>
			<tr>
                <th width="20">
                    <!--<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />-->
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="nowrap" width="1%">
					
				</th>
				<th>
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_FOLDERNAME' ); ?>
				</th>

				<th>
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_ORDER' ); ?> 
					<?php echo JHTML::_('grid.order',  $this->items, 'filesave.png', 'events.saveorder' ); ?>	
				</th>				
				<th>
					<?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_DESCRIPTION' ); ?>
				</th>
                <th class="nowrap">
                    <?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_EVENT_DATE' ); ?>
                </th>
                <th>
					&nbsp;
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
		
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = $this->items[$i];		
			$checked 	= JHTML::_('grid.id',   $i, $row->id );
			$editLink 	= JRoute::_( 'index.php?option=com_eventgallery&task=event.edit&id='. $row->id );
			$uploadLink = JRoute::_( 'index.php?option=com_eventgallery&task=upload.upload&folderid='. $row->id );
			$filesLink  = JRoute::_( 'index.php?option=com_eventgallery&view=files&folderid='. $row->id);


			?>
			<tr class="">
                <td>
                    <?php echo $checked; ?>
                </td>
				<td>
					<div class="btn-group">

                        <?php echo JHtml::_('jgrid.published', $row->published, $i, 'events.'); ?>

                        <?php IF ($row->cartable==1): ?>
                        <a style="color: green" class="btn btn-micro active jgrid" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','events.notcartable')">
                            <span class="state"><i class="icon-cart"></i></span>
                        </a>
                        <?php ELSE:?>
                            <a class="btn btn-micro jgrid" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','events.cartable')">
                                <span class="state"><i class="icon-cart"></i></span>
                            </a>
                        <?php ENDIF ?>

                        <?php IF (strpos($row->folder,'@')=== false): ?>
						<?php /*the following mix of jgrid and btn is for being compatible with joomla 2.5 and 3.0*/ ?>
                            <a href="<?php echo $uploadLink; ?>" id="upload_<?php echo $row->id?>" class="btn btn-micro jgrid">
                                <span class="state "><i class="icon-upload"></i>	<span class="text"></span></span>
                            </a>
                            <a href="<?php echo $filesLink; ?>" id="files_<?php echo $row->id?>" class="btn btn-micro jgrid">
                                <span class="state"><i class="icon-folder-2"></i>	<span class="text"></span></span>
                            </a>
                        <?php ENDIF ?>
                        <a href="<?php echo $editLink; ?>" id="files_<?php echo $row->id?>" class="btn btn-micro jgrid">
                            <span class="state"><i class="icon-edit"></i>	<span class="text"></span></span>
                        </a>
                        
					</div>				
				</td>
				<td>
					<?php echo $row->folder; ?>
				</td>

				<td class="order nowrap">
					<div class="input-prepend">
						<span class="add-on"><?php echo $this->pagination->orderUpIcon( $i, true, 'events.orderdown', 'JLIB_HTML_MOVE_UP', true); ?></span>
						<span class="add-on"><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'events.orderup', 'JLIB_HTML_MOVE_UP', true ); ?></span>
						<input class="width-40 text-area-order" type="text" name="order[]" size="3"  value="<?php echo $row->ordering; ?>" />
					</div>
				</td>

                <td>
					<?php echo $row->description; ?>
				</td>
                <td class="nowrap">
                    <?php echo JHTML::Date($row->date, JText::_('DATE_FORMAT_LC3')); ?><br>
                </td>
                <td>
                    <small>
                        <?php IF (strlen($row->foldertags)>0): ?>
                            <strong><?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_TAGS' ); ?></strong><br>
                            <?php echo $row->foldertags; ?><br>
                        <?php ENDIF ?>
                        <?php IF (strlen($row->picasakey)>0): ?>
                            <strong> <?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PICASA_KEY' ); ?></strong><br>
                            <?php echo $row->picasakey; ?><br>
                        <?php ENDIF ?>
                        <?php IF (strlen($row->password)>0): ?>
                            <strong><?php echo JText::_( 'COM_EVENTGALLERY_EVENTS_PASSWORD' ); ?></strong><br>
                            <?php echo $row->password; ?><br>
                        <?php ENDIF ?>
                    </small>
				</td>
				<td class="center">
					<a href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=folder='.$row->folder) ?>">
						<?php echo $row->commentCount ?>
					</a>
				</td>
				<td><small>
					<?php $user = JFactory::getUser($row->userid); echo $user->name;?><br /> 
                    <?php echo JText::_( 'COM_EVENTGALLERY_EVENT_CREATED' ); ?> <?php echo JHTML::Date($row->created,JText::_('DATE_FORMAT_LC4')) ?><br>
                    <?php echo JText::_( 'COM_EVENTGALLERY_EVENT_MODIFIED' ); ?> <?php echo JHTML::Date($row->modified,JText::_('DATE_FORMAT_LC4')) ?><br>
                    </small>
				</td>
				
			</tr>
			<?php
			
		}
		?>
		</table>
		<div class="pagination pagination-toolbar">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	</div>

	 <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<input type="hidden" name="option" value="com_eventgallery" />	
	
</form>
