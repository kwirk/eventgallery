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
<?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif;?>
        <div id="filter-bar" class="btn-toolbar">
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>
        </div>
        <div class="clearfix"> </div>
        <table class="table">
            <thead>
            <tr>
                <th width="20">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="nowrap" width="1%">

                </th>
                <th class="nowrap" width="1%">

                </th>
                <th>
                    <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_STATUS' ); ?>
                </th>
                <th>
                    <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_DETAILS' ); ?>
                </th>

                <th>
                    <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_PRICING' ); ?>
                </th>
            </tr>
            </thead>
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
                        <div class="btn-group">

                            <a class="btn btn-micro" href="<?php echo
                            JRoute::_('index.php?option=com_eventgallery&task=order.edit&id='.$item->getId()); ?>">
                                <i class="icon-edit"></i></a>

                        </div>
                    </td>
                    <td>
                        <?php echo $this->escape($item->getDocumentNumber()); ?>
                    </td>
                    <td>
                        <?php echo JText::_('COM_EVENTGALLERY_ORDERSTATUS_TYPE_ORDER'); ?>:
                        <strong><?php if ($item->getOrderStatus()) echo $item->getOrderStatus()->getDisplayName() ?></strong><br>
                        <?php echo JText::_('COM_EVENTGALLERY_ORDERSTATUS_TYPE_PAYMENT'); ?>:
                        <strong><?php if ($item->getPaymentStatus()) echo $item->getPaymentStatus()->getDisplayName() ?></strong><br>
                        <?php echo JText::_('COM_EVENTGALLERY_ORDERSTATUS_TYPE_SHIPPING'); ?>:
                        <strong><?php if ($item->getShippingStatus()) echo $item->getShippingStatus()->getDisplayName() ?></strong><br>
                    </td>
                    <td>

                        <?php echo JText::sprintf('COM_EVENTGALLERY_ORDERS_COUNT_SUMMARY',$item->getLineItemsTotalCount(), $item->getLineItemsCount()); ?>

                        <p class="smallsub">
                            <?php IF (strlen($item->getEMail())>0):?>
                                <a href="mailto:<?php echo $this->escape($item->getEMail()) ?>"><?php echo $this->escape($item->getEMail()) ?></a><br>
                            <?php ENDIF ?>
                            <?php IF (strlen($item->getPhone())>0):?>
                                <a href="tel:<?php echo $this->escape($item->getPhone()) ?>"><?php echo $this->escape($item->getPhone()) ?></a><br>
                            <?php ENDIF ?>
                            <?php IF ($item->getBillingAddress()): ?>
                                <?php echo $this->escape($item->getBillingAddress()->getFirstName()) ?>
                                <?php echo $this->escape($item->getBillingAddress()->getLastName()) ?><br>
                            <?php ENDIF ?>

                        </p>
                    </td>
                    <td>
                        <?php echo $item->getTotal() ?><br>
                        <small>                        
                            <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_SUBTOTAL' ); ?> <?php echo $item->getSubTotal() ?><br>
                            
                            <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_SURCHARGE' ); ?> 
                            <?php IF ($item->getSurchargeServiceLineItem()): ?>
                                <?php echo $item->getSurchargeServiceLineItem()->getPrice() ?>
                            <?php ELSE: ?>
                                -
                            <?php ENDIF ?><br>

                            <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_PAYMENT' ); ?> 
                            <?php IF ($item->getPaymentMethodServiceLineItem()): ?>
                              <?php echo $item->getPaymentMethodServiceLineItem()->getPrice() ?>
                            <?php ELSE: ?>
                                -
                            <?php ENDIF ?><br>

                            <?php echo JText::_( 'COM_EVENTGALLERY_ORDERS_SHIPPING' ); ?>  
                            <?php IF ($item->getShippingMethodServiceLineItem()): ?>
                                <?php echo $item->getShippingMethodServiceLineItem()->getPrice() ?>
                            <?php ELSE: ?>
                                -                            
                            <?php ENDIF ?>
                        </small>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination pagination-toolbar">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    </div>

    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />

    </form>