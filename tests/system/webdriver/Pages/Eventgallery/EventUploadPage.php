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
class EventUploadPage extends EventsPage
{
	
	protected $waitForXpath =  "//h1[text()=\"Event: [ upload ]\"]";
	protected $url = 'index.php?option=com_eventgallery&task=upload&cid[]=';	
	

	public function uploadFiles($event, $noOfFiles=5) {		

		$uploader =  $this->driver->findElement(By::id("fileselect"));

		// remove the first 3 folders because
		// we're in a subfolder and have not 
		// native Joomla help. Doing this will
		// enable this comonent to run in a subdirectory
		// like http://foo.bar/foobar
		$basefolders = explode(DIRECTORY_SEPARATOR,__DIR__);
		$basefolders = array_splice($basefolders, 0, count($basefolders)-4);
		$path = implode(DIRECTORY_SEPARATOR, $basefolders).DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."images";

		$count = 0;
		if ($handle = opendir($path)) {

		    /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
		    while (false !== ($file = readdir($handle))) {
		    	if (strpos($file,'jpg')>0) {
		    		$uploader->sendKeys($path.DIRECTORY_SEPARATOR.$file);
		    		$count++;
		    	}
		    	if ($count>$noOfFiles) {
		    		break;
		    	}

		    }

		    closedir($handle);
		}

		$this->driver->waitForElementUntilIsPresent(By::xPath("//li[@class=\"success\"]"),10);
		$this->driver->waitForElementUntilIsNotPresent(By::xPath("//li[@class=\"uploading\"]"),300);
	}	

}