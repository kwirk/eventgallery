<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the Ajax List Screen
 *
 */
class FrontEndAjaxListEventPage extends FrontEndBaseEventPage
{

	protected $waitForXpath =  "//div[@class=\"ajaxpaging\"]";		
	
	public function zoomImage() {
		// find a image which is showable in a lightbox	
		$this->driver->findElement(By::xPath('//div[@id="content"]//a[@rel="lightbo2" and (string-length(@class)=0 or @class!="btn")]'))->click();		
		$this->driver->waitForElementUntilIsPresent(By::id("mbCloseLink"));
		$this->driver->findElement(By::id('mbCloseLink'))->click();
		$this->driver->waitForElementUntilIsNotPresent(By::id("mbImage"));
	}

}