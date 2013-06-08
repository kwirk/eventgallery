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

//jimport( 'joomla.application.component.helper' );

class EventgalleryModelsImagetypeset extends EventgalleryModelsDefault
{

	var $_typeset_id     = null;
	var $_typeset = null;
	
	function __construct($typeset_id)
	{				 
		$this->_typeset_id = $typeset_id;
		$this->loadTypeSet();
	    parent::__construct();	 
	}
	

	/* Initialy loads the type set*/
	protected function loadTypeSet() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->select('ts.*');
		$query->from('#__eventgallery_imagetypeset as ts');
		if ($this->_typeset_id != null) {
			$query->where('id='.$db->quote($this->_typeset_id));
		}		
		$query->order('ts.default DESC');
		$db->setQuery($query);
	    $this->_typeset = $db->loadObject();
	    $this->_typeset_id = $this->_typeset->id;
                                    
	    $query = 'select t.* 
	    			from #__eventgallery_imagetypeset_imagetype_assignment tsta left join #__eventgallery_imagetype t on tsta.typeid=t.id
	    			where tsta.typesetid='.$db->quote($this->_typeset_id).' 
	    			order by tsta.default DESC';
	    $db->setQuery($query);
	    $types = $db->loadObjectList();
	    $this->_typeset->types = $types;
	}

	function getTypeSet() {
		return $this->_typeset;		
	}

	function getType($typeid) {
		foreach ($this->_typeset->types as $type) {
			if ($typeid == $type->id) {
				return $type;
			}
		}
		return null;
	}

	function getTypes() {
		return $this->_typeset->types;
	}
 
}
