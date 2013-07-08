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

jimport( 'joomla.application.component.model' );
jimport('joomla.html.pagination');

class EventgalleryModelEvent extends JModelAdmin
{
	function getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__eventgallery_folder '.
					'  WHERE id = \''.$this->_id.'\'';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}

		if (!$this->_data) {
			
			$this->_data = $this->getTable('folder');
		}
		return $this->_data;
	}


	function changeFolderName($oldFolder, $newFolder)
	{
		$db = JFactory::getDBO();
		$query = "update #__eventgallery_file set folder='$newFolder' where folder='$oldFolder';";
		$db->setQuery($query);
		$db->query();
		$query = "update #__eventgallery_comment set folder='$newFolder' where folder='$oldFolder';";
		$db->setQuery($query);
		$db->query();
	}
	
	

    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm('com_eventgallery.event', 'event', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)){
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.event.data', array());

        if (empty($data))
        {
            $data = $this->getItem();

        }

        $this->preprocessData('com_eventgallery.event', $data);

        return $data;
    }


}
