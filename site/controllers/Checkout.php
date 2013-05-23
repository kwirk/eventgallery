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
	public function display($cachable = false, $urlparams = false)
	{			
		parent::display($cachable, $urlparams);		
	}

	public function sendOrder() {

		$session = JFactory::getSession();
		$config = JFactory::getConfig();
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$option = $app->input->get('option');

		$cartJson = $session->get("$option.cart","");

		$cart = array();
		if (strlen($cartJson)>0) {
			$cart = json_decode($cartJson, true);
		}
	
		$order = array();

		$overallImageCount = 0;

		foreach($cart as $lineitem){
			$count = JRequest::getString( 'count_'.md5($lineitem['folder'].$lineitem['file']) , 0 );
			if ($count>0) {
				$lineitem['count'] = $count;
				array_push($order, $lineitem);
				$overallImageCount += $count;
			}
				
		}
 
		     
		$sitename	= $config->get('sitename');
		$name    = JRequest::getString( 'name' , $config->get( 'config.fromname' ) );
		$email   = JRequest::getString( 'email' , $config->get( 'config.mailfrom' ) );
		$message = JRequest::getString( 'message' , "null" );
		$subject_message = JRequest::getString( 'subject' , "null" );


		$mailer = JFactory::getMailer();

		
		
		$sender = array( 
		    $email,
		    $name 
		);
 
 		$mailer->setSubject("$sitename - Image Order for $name with $overallImageCount copies of ".count($order)." images");
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
		$body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_FILE').'</th>';
		$body  .= '<th>'.JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_THUMBNAIL').'</th>';

		foreach($order as $lineitem){
			$body  .= '<tr><td>';
			$body  .= $lineitem['count'];
			$body  .= '</td><td>';
			$body  .= '<pre>'.$lineitem['folder'].' / '.$lineitem['file'].'</pre>';
			$body  .= '</td><td>';
			$body  .= $lineitem['imagetag'];
			$body  .= '</td></tr>';				
		}
		$body  .= '</table>';

		$body  .= '<br /><br /><br /><h2>Short Summary</h2><br /><pre>';
		foreach($order as $lineitem){
			$body  .= $lineitem['count']."\t\t";
			$body  .= $lineitem['folder'].' / '.$lineitem['file']."\n";
		}
		$body  .= '</pre>';

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);

		$send = $mailer->Send();

		if ( $send !== true ) {
		    $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_FAILED').' ('. $mailer->ErrorInfo.')';
		    $this->setRedirect(JRoute::_("index.php?view=checkout"),$msg);
		}  else {
			$msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_STORED');
			$session->set( "$option.cart", "" );
			$this->setRedirect(JRoute::_("index.php?"),$msg,'info');
		}

		

	}


}
