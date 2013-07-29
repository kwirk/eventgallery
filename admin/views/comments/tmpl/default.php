<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 
$document = JFactory::getDocument();
$version =  new JVersion();
if ($version->isCompatible('3.0')) {

} else {
    $css=JURI::base().'components/com_eventgallery/media/css/legacy.css';
    $document->addStyleSheet($css);
}
?>

<form action="index.php" method="post" id="adminForm" name="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="">
			<div class="pull-left">
				Filter: <?php

				$filters = preg_split('/;/',$this->filter);
				$total = count($filters);
				$count = 0;

				foreach($filters as $filterItem)
				{
				    $temp = preg_split('/;/',$this->filter);
				    $filterArray = Array();
				    foreach($temp as $item)
				    {
				        if (strcmp($item,$filterItem)!=0)
				        {
				            array_push($filterArray,$item);
				        }
				    }
				    $filterString = implode(';',$filterArray);
				    if (strlen($filterItem)>0)
				    {
				        echo "<a href=\"";
				        echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter='.$filterString );
				        echo "\">$filterItem</a>";
				        if ($count<$total-2)
				        {
				            echo " &gt;&gt; ";
				        }
				    }
				    $count++;
				}

				?>
			</div>



			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="adminForminlist table table-striped">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_( 'ID' ); ?></th>
					<th width="20">
						<!--<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>-->
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					<th width="110">Image</th>
					<th><?php echo JText::_( 'Name' ); ?></th>
					<th></th>
					<th><?php echo JText::_( 'Text' ); ?></th>
					<th><?php echo JText::_( 'Date' ); ?></th>
					<th><?php echo JText::_( 'IP' ); ?></th>
					<th><?php echo JText::_( 'UserID' ); ?></th>
					<th width="50"><?php echo JText::_( 'Filter' ); ?></th>
				</tr>
			</thead>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
			    $row = $this->items[$i];
			    $checked 	= JHTML::_('grid.id',   $i, $row->id );
			    $published =  JHTML::_('jgrid.published', $row->published, $i,'Comment' );
			    $link 		= JRoute::_( 'index.php?option=com_eventgallery&task=editComment&cid[]='. $row->id );

			    ?>
			<tr class="<?php echo "row$k"; ?>">
				<td><a href="<?php echo $link; ?>"><?php echo $row->id; ?></a></td>
				<td><?php echo $checked; ?></td>
				<td><a
					href="<?php echo $link?>">			
					<img class="thumbnail" src="<?php echo JURI::base().("../components/com_eventgallery/helpers/image.php?view=resizeimage&folder=".$row->folder."&file=".$row->file."&option=com_eventgallery&width=100&height=50")?>" />
				</a></td>

				<td><a
					href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
				</td>
				<td><?php echo $published; ?></td>
				<td><a href="<?php echo $link; ?>"><?php echo $row->text; ?></a></td>
				<td><a href="<?php echo $link; ?>"><?php echo JHTML::date($row->date)?></a>
				</td>
				<td><a
					href="<?php echo $link; ?>"><?php echo $row->ip; ?></a>
				</td>
				<td><a
					href="<?php echo $link; ?>"><?php echo $row->user_id; ?></a>
				</td>
				<td>
					<a 
					href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=folder='.$row->folder.';'.$this->filter) ?>">+ Folder</a>
					<br>
					<a 
					href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=file='.$row->file.';folder='.$row->folder.';'.$this->filter) ?>">+ Bild</a>
					<br>
					<a
					href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=name='.$row->name.';'.$this->filter) ?>">+ Name</a>
					<br>
					<a
					href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=ip='.$row->ip.';'.$this->filter) ?>">+ IP</a>
					<br>
					<a
					href="<?php echo JRoute::_( 'index.php?option=com_eventgallery&task=comments&filter=user_id='.$row->user_id.';'.$this->filter) ?>">+ User</a>
				</td>		
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $this->pagination->getListFooter(); ?>
</div>

<input type="hidden" name="option" value="com_eventgallery" /> 
<input type="hidden" name="task" value="comments" />
<input type="hidden" name="boxchecked" value="0" />

</form>
