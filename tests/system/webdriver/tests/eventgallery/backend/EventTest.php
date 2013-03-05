<?php

require_once 'JoomlaWebdriverTestCase.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;

// creates, publishes and deletes an event

class EventTest001 extends JoomlaWebdriverTestCase
{

	// Events page 
	protected $esp = null;
	protected $cpPage = null;
 	

	public function setUp()
	{
		parent::setUp();
		$this->cpPage = $this->doAdminLogin();
		$this->cpPage->clickMenuByUrl('com_eventgallery','EventsPage');
		$this->esp = $this->getPageObject('EventsPage');
	}

	public function tearDown()
	{
		$this->doAdminLogout();		
		parent::tearDown();


	}
	/**
	 * @test
	 */
	public function event_CreateEvent()
	{		
		$salt = "test".rand();

		$this->esp->createEvent($salt);	

		$this->esp->publishEvent($salt);

		$this->esp->uploadFiles($salt);

		$this->esp->deleteEvent($salt);
 

	}
	
}