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

	protected $waitForXpath =  "//h1[text()=\"Event Manager\"]";	
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

		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));

		$message = $this->getAlertMessage();
		$this->test->assertTrue(strpos($message, 'Event saved!') >= 0, 'Event save return success');
	} 

	public function publishEvent($event) {
		$id = $this->getLineId($event);
		
		$this->driver->findElement(By::xPath("//a[@onclick=\"return listItemTask('$id','publish')\"]"))->click();

		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
		
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
		$folder = $this->driver->waitForElementUntilIsPresent(By::xPath("//div[@class=\"alert alert-success\"]"), 20);
		$message = $this->getAlertMessage();
		$this->test->assertTrue(strpos($message, 'Event(s) Deleted') >= 0, 'Event deletion should return success');		
	}

	public function uploadFiles($event) {
		$value = $this->getEventId($event);		
		$this->driver->findElement(By::id("upload_$value"))->click();
		$uploadPage = $this->test->getPageObject('EventUploadPage');
		$uploadPage->uploadFiles($event);
		$this->clickMenuByUrl('com_eventgallery','EventsPage');
		$this->test->getPageObject('EventsPage');
	}

/*
	#############################
	# Helper Methodes           #
	#############################	
*/

	protected function getEventId($event) {
		$element = $this->driver->findElement(By::xPath('//a[text()="'.$event.'"]'));
		$href = $element->getAttribute("href");
		$pos = strpos($href,"cid[]=");
		$value = substr($href, $pos+6);		
		return $value;
	}

	protected function getLineId($event) {

		$value = $this->getEventId($event);
		$id = $this->driver->findElement(By::xPath("//input[@value=$value]"))->getAttribute('id');
		return $id;

	}

}