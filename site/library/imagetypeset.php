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

class EventgalleryLibraryImagetypeset extends EventgalleryLibraryDatabaseObject
{

    /**
     * @var int
     */
    protected $_imagetypeset_id = null;

    /**
     * @var TableImagetypeset
     */
    protected $_imagetypeset = null;

    /**
     * @var array
     */
    protected $_imagetypes = null;

    /**
     * @var int
     */
    protected $_defaultimagetype_id = null;

    /**
     * @param int $imagetypeset_id
     */
    public function __construct($imagetypeset_id = -1)
	{		
		$this->_imagetypeset_id = $imagetypeset_id;
		$this->_loadImageTypeSet();
	    parent::__construct();	    	 
	}

    /**
     *
     */
    protected function _loadImageTypeSet() {

		$this->_imagetypeset = null;
		$this->_imagetypes = null;		

		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('its.*');
		$query->from('#__eventgallery_imagetypeset as its');				
		if ($this->_imagetypeset_id != -1) {
			$query->where('its.id='.$db->quote($this->_imagetypeset_id));
		}		
		$query->order('its.default DESC');
		$db->setQuery($query);
		$this->_imagetypeset = $db->loadObject();

		$this->_imagetypeset_id = $this->_imagetypeset->id;

		$this->_loadImageTypes();
	}

    /**
     * @throws Exception
     */
    protected function _loadImageTypes() {

		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('t.*, tsta.default as defaultimagetype');
		$query->from('#__eventgallery_imagetypeset_imagetype_assignment tsta left join #__eventgallery_imagetype t on tsta.typeid=t.id');				
		$query->where('tsta.typesetid='.$db->quote($this->_imagetypeset->id));
		$query->order('tsta.ordering');
		$db->setQuery($query);
		$dbtypes = $db->loadObjectList();
		$types = array();

		$this->_defaultimagetype_id = null;

		foreach ($dbtypes as $dbtype) {

            if ($dbtype->defaultimagetype==1) {
				$this->_defaultimagetype_id = $dbtype->id;
			}
			$types[$dbtype->id] = new EventgalleryLibraryImagetype($dbtype);
		}		

		$this->_imagetypes = $types;


		if (count($types)==0) {
			throw new Exception("ImageTypeSet ".$this->_imagetypeset_id.' is invalid. Please assign at least one ImageType.');
		}
 		
	}

    /**
     * @return array|null
     */
    public function getImageTypes() {
		return $this->_imagetypes;
	}

    /**
     * @param int $imagetypeid
     * @return EventgalleryLibraryImagetype
     */
    public function getImageType($imagetypeid) {
		return $this->_imagetypes[$imagetypeid];

	}

    /**
     * @return EventgalleryLibraryImagetype
     */
    public function getDefaultImageType() {
		if ($this->_defaultimagetype_id==null) {
            $result = array_values($this->_imagetypes);
			return $result[0];
		} else {
			return $this->getImageType($this->_defaultimagetype_id);
		}
	}

}
