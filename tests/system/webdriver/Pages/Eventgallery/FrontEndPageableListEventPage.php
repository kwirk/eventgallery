<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the Pageable Image list screen
 *
 */
class FrontEndPageableListEventPage extends FrontEndImageListEventPage
{

	protected $waitForXpath =  "//div[@id=\"event\"]";		
	
	public function zoomImage() {
		// find a image which is showable in a lightbox	
		$this->driver->findElement(By::xPath('//div[@id="event"]//div[@class="thumbnail"]'))->click();		
		$this->driver->waitForElementUntilIsPresent(By::id("bigimagelink"));
		$this->driver->findElement(By::id("bigimagelink"))->click();		
		$this->driver->findElement(By::id('mbCloseLink'))->waitForElementUntilIsDisplayed()->click();
		$this->driver->waitForElementUntilIsNotPresent(By::id("mbImage"));
	}
}