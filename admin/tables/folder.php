<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


class TableFolder extends JTable
{
	var $id = null;
	var $folder = null;
	var $picasakey = null;
	var $tags = null;
	var $date = null;
	var $description = null;
	var $published = null;
	var $text = null;
	var $userid = null;
	var $lastmodified = null;
	var $ordering = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableFolder(& $db) {
		parent::__construct('#__eventgallery_folder', 'id', $db);
	}	
	

}
?>
