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


?>

<form action="<?php echo JRoute::_('index.php?option=com_eventgallery&view=imagetypesets'); ?>"
      method="post" name="adminForm" id="adminForm">

    <div class="btn-group pull-right hidden-phone">
        <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="20">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="nowrap" width="1%">
                    
                </th>
                <th>
                    <?php echo JText::_( 'COM_EVENTGALLERY_IMAGETYPESET_NAME' ); ?>
                </th>
                <th>
                    <?php echo JText::_( 'COM_EVENTGALLERY_IMAGETYPESET_IMAGETYPES' ); ?>
                </th>
            </tr>           
        </thead>
        <tbody>
        <?php foreach ($this->items as $i => $item) :
        /**
         * @var EventgalleryLibraryImagetypeset $item;
         */
        ?>

            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $item->getId()); ?>
                </td>
                 <td>
                    <div class="btn-group">
                        <?php echo JHtml::_('jgrid.published', $item->isPublished(), $i, 'imagetypesets.'); ?>
                        <?php IF ($item->isDefault()): ?>
                            <a href="#" class="btn btn-micro active"><i class="icon-star"></i></a>
                        <?php ELSE:?>
                            <a href="#" onclick="return listItemTask('cb<?php echo $i; ?>','imagetypesets.default')" class="btn btn-micro"><i class="icon-star-empty"></i></a>
                        <?php ENDIF ?>
                        <a class="btn btn-micro" href="<?php echo
                            JRoute::_('index.php?option=com_eventgallery&task=imagetypeset.edit&id='.$item->getId()); ?>">
                        <i class="icon-edit"></i></a>
                    </div>
                </td>
                <td>                    
                    <?php echo $this->escape($item->getName()) ?><br />
                    <small><?php echo $this->escape($item->getNote()) ?></small> 
                    
                </td>
                <td>
                    <?php FOREACH($item->getImageTypes() as $imagetype):?>
                        <a href="<?php echo JRoute::_('index.php?option=com_eventgallery&task=imagetype.edit&id='.$imagetype->getId()); ?>">
                            <?php echo $imagetype->getName() ?>                            
                        </a>
                        <?php IF ($imagetype->getId()==$item->getDefaultImageType()->getId()):?>
                            <i class="icon-star"></i>
                        <?php ENDIF ?>
                        (<?php echo $imagetype->getPrice() ?>
                        <?php echo $imagetype->getCurrency() ?>)

                        <br>
                    <?php ENDFOREACH ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />

    <div class="pagination pagination-toolbar">
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>

</form>