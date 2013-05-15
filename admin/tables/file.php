<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access');


class TableFile extends JTable
{
	var $id = null;
	var $file = null;
    var $folder = null;
	var $hits = null;
	var $caption = null;
	var $title = null;
	var $published = null;
	var $allowcomments = null;
	var $userid = null;
	var $lastmodified = null;
	var $ordering = null;
	var $ismainimage = null;
	var $ismainimageonly = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableFile($db) {
		parent::__construct('#__eventgallery_file', 'id', $db);
	}
}
?>
