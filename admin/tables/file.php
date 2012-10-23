<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


class TableFile extends JTable
{
	var $id = null;
	var $file = null;
    var $folder = null;
	var $hits = null;
	var $published = null;
	var $allowcomments = null;
	var $userid = null;
	var $lastmodified = null;
	var $ordering = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableFile(& $db) {
		parent::__construct('#__eventgallery_file', 'id', $db);
	}
}
?>
