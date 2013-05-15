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
	var $password = null;
	var $cartable = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableFolder($db) {
		parent::__construct('#__eventgallery_folder', 'id', $db);
	}	
	

}
?>
