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


class TableComment extends JTable
{
	var $id = null;
	var $file = null;
    var $folder = null;
	var $text = null;
	var $name = null;
	var $link = null;
	var $email = null;
	var $user_id = null;
	var $date = null;
	var $ip = null;
	var $published = null;
	var $lastmodified = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableComment($db) {
		parent::__construct('#__eventgallery_comment', 'id', $db);
	}
}
?>