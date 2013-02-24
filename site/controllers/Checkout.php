<?php

defined('_JEXEC') or die;

class CheckoutController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{			
		parent::display($cachable, $urlparams);		
	}

	public function sendOrder() {


		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app =& JFactory::getApplication();
		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$option = $app->input->get('option');

		$cartJson = $app->getUserState("$option.cart","");

		$cart = array();
		if (strlen($cartJson)>0) {
			$cart = json_decode($cartJson, true);
		}
	
		$order = array();

		foreach($cart as $lineitem){
			$count = $this->input->getString( 'count_'.md5($lineitem['folder'].$lineitem['file']) , 0 );
			if ($count>0) {
				$lineitem['count'] = $count;
				array_push($order, $lineitem);
			}
				
		}


		$name    = $this->input->getString( 'file' , null );
		$email   = $this->input->getString( 'email' , null );
		$message = $this->input->getString( 'message' , null );


		$mailer =& JFactory::getMailer();

		$config =& JFactory::getConfig();
		
		$sender = array( 
		    $config->get( 'config.mailfrom' ),
		    $config->get( 'config.fromname' ) 
		);
 
 		$mailer->setSubject('Eventgallery: Image Order');
		$mailer->setSender($sender);		
		$mailer->addRecipient("svenbluege@gmail.com");

		$body   = '<h1>User</h1>';		
		$body  .= "From: $name<br>";
		$body  .= "EMail: $email<br>";
		$body  .= "Message: $message<br><br><br>";
		$body  .= '<h1>Items</h1>';


		$body  .= '<ul>';
		foreach($order as $lineitem){
			$body  .= '<li>';
			$body  .= $lineitem['count'].'x <pre>'.$lineitem['folder'].' / '.$lineitem['file'].'</pre><br>'.$lineitem['imagetag'];
			$body  .= '</li>';				
		}
		$body  .= '</ul>';

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);

		$send =& $mailer->Send();

		

		if ( $send !== true ) {
		    $msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_FAILED').' ('. $mailer->ErrorInfo.')';
		    $this->setRedirect(JRoute::_("index.php?view=checkout"),$msg);
		}  else {
			$msg = JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_STORED');
			$app->setUserState( "$option.cart", "" );
			$this->setRedirect(JRoute::_("index.php?"),$msg);
		}

		

	}


}
