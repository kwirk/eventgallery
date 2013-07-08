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

<form action="<?php echo JRoute::_('index.php?option=com_eventgallery&view=orders'); ?>"
      method="post" name="adminForm" id="adminForm">

    <div class="btn-group pull-right hidden-phone">
        <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>

    <table>
        <tbody>
        <?php foreach ($this->items as $i => $item) :
        /**
         * @var EventgalleryLibraryOrder $item;
         */
        ?>

            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $item->getId()); ?>
                </td>
                <td>
                    <a href="<?php echo
                     JRoute::_('index.php?option=com_eventgallery&task=order.edit&id='.$item->getId()); ?>">
                        <?php echo $this->escape($item->getId()); ?></a>

                    <p class="smallsub">
                        <?php echo $this->escape($item->getEMail()) ?><br>
                        <?php echo $this->escape($item->getBillingAddress()->getFirstName()) ?>
                        <?php echo $this->escape($item->getBillingAddress()->getLastName()) ?><br>
                        <?php echo $item->getTotalCurrency() ?>
                        <?php echo $item->getTotal() ?>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />
    <div class="pagination pagination-toolbar">
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>

    </form>