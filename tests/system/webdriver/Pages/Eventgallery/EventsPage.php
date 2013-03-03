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

		$description = $this->driver->findElement(By::id("description"));
		$description->click();
		$description->clear();
		$description->sendKeys($event);

		$this->driver->findElement(By::cssSelector("button.btn.btn-small"))->click();

		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath), 5);

		$message = $this->getAlertMessage();
		$this->test->assertTrue(strpos($message, 'Event saved!') >= 0, 'Event save return success');

		

	} 

	public function publishEvent($event) {
		$id = $this->getLineId($event);
		
		$this->driver->findElement(By::xPath("//a[@onclick=\"return listItemTask('$id','publish')\"]"))->click();

		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath), 5);
		
		$element = $this->driver->findElement(By::xPath("//a[@onclick=\"return listItemTask('$id','unpublish')\"]"));
		$this->test->assertTrue(strpos($element->getAttribute('title'), 'published!') >= 0, 'Event should be published');
	}

	public function updateEvent($event) {

		
	}

	public function deleteEvent($event) {				
		$id = $this->getLineId($event);
		$this->driver->findElement(By::id($id))->click();
		$this->driver->findElement(By::xPath("//button[@onclick=\"if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (confirm('Remove all selected Events?')){Joomla.submitbutton('removeEvent');}}\"]"))->click();
		$this->driver->acceptAlert();
		$message = $this->getAlertMessage();
		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath), 5);
		$this->test->assertTrue(strpos($message, 'Event(s) Deleted') >= 0, 'Event deletion should return success');		
	}

	protected function getLineId($event) {

		$element = $this->driver->findElement(By::xPath('//a[text()="'.$event.'"]'));
		$href = $element->getAttribute("href");
		$pos = strpos($href,"cid[]=");
		$value = substr($href, $pos+6);
		$id = $this->driver->findElement(By::xPath("//input[@value=$value]"))->getAttribute('id');
		return $id;

	}

}