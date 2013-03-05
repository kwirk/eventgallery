<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for an image list
 *
 */
class FrontEndBaseEventPage extends FrontEndBasePage
{

	protected $waitForXpath =  "//div[@class=\"event\" or @class=\"ajaxpaging\" or @id=\"event\"]";			

	/**
	* Determines what kind of Eventpage we have. Basically there are three different layouts possible
	*/
	public function getEventPage() {

		try {
	 		if (null !=  $this->driver->findElement(By::xPath("//div[@class=\"event\"]")) ) {
				return $this->test->getPageObject('FrontEndImageListEventPage');
			}
		} catch (Exception $e) {}

		try {
			if (null !=  $this->driver->findElement(By::xPath("//div[@id=\"event\"]")) ) {
				return $this->test->getPageObject('FrontEndPageableListEventPage');
			}
		} catch (Exception $e) {}

		try {
			if (null !=  $this->driver->findElement(By::xPath("//div[@class=\"ajaxpaging\"]")) ) {
				return $this->test->getPageObject('FrontEndAjaxListEventPage');
			}
		} catch (Exception $e) {}
	
		return null;
	}

	public function zoomImage() {		
	}

	public function add2cart() {
		// click on add2cart
		$this->driver->findElement(By::xPath('//a[contains(@class,"eventgallery-add2cart")]'))->click();
		$this->driver->waitForElementUntilIsPresent(By::xPath('//div[@class="eventgallery-cart"]'));
		$this->driver->findElement(By::xPath('//div[@class="eventgallery-cart"]'))->waitForElementUntilIsDisplayed();

		// remove it again
		$this->driver->findElement(By::xPath('//a[contains(@class,"eventgallery-removeFromCart")]'))->click();

		// wait until there is no item in cart since we added only one
		$this->driver->waitForElementUntilIsNotPresent(By::xPath('//a[contains(@class,"eventgallery-removeFromCart")]'));
	}


}