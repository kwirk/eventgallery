<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class EventgalleryLibraryManagerAddress extends EventgalleryLibraryDatabaseObject
{
	
	function __construct()
	{		
	 
	}

    /**
     * @param array $data
     * @oaram string $prefix
     * @return bool|JTable
     */
    public function createStaticAddress($data, $prefix) {
       $newData = array();
       foreach($data as $key=>$value) {
           $newData[str_replace($prefix,'',$key)] = $value;
       }

       $row = $this->store($newData, 'Staticaddress');
       return new EventgalleryLibraryAddress($row);
    }

 
}
