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



        $endPoint = $this->getData()->options->productionmode==1?'https://svcs.paypal.com/AdaptivePayments/Pay':'https://svcs.sandbox.paypal.com/AdaptivePayments/Pay';

        $credentials = $this->_getCredentials();

        $requestParams = array (

                'actionType' => 'PAY',
                'currencyCode' => $lineitemcontainer->getTotal()->getCurrencyCode(),
                "receiverList" => array(
                    "receiver"=>array(array(
                        'amount' => $lineitemcontainer->getTotal()->getAmount(),
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
        $response = $paypal->request('Pay', $endPoint, $credentials,
            $requestParams
        );

        if( $response instanceof stdClass && $response->responseEnvelope->ack == 'Success') {
            $app = JFactory::getApplication();
            $webscr = $this->getData()->options->productionmode==1?'https://www.paypal.com/cgi-bin/webscr':'https://www.sandbox.paypal.com/cgi-bin/webscr';
            $app->redirect($webscr.'?cmd=_ap-payment&paykey='.$response->payKey);
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

    public function onPrepareAdminForm($form) {

        /**
         * add the language files
         */

        $language = JFactory::getLanguage();
        $language->load('com_eventgallery' , __DIR__ , $language->getTag(), true);

        /**
         * disable the default data field
         */
        $form->setFieldAttribute('data', 'required', 'false');
        $form->setFieldAttribute('data', 'disabled', 'true');

        $field = new SimpleXMLElement('
            <fieldset name="paypal" label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_LABEL" description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_DESC">
                <field name="paypal_receiver_email"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_RECEIVER_EMAIL_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_RECEIVER_EMAIL_DESC"
                   required="true"                   
                   class="input-xlarge"
                />
                <field name="paypal_credentials_userid"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_USERID_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_USERID_DESC"
                   required="true"                   
                   class="input-xlarge"
                />
                <field name="paypal_credentials_password"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_PASSWORD_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_PASSWORD_DESC"
                   required="true"                   
                   class="input-xlarge"
                />
                <field name="paypal_credentials_signature"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_SIGNATURE_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_SIGNATURE_DESC"
                   required="true"                   
                   class="input-xlarge"
                />
                <field name="paypal_credentials_appid"
                   type="text"
                   label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_APPID_LABEL"
                   description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_CREDENTIALS_APPID_DESC"
                   required="true"                   
                   class="input-xlarge"
                />
                <field name="paypal_options_productionmode" type="radio" class="btn-group" default="0" label="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_PRODUCTIONMODE_LABEL" description="COM_EVENTGALLERY_PLUGINS_PAYMENT_PAYPAL_PRODUCTIONMODE_DESC">
                    <option value="1">JYES</option>
                    <option value="0">JNO</option>
                </field>
            </fieldset>
        ');
        $form->setField($field);

        if (isset($this->getData()->receiver->email)) {         $form->setValue("paypal_receiver_email", null, $this->getData()->receiver->email); }
        if (isset($this->getData()->credentials->userid)) {     $form->setValue("paypal_credentials_userid", null, $this->getData()->credentials->userid); }
        if (isset($this->getData()->credentials->password)) {   $form->setValue("paypal_credentials_password", null, $this->getData()->credentials->password); }
        if (isset($this->getData()->credentials->signature)) {  $form->setValue("paypal_credentials_signature", null, $this->getData()->credentials->signature); }
        if (isset($this->getData()->credentials->appid)) {      $form->setValue("paypal_credentials_appid", null, $this->getData()->credentials->appid); }
        if (isset($this->getData()->options->productionmode)) { $form->setValue("paypal_options_productionmode", null, $this->getData()->options->productionmode); }

        return $form;
    }    

    public function onSaveAdminForm($data) {

        $object = new stdClass();

        $object->receiver = array("email"=>$data['paypal_receiver_email']);
        $object->credentials = array(
            "userid"=>$data['paypal_credentials_userid'],
            "password"=>$data['paypal_credentials_password'],
            "signature"=>$data['paypal_credentials_signature'],
            "appid"=>$data['paypal_credentials_appid'],
        );
        $object->options = array("productionmode"=>$data['paypal_options_productionmode']);

        $this->setData($object);

        return true;
    }
}
