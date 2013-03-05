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
class FrontEndEventsPage extends FrontEndBasePage
{

	protected $waitForXpath =  "//div[@id=\"events\"]";		

	public function openEvent($folder = null) {
		if (null == $folder) {
			$this->driver->findElements(By::xPath('//a[contains(@class,"item")]'))[0]->click();
			
		} else {
			$this->driver->findElement(By::xPath('//a["'.$folder.'"=substring(@href,string-length(@href)-'.(strlen($folder)-1).')]'))->click();
		}

		$baseEventPage = $this->test->getPageObject('FrontEndBaseEventPage');

		return $baseEventPage::getEventPage();
	}

}