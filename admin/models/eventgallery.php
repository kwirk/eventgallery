<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.modellist');

class EventgalleryModelEventgallery extends JModelList
{

	function __construct()
	{
	    parent::__construct();
	}
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function getListQuery()
	{
		
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('f.*, IF (isNull(c.id),0,sum(1)) commentCount');
		$query->from('#__eventgallery_folder f left join #__eventgallery_comment c on f.folder=c.folder');
		$query->group('f.folder');
		$query->order('f.ordering DESC, f.folder DESC');

		return $query;
	}
	
}
