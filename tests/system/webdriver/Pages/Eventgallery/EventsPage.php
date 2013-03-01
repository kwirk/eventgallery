<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end login screen
 *
 */
class EventsPage extends AdminPage
{

	protected $waitForXpath =  "//ul/li/a[@href='index.php?option=com_eventgallery']";
	protected $url = 'administrator/index.php?option=com_eventgallery';

	public function createEvent($event) {

		$this->driver->findElement(By::xPath("//button[@onclick=\"Joomla.submitbutton('newFolder')\"]"))->click();

		$folder = $this->driver->waitForElementUntilIsPresent(By::id("folder"), 5);

		$folder->click();
		$folder->clear();
		$folder->sendKeys($event);

		$description = $this->driver->findElement(By::id("description"))
		$description->click();
		$description->clear();
		$description->sendKeys($event);

		$this->driver->findElement(By::cssSelector("button.btn.btn-small"))->click();

		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath), 5);

		$message = $this->getAlertMessage();
		$this->test->assertTrue(strpos($message, 'Event saved!') >= 0, 'Event save return success');

	} 

	public function updateEvent($event) {
		
	}

	public function deleteEvent($event) {

	}

}