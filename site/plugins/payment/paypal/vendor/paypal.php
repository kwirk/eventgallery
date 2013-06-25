<?php

class EventgalleryPluginsPaymentPaypalVendorPaypal {
   /**
    * Last error message(s)
    * @var array
    */
   protected $_errors = array();



   /**
    * API endpoint
    * Live - https://api-3t.paypal.com/nvp
    * Sandbox - https://api-3t.sandbox.paypal.com/nvp
    * @var string
    */
   protected $_endPoint = 'https://svcs.sandbox.paypal.com/AdaptivePayments/Pay';

   /**
    * API Version
    * @var string
    */
   protected $_version = '74.0';


    /**
     * Make API request
     *
     * @param  string     $method
     * @param  string     $endPoint
     * @param  array     $credentials
     * @param array $params
     *
     * @return bool|mixed
     */
    public function request($method, $endPoint, $credentials, $params = array()) {
      $this -> _errors = array();
      if( empty($method) ) { //Check if API method is not empty
         $this -> _errors = array('API method is missing');
         return false;
      }

      $data = json_encode($params);

      //cURL settings
      $curlOptions = array (
         CURLOPT_HTTPHEADER => $credentials,
         CURLOPT_URL => $endPoint,
         CURLOPT_VERBOSE => 1,
         CURLOPT_SSL_VERIFYPEER => false,
         CURLOPT_SSL_VERIFYHOST => 2,
         #CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //CA cert file
         CURLOPT_RETURNTRANSFER => 1,
         CURLOPT_POST => 1,
         CURLOPT_POSTFIELDS => $data
      );

      $ch = curl_init();
      curl_setopt_array($ch,$curlOptions);

      //Sending our request - $response will hold the API response
      $response = curl_exec($ch);

      //Checking for cURL errors
      if (curl_errno($ch)) {
         $this -> _errors = curl_error($ch);
         curl_close($ch);
         return false;
         //Handle errors
      } else  {
         curl_close($ch);
         $responseArray = json_decode($response);
         return $responseArray;
      }
   }
}