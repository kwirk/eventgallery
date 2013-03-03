<?php

require_once 'JoomlaWebdriverTestCase.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;

class EventTest extends JoomlaWebdriverTestCase
{



	public function setUp()
	{
		//
		parent::setUp();
	
	}

	public function tearDown()
	{
		//
		parent::tearDown();
	}
	/**
	 * @test
	 */
	public function event_CreateEvent()
	{		
			
		$this->doAdminLogin();
		$d = $this->driver;
		//get url
		
		$d->get($this->testUrl.'administrator/index.php?option=com_eventgallery');
		$esp = $this->getPageObject('EventsPage');
		

		$salt = "test".md5(rand());
		$esp->createEvent($salt);	
		$esp->publishEvent($salt);
		$esp->deleteEvent($salt);

		//$this->doAdminLogout();
	}

	
}