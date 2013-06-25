<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class EventgalleryPluginsPaymentPaypalPayment extends  EventgalleryLibraryMethodesPayment
{


    /**
     * Returns if this method can be used with the current cart.
     *
     * @param EventgalleryLibraryLineitemcontainer $cart
     *
     * @return bool
     */
    public function isEligible($cart)
    {
       return true;
    }

    protected function _getCredentials() {

        return array(
            'X-PAYPAL-SECURITY-USERID:'.$this->getData()->credentials->userid,
            'X-PAYPAL-SECURITY-PASSWORD:'.$this->getData()->credentials->password,
            'X-PAYPAL-SECURITY-SIGNATURE:'.$this->getData()->credentials->signature,
            'X-PAYPAL-APPLICATION-ID:'.$this->getData()->credentials->appid,
            'X-PAYPAL-REQUEST-DATA-FORMAT:JSON',
            'X-PAYPAL-RESPONSE-DATA-FORMAT:JSON'
        );

    }

    /**
     * @param EventgalleryLibraryLineitemcontainer $lineitemcontainer
     *
     * @return bool|void
     */

    public function processOnOrderSubmit($lineitemcontainer) {


        $endPoint = $this->getData()->endpoints->api;

        $credentials = $this->_getCredentials();

        $requestParams = array (

                'actionType' => 'PAY',
                'currencyCode' => $lineitemcontainer->getTotalCurrency(),
                "receiverList" => array(
                    "receiver"=>array(array(
                        'amount' => $lineitemcontainer->getTotal(),
                        'email'=> $this->getData()->receiver->email
                    ))
                ),
                "returnUrl"=>JRoute::_(JURI::base().'index.php?option=com_eventgallery&view=checkout&task=confirm'),
                "cancelUrl"=>JRoute::_(JURI::base().'index.php?option=com_eventgallery&view=cart'),
                "ipnNotificationUrl"=> JRoute::_(JURI::base().'index.php?option=com_eventgallery&view=checkout&task=processPayment&paymentmethodid='.$this->getId().'&orderid='.$lineitemcontainer->getId().'&payed=true'),
                "requestEnvelope" => array(
                    "errorLanguage"=>"en_US",
                    "detailLevel"=>"ReturnAll"
                )
            );


        $paypal = new EventgalleryPluginsPaymentPaypalVendorPaypal();
        $response = $paypal -> request('Pay', $endPoint, $credentials,
            $requestParams
        );

        if( $response instanceof stdClass && $response->responseEnvelope->ack == 'Success') {
            $app = JFactory::getApplication();
            $app->redirect($this->getData()->endpoints->webscr.'?cmd=_ap-payment&paykey='.$response->payKey);
            return true;
        } else {
            return new Exception("payment failed");
        }
    }
}
