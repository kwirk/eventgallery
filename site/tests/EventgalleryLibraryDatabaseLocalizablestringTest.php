<?php
/**
 * Created by JetBrains PhpStorm.
 * User: SBluege
 * Date: 26.06.13
 * Time: 19:39
 * To change this template use File | Settings | File Templates.
 */

class EventgalleryLibraryDatabaseLocalizablestringTest extends PHPUnit_Framework_TestCase {

    protected $testdata = array("de_DE"=>'deutsch', "en_US"=>"englisch", "en_GB"=>"britisch");

    protected function setUp()
    {
        parent::setUp();

    }

    public function testFoo() {



        $ls = new EventgalleryLibraryDatabaseLocalizablestring(json_encode($this->testdata));

        $this->assertEquals('deutsch', $ls->get("de_DE"));
        $this->assertEquals('englisch', $ls->get("en_US"));
        $this->assertEquals('britisch', $ls->get("en_GB"));
        $this->assertEquals(null, $ls->get(null));
    }

    public function testGetEncodedString() {


        $ls = new EventgalleryLibraryDatabaseLocalizablestring(json_encode($this->testdata));

        $this->assertEquals($ls->getEncodedString(), json_encode($this->testdata));
    }

    public function testSetGet() {

        $ls = new EventgalleryLibraryDatabaseLocalizablestring(json_encode($this->testdata));

        $ls->set("fe_DE", "foobar");
        $this->assertEquals('foobar', $ls->get("fe_DE"));

        $ls->set("fe_DE", null);
        $ls->set(null, "foobar");

    }

    public function testCheckout() {

        /**
         * @var EventgalleryLibraryManagerCart $cartMgr
         */
        $cartMgr = EventgalleryLibraryManagerCart::getInstance();

        // CREATE
        $cart = $cartMgr->getCart();
        $this->assertEmpty($cart->getLineItems());
        $cart->addItem("test","A_001_2013-03-17_IMG_1294.jpg","1");
        $this->assertNotEmpty($cart->getLineItems());

        /**
         * @var EventgalleryLibraryImagelineitem $lineitem
         */
        // add line item
        $lineitems = array_values($cart->getLineItems());
        $lineitem = $lineitems[0];

        $oldPrice = $lineitem->getPrice();
        $lineitem->setQuantity(10);
        $cartMgr->calculateCart();
        $newPrice = $oldPrice*10;
        $this->assertEquals($newPrice, $cart->getSubTotal());

        // clone line item
        $newLineitem = $cart->cloneLineItem($lineitem->getId());
        $this->assertEquals(2, $cart->getLineItemsCount());
        $this->assertEquals(11, $cart->getLineItemsTotalCount());

        // delete item
        $cart->deleteLineItem($newLineitem->getId());
        $this->assertEquals(1, $cart->getLineItemsCount());
        $this->assertEquals(10, $cart->getLineItemsTotalCount());

        /**
         * @var EventgalleryLibraryManagerShipping $shippingMgr
         */
        $shippingMgr = EventgalleryLibraryManagerShipping::getInstance();
        $cart->setShippingMethod($shippingMgr->getDefaultMethod());
        $this->assertNotEmpty($cart->getShippingMethod());

        /**
         * @var EventgalleryLibraryManagerPayment $paymentMgr
         */
        $paymentMgr = EventgalleryLibraryManagerPayment::getInstance();
        $cart->setPaymentMethod($paymentMgr->getDefaultMethod());
        $this->assertNotEmpty($cart->getPaymentMethod());

        /**
         * @var EventgalleryLibraryFactoryAddress $addressFactory
         */
        $addressFactory = EventgalleryLibraryFactoryAddress::getInstance();
        $data = array (
            "firstname"=>"Peter",
            "lastname"=>"Mustermann",
            "address1"=>"Foostreet",
            "address2"=>"Barstreet",
            "address3"=>"12345678",
            "zip"=>"12345",
            "city"=>"Footown",
            "country"=>"Barland"
        );

        $address = $addressFactory->createStaticAddress($data,'');
        $cart->setBillingAddress($address);
        $cart->setShippingAddress($address);

        /**
         * @var EventgalleryLibraryManagerOrder $orderMgr
         */
        $orderMgr = EventgalleryLibraryManagerOrder::getInstance();
        $order = $orderMgr->createOrder($cart);
        $this->assertEquals(1, $order->getLineItemsCount());
        $this->assertEquals(10, $order->getLineItemsTotalCount());
        $this->assertNotEmpty($order->getShippingMethod());
        $this->assertNotEmpty($order->getPaymentMethod());
        $this->assertNotEmpty($order->getShippingAddress());
        $this->assertNotEmpty($order->getBillingAddress());

        $this->assertEquals('Peter', $order->getBillingAddress()->getFirstName());
        $this->assertEquals('Mustermann', $order->getBillingAddress()->getLastName());
        $this->assertEquals('Foostreet', $order->getBillingAddress()->getAddress1());
        $this->assertEquals('Barstreet', $order->getBillingAddress()->getAddress2());
        $this->assertEquals('12345678', $order->getBillingAddress()->getAddress3());
        $this->assertEquals('12345', $order->getBillingAddress()->getZip());
        $this->assertEquals('Footown', $order->getBillingAddress()->getCity());
        $this->assertEquals('Barland', $order->getBillingAddress()->getCountry());

        // move to history
        $cart->setStatus(1);
        $this->assertEquals(1, $cart->getStatus());

        EventgalleryLibraryManagerAddress::getInstance();
    }

    public function testMethodes() {
        /**
         * @var EventgalleryLibraryManagerCart $cartMgr
         */
        $cartMgr = EventgalleryLibraryManagerCart::getInstance();

        // CREATE
        $cart = $cartMgr->getCart();
        $this->assertEmpty($cart->getLineItems());
        $cart->addItem("test","A_001_2013-03-17_IMG_1294.jpg","1");
        $this->assertNotEmpty($cart->getLineItems());

        /**
         * @var EventgalleryLibraryManagerShipping $shippingMgr
         */
        $shippingMgr = EventgalleryLibraryManagerShipping::getInstance();
        $methods = $shippingMgr->getMethods();
        foreach ($methods as $method) {
            /**
             * @var EventgalleryLibraryInterfaceMethod $method
             */
            $method->isEligible($cart);
        }

        /**
         * @var EventgalleryLibraryManagerPayment $paymentMgr
         */
        $paymentMgr = EventgalleryLibraryManagerPayment::getInstance();
        $methods = $paymentMgr->getMethods();
        foreach ($methods as $method) {
            /**
             * @var EventgalleryLibraryInterfaceMethod $method
             */
            $method->isEligible($cart);
        }

        /**
         * @var EventgalleryLibraryManagerSurcharge $surchargeMgr
         */
        $surchargeMgr = EventgalleryLibraryManagerSurcharge::getInstance();
        $methods = $surchargeMgr->getMethods();
        foreach ($methods as $method) {
            /**
             * @var EventgalleryLibraryInterfaceMethod $method
             */
            $method->isEligible($cart);
        }


    }


}
