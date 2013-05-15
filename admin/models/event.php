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

class EventgalleryModelEvent extends JModelLegacy
{
	
	function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$limitstart =  $app->getUserStateFromRequest( 'com_eventgallery.event.limitstart', 'limitstart', 0);	    
		$limit =  $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$this->setState('limit',$limit);
		$this->setState('com_eventgallery.event.limitstart',$limitstart);
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId($array[0]);
	}


	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
		$this->_files   = null;
	}

	function getFiles()
    {

        $query = 'SELECT file.*, IF (isNull(comment.id),0,sum(1)) commentCount
        	  from #__eventgallery_file file join #__eventgallery_folder folder on folder.folder=file.folder
        	  left join #__eventgallery_comment comment on file.folder=comment.folder and file.file=comment.file
                  where folder.id='.$this->_db->Quote($this->_id).'
                   
                    and (comment.published=1 or isNull(comment.published))
                  group by file.folder, file.file
                  order by ordering desc, file.file';

		$limit		= $this->getState('limit');
        $limitstart = $this->getState('com_eventgallery.event.limitstart');
   		if ($limitstart>$this->getTotal($this->_id)) {
			$limitstart = 0;
		}

        $entries = $this->_getList($query, $limitstart, $limit);
		
		if (strpos($this->_data->folder,'@')>-1) {
			$entries = Array();
		}
		
        return $entries;
    }

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

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row = $this->getTable('folder');
		$user = JFactory::getUser();
		
		$data = JRequest::get( 'post' );
		$data['text'] = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$data['userid'] = $user->id;
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
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		$this->_data = null;
		return true;
	}


	function publish($visible)
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {

				$row = $this->getTable('comment');

				$query = ' SELECT * FROM #__eventgallery_folder '.
							'  WHERE id = '.$cid;
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

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row = $this->getTable('folder');


		$db = JFactory::getDBO();
		$query = "select * from #__eventgallery_folder where id in (".implode(',',$cids).")";
		$db->setQuery($query);
		$folders = $db->loadObjectList("id");



		foreach($folders as $folder)
		{
			$query = "delete from #__eventgallery_file where folder='".$folder->folder."'";
			$db->setQuery($query);
			$db->query();
			$query = "delete from #__eventgallery_folder where folder='".$folder->folder."'";
			$db->setQuery($query);
			$db->query();

			
			$foldername=JFile::makeSafe($folder->folder);
			
			if (strlen($foldername)>0)
			{
				$path=JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR.$foldername.DIRECTORY_SEPARATOR ;
				$this->delTree($path);
			}
			
		}

		return true;
	}

	function getPagination()
	{

	    if (empty($this->_pagination))
	    {
	        jimport('joomla.html.pagination');
	        
	        $total = $this->getTotal();
	        $limit		= $this->getState('limit');
		    $limitstart = $this->getState('com_eventgallery.event.limitstart');	
		    
		     if ($limitstart > $total || JRequest::getVar('limitstart','0')==0) {
    		    $limitstart=0;    		   
    		    $this->setState('com_eventgallery.event.limitstart',$limitstart);
		    }
		    
		    $this->_pagination = new JPagination($total, $limitstart, $limit);
	    }
	    
	    
	    return $this->_pagination;
	    
	}
	
	function getTotal()
	{
		$query = 'select * from #__eventgallery_file file, #__eventgallery_folder folder where folder.folder=file.folder and folder.id='.$this->_db->Quote($this->_id);		
		return $this->_getListCount($query);
	}

	private function delTree($dir) { 
	   $files = array_diff(scandir($dir), array('.','..')); 
	    foreach ($files as $file) { 
	      (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file"); 
	    } 
	    return rmdir($dir); 
	  } 
	

}
