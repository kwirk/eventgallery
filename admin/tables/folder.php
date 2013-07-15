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
    public $id = null;
    public $folder = null;
    public $picasakey = null;
    public $tags = null;
    public $date = null;
    public $description = null;
    public $published = null;
    public $text = null;
    public $userid = null;
    public $lastmodified = null;
    public $ordering = null;
    public $password = null;
    public $cartable = null;
    public $typesetid;

    /**
     * Constructor
     * @param JDatabaseDriver $db
     */
	function TableFolder($db) {
		parent::__construct('#__eventgallery_folder', 'id', $db);
	}	
	

}
