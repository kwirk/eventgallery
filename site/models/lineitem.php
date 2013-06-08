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

class EventgalleryModelsLineitem extends EventgalleryModelsDefault
{

	var $_lineitemcontainer_id     = null;
	
	function __construct($lineitemcontainer_id)
	{				 
		$this->_lineitemcontainer_id = $lineitemcontainer_id;
	    parent::__construct();	 
	}

	/**
	* Builds the query to be used by the book model
	* @return   object  Query object
	*
	*
	*/
	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('ili.*');
		$query->from('#__eventgallery_imagelineitem as ili');

		return $query;
	}

	/**
	* Builds the filter for the query
	* @param    object  Query object
	* @return   object  Query object
	*
	*/
	protected function _buildWhere(&$query)
	{
		$db = JFactory::getDBO();
		$query->where('ili.lineitemcontainerid = ' . $db->quote($this->_lineitemcontainer_id) );
		$query->where('ili.status = 0');
		return $query;
	}

	/* find a specific line item*/
	function getItemByFileAndType($lineitemcontainerid, $folder, $file, $typeid) {
		$db = JFactory::getDBO();
		$query = $this->_buildQuery();    
		$this->_buildWhere($query);
		$query->where('lineitemcontainerid='. $db->quote($lineitemcontainerid));
		$query->where('folder='. $db->quote($folder));
		$query->where('file='. $db->quote($file));
		$query->where('typeid='. $db->quote($typeid));
		$db->setQuery($query);
		return $db->loadObject();
		
	}

	function getItems() {

		$query = $this->_buildQuery();    
		$this->_buildWhere($query);
		$list = $this->_getList($query);
		return $list;
	}

	function removeItem($lineitemid) {
		$db = JFactory::getDBO();
		$query = "delete from #__eventgallery_imagelineitem where id=".$db->quote($lineitemid)." and lineitemcontainerid=".$db->quote($this->_lineitemcontainer_id)."";
		
		$db->setQuery($query);
		$db->execute();
	}
 
}
