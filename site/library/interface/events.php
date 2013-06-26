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

/**
 * This interface provides a general interface for all method events.
 *
 * Class EventgalleryLibraryInterfaceMethod
 */
interface EventgalleryLibraryInterfaceEvents
{
    /**
     *
     * Event which is called if an order is about to be submitted
     *
     * @param $lineitemcontainer EventgalleryLibraryLineitemcontainer
     *
     * @return bool|array true or array with errors
     */
    public function processOnOrderSubmit($lineitemcontainer);


    

}
