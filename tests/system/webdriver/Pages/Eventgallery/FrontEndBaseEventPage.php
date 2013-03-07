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
		$add2cartButton = $this->driver->findElement(By::xPath('//a[contains(@class,"button-add2cart")]'));
		$data = $add2cartButton->getAttribute("data-id");
		$add2cartButton->click();

		//check for changed button
		$this->driver->waitForElementUntilIsPresent(By::xPath('//a[@data-id="'.$data.'" and contains(@class,"alreadyInCart")]'));

		// check for items removeFromCart-button in cart
		$this->driver->waitForElementUntilIsPresent(By::xPath('//a[@data-id="'.$data.'" and contains(@class,"eventgallery-removeFromCart")]'));
		
		$this->driver->findElement(By::xPath('//div[@class="eventgallery-cart"]'))->waitForElementUntilIsDisplayed();

		
	}

	public function removeFromCart() {
		$removeButton = $this->driver->findElement(By::xPath('//a[contains(@class,"eventgallery-removeFromCart")]'));
		
		$data = $removeButton->getAttribute("data-id");

		$removeButton->click();
		
		// wait until there is no item in cart since we added only one
		$this->driver->waitForElementUntilIsNotPresent(By::xPath('//a[@data-id="'.$data.'" and contains(@class,"eventgallery-removeFromCart")]'));
	}


}