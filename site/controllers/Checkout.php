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
	public function display($cachable = false, $urlparams  = array())
	{
		parent::display($cachable, $urlparams);		
	}

    /**
     * just sets the layout for the confirm page
     *
     * @param bool $cachable
     * @param array $urlparams
     */
    public function confirm($cachable = false, $urlparams  = array()) {
        JRequest::setVar('layout', 'confirm');
        $this->display($cachable, $urlparams);
    }

    /**
     * Just sets the layout for the change page
     *
     * @param bool $cachable
     * @param array $urlparams
     */
    public function change($cachable = false, $urlparams  = array()) {
        JRequest::setVar('layout', 'change');
        $this->display($cachable, $urlparams);
 	}

    public function saveChanges($cachable = false, $urlparams  = array()) {

        JRequest::checkToken();

        $errors = EventgalleryLibraryManagerCart::getInstance()->updateCart();
        if (isset($errors['formerrors']) && count($errors['formerrors'])>0) {
            $msg = "";
            $app = JFactory::getApplication();

            /**
             * @var Exception $error
             */
            foreach ($errors['formerrors'] as $error) {
                $this->setMessage($msg);
                $app->enqueueMessage($error->getMessage(), 'error');
            }

            $this->change($cachable, $urlparams);
        }
        else {
            $continue = JRequest::getString( 'continue' , null );

            $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_CHANGES_STORED');
            if ($continue == null) {
                $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout&task=change"),$msg,'info');
            } else {
                $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout"),$msg,'info');
            }
        }
    }


	public function createOrder() {

        // switch to the change page.
        $continue = JRequest::getString( 'continue' , null );

        if ($continue==null) {
            $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout&task=change"));
            return;
        }

		$config = JFactory::getConfig();
		// Check for request forgeries.
        JRequest::checkToken();

        EventgalleryLibraryManagerCart::getInstance()->updateCart();

		/* @var EventgalleryLibraryCart $cart */
        $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();


		$overallImageCount = $cart->getLineItemsTotalCount();




 		/* create order*/
        $orderMgr = new EventgalleryLibraryManagerOrder();

        $order = $orderMgr->createOrder($cart);
        $lineitems = $order->getLineItems();

 		/* send mail */
		     
		$sitename	= $config->get('sitename');
		$name    = JRequest::getString( 'name' , $config->get( 'config.fromname' ) );
		$email   = JRequest::getString( 'email' , $config->get( 'config.mailfrom' ) );
		$message = JRequest::getString( 'message' , "null" );
		$subject_message = JRequest::getString( 'subject' , "null" );

        $order->setMessage($message);
        $order->setEMail($email);

		$mailer = JFactory::getMailer();		
		
		$sender = array( 
		    $email,
		    $name 
		);
 
 		$mailer->setSubject("$sitename - Image Order for $name with $overallImageCount copies of ".count($lineitems)." images");
		$mailer->setSender($sender);	

		$params = JComponentHelper::getParams('com_eventgallery');	
		$mailer->addRecipient($params->get('adminmail'));

		$body   = '<h1>User</h1>';		
		$body  .= "From: $name<br>";
		$body  .= "EMail: $email<br>";
		$body  .= "Subject: $subject_message<br>";
		$body  .= "Message: $message<br><br><br>";
		$body  .= '<h1>Items</h1>';


		$body  .= '<table>';
		$body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_COUNT').'</th>';
        $body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_PRICE').'</th>';
        $body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_IMAGETYPE').'</th>';
		$body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_FILE').'</th>';
		$body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_THUMBNAIL').'</th>';

        /**
         * @var EventgalleryLibraryLineitem $lineitem
         */
        foreach($lineitems as $lineitem){

			$body  .= '<tr><td>';
			$body  .= $lineitem->getQuantity();
            $body  .= '</td><td>';
            $body  .= $lineitem->getCurrency().' '.$lineitem->getPrice();
            $body  .= ' ( '.$lineitem->getCurrency().' '.$lineitem->getPrice().' )';
			$body  .= '</td><td>';
            $body  .= $lineitem->getImageType()->getDisplayName();
            $body  .= '</td><td>';
			$body  .= '<pre>'.$lineitem->getFolderName().' / '.$lineitem->getFileName().'</pre>';
			$body  .= '</td><td>';



    		$imagetag = '<a class="thumbnail" 
    						href="'.$lineitem->getFile()->getImageUrl(null, null, true).'"
    						> '.$lineitem->getFile()->getThumbImgTag(100,100).'</a>';

			$body  .= $imagetag;
			$body  .= '</td></tr>';				
		
		}
		$body  .= '</table>';

        $body .= '<strong>Subtotal: '.$order->getSubTotalCurrency().' '.sprintf("%0.2f",$order->getSubTotal()).'</strong>';
        $body .= "<br>";
        $body .= "<br>";
        $body .= '<strong>Total: '.$order->getTotalCurrency().' '.sprintf("%0.2f",$order->getTotal()).'</strong>';
        $body .= "<br>";
        $body .= "<br>";

		$body  .= '<br /><br /><br /><h2>Short Summary</h2><br /><pre>';
		foreach($lineitems as $lineitem){
			$body  .= $lineitem->getQuantity()."\t\t";
            $body  .= $lineitem->getImageType()->getDisplayName()."\t\t";
			$body  .= $lineitem->getFolderName().' / '.$lineitem->getFileName()."\n";
		}
		$body  .= '</pre>';

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);

		$send = $mailer->Send();

		if ( $send !== true ) {
		    $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_FAILED').' ('. $mailer->ErrorInfo.')';
		}  else {
			$msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_STORED');
		}

        $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout&task=confirm"),$msg,'info');

		

	}


}
