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


	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
		$cpPage->clickMenuByUrl('com_eventgallery','EventsPage');
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
		$salt = "test".md5(rand());

		$this->esp->createEvent($salt);	

		$this->esp->publishEvent($salt);

		$uploadPage = $this->esp->gotoUploadPage($salt);
		$uploadPage->uploadFiles($salt);
		$this->esp->clickMenuByUrl('com_eventgallery','EventsPage');

		$this->esp->deleteEvent($salt);
 

	}

	
}