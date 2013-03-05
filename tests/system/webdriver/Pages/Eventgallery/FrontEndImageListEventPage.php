<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the ImageList screen
 *
 */
class FrontEndImageListEventPage extends FrontEndBaseEventPage
{

	protected $waitForXpath =  "//div[@class=\"event\"]";		
	

	public function zoomImage() {
		// find a image which is showable in a lightbox	
		$this->driver->findElement(By::xPath('//a[contains(@rel,"lightbo2[gallery]")]'))->click();		
		$this->driver->waitForElementUntilIsPresent(By::id("mbCloseLink"));		
		$this->driver->findElement(By::id('mbCloseLink'))->click();
		$this->driver->waitForElementUntilIsNotPresent(By::id("mbImage"));
	}

}