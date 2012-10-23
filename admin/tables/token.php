<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


class TableToken extends JTable
{
	var $id = null;
	var $token = null;
	var $date = null;
	var $folder = null;
	var $userid = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableComment(& $db) {
		parent::__construct('#__eventgallery_comment', 'id', $db);
	}
}
?>