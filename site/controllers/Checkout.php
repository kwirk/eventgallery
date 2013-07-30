<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class CheckoutController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        parent::display(false, $urlparams);
    }

    /**
     * @param EventgalleryLibraryOrder $order
     *
     * @return mixed|string
     */
    protected  function _sendOrderConfirmationMail($order) {

        $config = JFactory::getConfig();
        $params = JComponentHelper::getParams('com_eventgallery');

        $sitename = $config->get('sitename');

        $view = $this->getView('Mail', 'html', 'EventgalleryView', array('layout'=>'confirm'));
        $view->set('order', $order);
        $view->set('params', $params);
        $body = $view->loadTemplate();

        $mailer = JFactory::getMailer();

        $sender = array(
            $params->get('adminmail'),
            $sitename
        );

        $mailer->setSubject(
            "$sitename - Image Order for ".$order->getBillingAddress()->getFirstName().' '.$order->getBillingAddress()->getLastName().' with '.$order->getLineItemsTotalCount().' copies of ' . $order->getLineItemsCount() . " images"
        );

        $mailer->setSender($sender);

        $mailer->addBCC($params->get('adminmail'));
        $mailer->addRecipient($order->getEMail(), $order->getBillingAddress()->getFirstName().' '.$order->getBillingAddress()->getLastName());

        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($body);

        $send = $mailer->Send();

        if ($send !== true) {
            return $mailer->ErrorInfo;
        }

        return $send;


    }

    /**
     * just sets the layout for the confirm page
     *
     * @param bool  $cachable
     * @param array $urlparams
     */
    public function confirm($cachable = false, $urlparams = array())
    {
        JRequest::setVar('layout', 'confirm');
        $this->display($cachable, $urlparams);
    }

    /**
     * Just sets the layout for the change page
     *
     * @param bool  $cachable
     * @param array $urlparams
     */
    public function change($cachable = false, $urlparams = array())
    {
        JRequest::setVar('layout', 'change');
        $this->display($cachable, $urlparams);
    }

    public function saveChanges($cachable = false, $urlparams = array())
    {

        /* @var EventgalleryLibraryManagerCart $cartMgr */
        $cartMgr = EventgalleryLibraryManagerCart::getInstance();

        JRequest::checkToken();
        $errors = array();
        $errors = array_merge($errors, $cartMgr->updateShippingMethod());
        $errors = array_merge($errors, $cartMgr->updatePaymentMethod());
        $errors = array_merge($errors, $cartMgr->updateAddresses());
        $cartMgr->calculateCart();

        if (count($errors) > 0) {
            $msg = "";
            $app = JFactory::getApplication();

            /**
             * @var Exception $error
             */
            foreach ($errors as $error) {
                $this->setMessage($msg);
                $app->enqueueMessage($error->getMessage(), 'error');
            }

            $this->change($cachable, $urlparams);
        } else {
            $continue = JRequest::getString('continue', null);

            $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_CHANGES_STORED');
            if ($continue == null) {
                $this->setRedirect(
                    JRoute::_("index.php?option=com_eventgallery&view=checkout&task=change"), $msg, 'info'
                );
            } else {
                $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout"), $msg, 'info');
            }
        }
    }


    public function createOrder()
    {

        // switch to the change page.
        $continue = JRequest::getString('continue', null);

        if ($continue == null) {
            $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout&task=change"));
            return;
        }


        // Check for request forgeries.
        JRequest::checkToken();

        /* @var EventgalleryLibraryManagerCart $cartMgr */
        $cartMgr = EventgalleryLibraryManagerCart::getInstance();

        $cartMgr->calculateCart();

        $cart = $cartMgr->getCart();


        /* create order*/
        $orderMgr = new EventgalleryLibraryManagerOrder();

        #$order = $cart;
        $order = $orderMgr->createOrder($cart);

        /* send mail */
        $send = $this->_sendOrderConfirmationMail($order);

        $orderMgr->processOnOrderSubmit($order);



        if ($send !== true) {
            $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_FAILED') . ' (' . $send . ')';
        } else {
            $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_STORED');
        }

        $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout&task=confirm"), $msg, 'info');

    }

    public function processPayment() {
       $methodid = JRequest::getString("paymentmethodid",null);
        /**
         * @var EventgalleryLibraryManagerPayment $methodMgr
         */

        $methodMgr = EventgalleryLibraryManagerPayment::getInstance();
        $method = $methodMgr->getMethod($methodid, false);
        if ($method != null) {
            $method->onIncomingExternalRequest();
        }


    }



}
