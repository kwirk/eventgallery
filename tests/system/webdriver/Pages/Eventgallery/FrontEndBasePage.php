<?php

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the basic frontend page
 *
 */
class FrontEndBasePage extends AdminPage
{

	/**
	 * @param  Webdriver                 $driver    Driver for this test.
	 * @param  JoomlaWebdriverTestClass  $test      Test class object (needed to create page class objects)
	 * @param  string                    $url       Optional URL to load when object is created. Only use for initial page load.
	 */
	public function __construct(Webdriver $driver, $test, $url = null)
	{
		$this->driver = $driver;
		/* @var $test JoomlaWebdriverTestCase */
		$this->test = $test;
		$cfg = new SeleniumConfig();
		$this->cfg = $cfg; // save current configuration
		if ($url)
		{
			$this->driver->get($url);
		}
		$element = $driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath), 5);
	}



}