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

class EventgalleryPluginsPaymentPaypalPayment extends  EventgalleryLibraryMethodsPayment
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

    static public  function getClassName() {
        return "Payment: PayPal";
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
                "ipnNotificationUrl"=> JRoute::_(JURI::base().'index.php?option=com_eventgallery&view=checkout&task=processPayment&paymentmethodid='.$this->getId().'&orderid='.$lineitemcontainer->getId().'&paypal=ipn'),
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

    public function onIncomingExternalRequest() {
       // verify response
        $endPoint = $this->getData()->endpoints->webscr;


        // STEP 1: Read POST data

        // reading posted data from directly from $_POST causes serialization
        // issues with array data in POST
        // reading raw POST data from input stream instead.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($myPost as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }


        // STEP 2: Post IPN data back to paypal to validate

        $ch = curl_init($endPoint);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // In wamp like environments that do not come bundled with root authority certificates,
        // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
        // of the certificate as shown below.
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if( !($res = curl_exec($ch)) ) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);

        }
        curl_close($ch);


        // STEP 3: Inspect IPN validation result and act accordingly

        if (strcmp ($res, "VERIFIED") == 0) {
            // check whether the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your Primary PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment

            // assign posted variables to local variables

            if ($myPost['status']=="COMPLETED") {
                $orderid = JRequest::getString("orderid");

                $order = new EventgalleryLibraryOrder($orderid);



                $order->setPaymentStatus(new EventgalleryLibraryOrderstatus(EventgalleryLibraryOrderstatus::TYPE_PAYMENT_PAYED));
                $order->getPaymentMethodServiceLineItem()->setData('ipn_response', JRequest::getString('ipn', json_encode($myPost)));
            }


            // <---- HERE you can do your INSERT to the database

        } else if (strcmp ($res, "INVALID") == 0) {
            // log for manual investigation
        }


    }
}
