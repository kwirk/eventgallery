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


jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');


class EventgalleryModelFile extends JModelLegacy
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}


	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__eventgallery_file '.
					'  WHERE id = '.$this->_db->quote($this->_id);

			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row = $this->getTable('file');
		$data = JRequest::get( 'post' );


		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 * 
	 * @access	public
	 * @return	boolean	True on success
	 */

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row = $this->getTable('file');

		if (count( $cids ))
		{
			foreach($cids as $cid) {

				$query = ' SELECT * FROM #__eventgallery_file '.
							'  WHERE id = '.$this->_db->quote($cid);
				
				$this->_db->setQuery( $query );
				$data = $this->_db->loadObject();
				
				$path=JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR.JFile::makeSafe($data->folder).DIRECTORY_SEPARATOR ;
				$filename=JFile::makeSafe($data->file);
				$file = $path.$filename;
				
				if (file_exists($file) && !is_dir($file)) {				
					if (!unlink($file)) {
						echo $file;
						return false;
					}		
				}

				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );					
					return false;
				}

				

			}						
		}
		return true;
	}

	function publish($visible)
	{
		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				
				$row = $this->getTable('file');

		        $query = ' SELECT * FROM #__eventgallery_file '.
							'  WHERE id = '.$this->_db->quote($cid);
				
				$this->_db->setQuery( $query );
				$data = $this->_db->loadObject();
				$row->bind($data);
				$row->published = $visible;
				$row->id=$cid;
				if (!$row->store()) {
					$this->setError( $row->getErrorMsg() );			
				}
			}
		}
		return true;
	}			

	function setCaption($caption, $title) {
		$cid = JRequest::getString('cid');

				
		$row = $this->getTable('file');

        $query = ' SELECT * FROM #__eventgallery_file '.        
					'  WHERE id = '.$this->_db->quote($cid);
		
		$this->_db->setQuery( $query );
		$data = $this->_db->loadObject();
		$row->bind($data);
		$row->caption = $caption;
		$row->title = $title;
		$row->id=$cid;
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );			
		}
		
		return true;

	}

	function allowComments($allowComments)
	{
		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				
				$row = $this->getTable('file');

		        $query = ' SELECT * FROM #__eventgallery_file '.
							'  WHERE id = '.$this->_db->quote($cid);
				
				$this->_db->setQuery( $query );
				$data = $this->_db->loadObject();
				$row->bind($data);
				$row->allowcomments = $allowComments;
				$row->id=$cid;
				if (!$row->store()) {
					$this->setError( $row->getErrorMsg() );			
				}
			}
		}
		return true;
	}		

	function setMainImageOnly($isMainImageOnly)
	{
		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				
				$row = $this->getTable('file');

		        $query = ' SELECT * FROM #__eventgallery_file '.
							'  WHERE id = '.$this->_db->quote($cid);
				
				$this->_db->setQuery( $query );
				$data = $this->_db->loadObject();
				$row->bind($data);
				$row->ismainimageonly = $isMainImageOnly;
				$row->id=$cid;
				if (!$row->store()) {
					$this->setError( $row->getErrorMsg() );			
				}
			}
		}
		return true;
	}	

	function setMainImage($isMainImage)
	{
		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				
				$row = $this->getTable('file');

		        $query = ' SELECT * FROM #__eventgallery_file '.
							'  WHERE id = '.$this->_db->quote($cid);
				
				$this->_db->setQuery( $query );
				$data = $this->_db->loadObject();
				$row->bind($data);
				$row->ismainimage = $isMainImage;
				$row->id=$cid;
				if (!$row->store()) {
					$this->setError( $row->getErrorMsg() );			
				}
			}
		}
		return true;
	}	

	function storeOrder($direction=0)
	{
		$db			= JFactory::getDBO();

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		$conditions	= array ();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// Instantiate an article table object
		$row =$this->getTable('file');

		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}
		
	}
	
	function move($direction)
	{
		$db		= JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		if (isset( $cid[0] ))
		{
			$row = $this->getTable('file');
			$row->load( (int) $cid[0] );
			$row->move($direction);
		}
		
	}


}
?>
