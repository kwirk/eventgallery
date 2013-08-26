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
	public $id = null;
	public $file = null;
    public $folder = null;
	public $hits = null;
	public $caption = null;
	public $title = null;
	public $published = null;
	public $allowcomments = null;
	public $userid = null;
	public $ordering = null;
	public $ismainimage = null;
	public $ismainimageonly = null;
	public $modified = null;
	public $created = null;

    /**
     * Constructor
     * @param JDatabaseDriver $db
     */

	function TableFile($db) {
		parent::__construct('#__eventgallery_file', 'id', $db);
	}
}
