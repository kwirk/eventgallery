<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * @var EventgalleryLibraryOrder $order
 */

$order = $this->order;
?>
<style type="text/css">

    body {
        background-color: #DDD;
    }
    
    #content {
        background-color: white;
        max-width: 700px;
        width: 100%;
        border: 1px solid #EEE;
        padding: 20px;
        padding-top: 40px;
        margin: auto;
    }

    h1 {
        font-size: 1.2em;
    }

    h2 {
        font-size: 1.1em;
    }
    
    table {
        border-spacing: 0px;
    }
    
    table td{
        padding: 10px 10px 10px 0px;
    }
    
    .table-address {
        width: 100%;
    }

    .table-summary {
        width: 100%;
    }
    
    .table-summary td{
        text-align: right;
    }
    
    .table-summary .subtotal td{
        border-top: 1px solid silver;    
    }
    
    .table-summary .total td{
        border-top: 4px double silver;
        font-weight: bold;
    }
    
    .widerruf {
        width: 100%;
        border-top: 1px dashed silver;
        border-bottom: 1px dashed silver;
    }


</style>
<div id="content">
    <p>
        Hallo <?php echo $order->getBillingAddress()->getFirstName(). ' '. $order->getBillingAddress()->getLastName(); ?>!
    </p>

    <p>
        Thank you for placing your order! We received your order at <?php echo JHTML::_('date', $order->getCreationDate()); ?> and we'll 
        process it with the order number <?php echo $order->getDocumentNumber(); ?>. Please find a list of your items below. We'll notice you about the shippment with a separate email. 
        The ship-to address below is the one we'll use to send your order to. Usually this will take 2-3 weeks. 
    </p>

    <h1>Your items</h1>

    <table>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_COUNT'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_PRICE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_IMAGETYPE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_FILE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_THUMBNAIL'); ?></th>

        <?php
        /**
        * @var EventgalleryLibraryImagelineitem $lineitem
        */
        FOREACH ($order->getLineItems() as $lineitem): ?>

            <tr>
                <td>
                    <?php echo $lineitem->getQuantity(); ?>
                </td>
                <td>
                    <?php echo $lineitem->getCurrency().' '.$lineitem->getPrice().' ( '.$lineitem->getCurrency().' '.$lineitem->getSinglePrice() .')';?>
                </td>
                <td>
                    <?php echo $lineitem->getImageType()->getDisplayName(); ?>
                </td>
                <td>
                    <?php echo $lineitem->getFolderName() .'/'. $lineitem->getFileName(); ?>
                </td>
                <td>
                    <a class="thumbnail"    href="<?php echo $lineitem->getFile()->getImageUrl(NULL, NULL, true)?>">
                        <img src="<?php echo $lineitem->getFile()->getThumbUrl(104, 104); ?>">
                    </a>
                </td>
            </tr>

        <?php ENDFOREACH; ?>
    </table>

    <table class="table-summary">
        <tr class="subtotal">
            <td>
                Subtotal
            </td>
            <td>
                <?php echo $order->getSubTotalCurrency().' '. sprintf("%0.2f", $order->getSubTotal()); ?>
            </td>
        </tr>
        <?php IF ($order->getSurcharge() != NULL): ?>
        <tr>
            <td>
                <?php echo $order->getSurcharge()->getDisplayName(); ?>
            </td>
            <td>
                <?php echo $order->getSurcharge()->getCurrency(); ?>
                <?php echo $order->getSurcharge()->getPrice(); ?>
            </td>
        </tr>
        <?php ENDIF ?>

        <?php IF ($order->getShippingMethod() != NULL): ?>
        <tr>
            <td>
                <?php echo $order->getShippingMethod()->getDisplayName(); ?>:
            </td>
            <td>
                <?php echo $order->getShippingMethod()->getCurrency(); ?>
                <?php echo $order->getShippingMethod()->getPrice(); ?>
            </td>
        </tr>
        <?php ENDIF ?>

        <?php IF ($order->getPaymentMethod() != NULL): ?>
        <tr>
            <td>
                <?php echo $order->getPaymentMethod()->getDisplayName(); ?>:
            </td>
            <td>
                <?php echo $order->getPaymentMethod()->getCurrency(); ?>
                <?php echo $order->getPaymentMethod()->getPrice(); ?>
            </td>
        </tr>
        <?php ENDIF ?>


        <tr class="total">
            <td>
                Total:    
            </td>
            <td>
                <?php echo $order->getTotalCurrency().' '.sprintf("%0.2f", $order->getTotal()); ?>
            </td>
        </tr>
        
        <tr class="total">
            <td>
                contains VAT:    
            </td>
            <td>
                <?php echo $order->getTotalCurrency().' '.sprintf("%0.2f", $order->getTax()); ?>
            </td>
        </tr>

        
        
    </table>


    <table class="table-address">
        <tr>
            <td>
                <h2>Billing Address</h2>

                <?php echo $order->getBillingAddress()->getFirstName(); ?> <?php echo $order->getBillingAddress()->getLastName(); ?> <br/>
                <?php echo $order->getBillingAddress()->getAddress1(); ?><br/>
                <?php if (strlen($order->getBillingAddress()->getAddress2())>0) echo $order->getBillingAddress()->getAddress2(); ?><br/>
                <?php if (strlen($order->getBillingAddress()->getAddress3())>0) echo $order->getBillingAddress()->getAddress3(); ?><br/>
                <?php echo $order->getBillingAddress()->getZip(); ?> <?php echo $order->getBillingAddress()    ->getCity(); ?><br/>
                <?php echo $order->getBillingAddress()->getCountry(); ?><br/>



            </td>
            <td>
                <h2>Shipping Address</h2>
                <?php echo $order->getShippingAddress()->getFirstName(); ?> <?php echo $order->getShippingAddress()->getLastName(); ?> <br/>
                <?php echo $order->getShippingAddress()->getAddress1(); ?><br/>
                <?php  if (strlen($order->getShippingAddress()->getAddress3())>0)echo $order->getShippingAddress()->getAddress2(); ?><br/>
                <?php  if (strlen($order->getShippingAddress()->getAddress3())>0)echo $order->getShippingAddress()->getAddress3(); ?><br/>
                <?php echo $order->getShippingAddress()->getZip(); ?> <?php echo $order->getShippingAddress()->getCity(); ?><br/>
                <?php echo $order->getShippingAddress()->getCountry(); ?><br/>   
            </td>
        </tr>
    </table>


    <?php IF (strlen($order->getMessage())>0):?>
    <p><strong>Custom Message</strong></p>
    <p>
        Message: <?php echo $order->getMessage();?>
    </p>
    <?php ENDIF; ?>
    
    
    <div class="widerruf">
        <h2>Widerrufsbelehrung</h2>
        <p>
        <strong>Widerrufsrecht</strong>
        </p>

        <p>
        Sie können Ihre Vertragserklärung innerhalb von 14 Tagen ohne Angabe von Gründen in Textform (z. B. Brief, Fax, E-Mail) oder – wenn Ihnen die Sache vor Fristablauf überlassen wird – auch durch Rücksendung der Sache widerrufen. Die Frist beginnt nach Erhalt dieser Belehrung in Textform, jedoch nicht vor Eingang der Ware beim Empfänger (bei der wiederkehrenden Lieferung gleichartiger Waren nicht vor Eingang der ersten Teillieferung) und auch nicht vor Erfüllung unserer Informationspflichten gemäß Artikel 246 § 2 in Verbindung mit § 1 Absatz. 1 und 2 EGBGB sowie unserer Pflichten gemäß § 312g Absatz. 1 Satz 1 BGB in Verbindung mit Artikel 246 § 3 EGBGB. Zur Wahrung der Widerrufsfrist genügt die rechtzeitige Absendung des Widerrufs oder der Sache.
        </p>
        <p>
        Der Widerruf ist zu richten an:
        </p>
        <p>
        [Name/Firma]    Musterhändler GmbH<br>
        [Angaben zum gesetzlichen Vertreter]    Geschäftsführer: Max Mustermann<br>
        [ladungsfähige Anschrift (kein Postfach!)]  Kommerzallee 1a, 12345 Musterhausen<br>
        [E-Mail-Adresse]    max.mustermann@xyz.de<br>
        [ggf. Faxnummer]    Fax 01234 / 567.890<br>
        [keine Telefonnummer!]<br>
        </p>
        <p>
        <strong>Widerrufsfolgen</strong>
        </p>
        <p>
        Im Falle eines wirksamen Widerrufs sind die beiderseits empfangenen Leistungen zurückzugewähren und ggf. gezogene Nutzungen (z. B. Zinsen) herauszugeben. Können Sie uns die empfangene Leistung sowie Nutzungen (z.B. Gebrauchsvorteile) nicht oder teilweise nicht oder nur in verschlechtertem Zustand zurückgewähren beziehungsweise herausgeben, müssen Sie uns insoweit Wertersatz leisten. Für die Verschlechterung der Sache und für gezogene Nutzungen müssen Sie Wertersatz nur leisten, soweit die Nutzungen oder die Verschlechterung auf einen Umgang mit der Sache zurückzuführen ist, der über die Prüfung der Eigenschaften und der Funktionsweise hinausgeht. 3 Unter "Prüfung der Eigenschaften und der Funktionsweise" versteht man das Testen und Ausprobieren der jeweiligen Ware, wie es etwa im Ladengeschäft möglich und üblich ist.
        </p>
        <p>
        Paketversandfähige Sachen sind auf unsere Gefahr zurückzusenden. Sie haben die regelmäßigen Kosten der Rücksendung zu tragen, wenn die gelieferte Ware der bestellten entspricht und wenn der Preis der zurückzusendenden Sache einen Betrag von 40 Euro nicht übersteigt oder wenn Sie bei einem höheren Preis der Sache zum Zeitpunkt des Widerrufs noch nicht die Gegenleistung oder eine vertraglich vereinbarte Teilzahlung erbracht haben. 4 Anderenfalls ist die Rücksendung für Sie kostenfrei. Nicht paketversandfähige Sachen werden bei Ihnen abgeholt. Verpflichtungen zur Erstattung von Zahlungen müssen innerhalb von 30 Tagen erfüllt werden. Die Frist beginnt für Sie mit der Absendung Ihrer Widerrufserklärung oder der Sache, für uns mit deren Empfang.
        </p>
        <p><strong>
        Ende der Widerrufsbelehrung
        </strong></p>
    </div>
    
    <div class="contact">
        <p>
        Firma <br>
        Name Vorname<br>
        Adresse<br>
        Stadt, PLZ <br />
        </p>
        <p>
        Allgemeine Geschäftsbedingungen (AGB): http://www.foobar.de/agb
        </p>
    </div>
    
</div>

