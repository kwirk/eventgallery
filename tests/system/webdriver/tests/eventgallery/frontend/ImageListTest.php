<?php

require_once 'JoomlaWebdriverTestCase.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;

// creates, publishes and deletes an event

class FrontEndTest001 extends JoomlaWebdriverTestCase
{

	private $eventPageTypes = array(

			"imagelist" => array("menuName"=>"Image List "),
			"ajaxlist" => array("menuName"=>"Ajax List "),
			"pageablelist" => array("menuName"=>"Pageable List "),


		);

	private $doInit = true;
	private $doCleanup = false;

	// Events page 
	protected $esp = null;
	protected $salt = null;

	public function setUp()
	{		
		parent::setUp();
		$this->salt = rand();
		$this->salt = 4447;
		if ($this->doInit) {
			try {
				$this->cpPage = $this->doAdminLogin();
				$this->cpPage->clickMenuByUrl('com_eventgallery','EventsPage');
				$this->esp = $this->getPageObject('EventsPage');

				$this->esp->createEvent('event'.$this->salt);	
				$this->esp->publishEvent('event'.$this->salt);
				$this->esp->uploadFiles('event'.$this->salt);

				$this->createMenuItem($this->eventPageTypes['imagelist']['menuName'].$this->salt, 		'Events - List', 'Event - Image List');
				$this->createMenuItem($this->eventPageTypes['pageablelist']['menuName'].$this->salt, 	'Events - List', 'Event - Pageable List');
				$this->createMenuItem($this->eventPageTypes['ajaxlist']['menuName'].$this->salt, 		'Events - List', 'Event - Ajax List');

				$this->createMenuItem("Checkout ".$this->salt, 		'Checkout');
				$this->createMenuItem("Cart ".$this->salt, 		    'Cart');

				$this->doAdminLogout();
			} catch (Exception $e) {
					
			}
		}

		//open site
		$url = $this->cfg->host . $this->cfg->path . 'index.php';
		$this->driver->get($url);	

	}

	public function tearDown()
	{
		if ($this->doCleanup) {

			$this->cpPage = $this->doAdminLogin();
			$this->cpPage->clickMenuByUrl('com_eventgallery','EventsPage');
			$this->esp = $this->getPageObject('EventsPage');

			$this->deleteMenuItem($this->eventPageTypes['imagelist']['menuName'].$this->salt); 	
			$this->deleteMenuItem($this->eventPageTypes['pageablelist']['menuName'].$this->salt);
			$this->deleteMenuItem($this->eventPageTypes['ajaxlist']['menuName'].$this->salt);
			$this->deleteMenuItem("Checkout ".$this->salt);
			$this->deleteMenuItem("Cart ".$this->salt);

			$this->cpPage->clickMenuByUrl('com_eventgallery','EventsPage');
			$this->esp = $this->getPageObject('EventsPage');
			$this->esp->deleteEvent('event'.$this->salt);

			$this->doAdminLogout();		
		}
		parent::tearDown();


	}

	/**
	 * @test
	 */
	public function doZooming() {
		foreach($this->eventPageTypes as $type=>$config) {
			$this->driver->findElement(By::xPath("//a[text()=\"".$this->eventPageTypes[$type]['menuName'].$this->salt."\"]"))->click();
			$eventsPage = $this->getPageObject('FrontEndEventsPage',30);
			$eventPage = $eventsPage->openEvent();
			$eventPage->zoomImage();
		}
	}

	/**
	* @test
	*/
	public function doCarting() {
		foreach($this->eventPageTypes as $type=>$config) {
			$this->driver->findElement(By::xPath("//a[text()=\"".$this->eventPageTypes[$type]['menuName'].$this->salt."\"]"))->click();
			$eventsPage = $this->getPageObject('FrontEndEventsPage',30);
			$eventPage = $eventsPage->openEvent();
			$eventPage->add2cart();
			$eventPage->removeFromCart();
		}
	}

	/**
	* @test
	*/
	public function doTestCart() {

		
		$this->driver->findElement(By::xPath("//a[text()=\"".$this->eventPageTypes['imagelist']['menuName'].$this->salt."\"]"))->click();
		$eventsPage = $this->getPageObject('FrontEndEventsPage',30);
		$eventPage = $eventsPage->openEvent();
		$eventPage->add2cart();
		$eventPage->add2cart();
		$eventPage->add2cart();

		$eventPage->removeFromCart();
		$eventPage->removeFromCart();

	}

	/**
	* @tes
	*/
	public function doTestCheckout() {
		
	}

 
	/**
	* creates an menu entry. For this modifications to MenuItemEditPage was necessary
	* - added new entry to menuItemTypes array: array('group' => 'Eventgallery', 'type' => 'Events - List'),
	* - changed setMenuItemType to wait for the content of the iFrame to appear before clicking it.
	*/
	protected function createMenuItem($title="Eventgallery", $menuType="Events - List", $layout=null, $menuLocation = 'Top') {


		$this->menuItemsManagerPage = $this->cpPage->clickMenu('Main Menu', 'MenuItemsManagerPage');

		$this->menuItemsManagerPage->setFilter('menutype', $menuLocation);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($title), 'Test menu should not be present');

		if (null != $layout) {
			$this->menuItemsManagerPage->addMenuItem($title, $menuType, $menuLocation, array('Layout' => $layout));	
		} else {
			$this->menuItemsManagerPage->addMenuItem($title, $menuType, $menuLocation);	
		}		

		$message = $this->menuItemsManagerPage->getAlertMessage();
		$this->assertContains('Menu item successfully saved', $message, 'Menu save should return success', true);
		$this->menuItemsManagerPage->searchFor($title);
		$this->assertTrue($this->menuItemsManagerPage->getRowNumber($title) > 0, 'Test menu should be on the page');		
	}

	/**
	* deletes a menu item by its title
	*/
	protected function deleteMenuItem($title, $menuLocation='Top') {
		$this->menuItemsManagerPage = $this->cpPage->clickMenu('Main Menu', 'MenuItemsManagerPage');
		$this->menuItemsManagerPage->setFilter('menutype', $menuLocation);
		$this->menuItemsManagerPage->deleteItem($title);		
	}


	
}