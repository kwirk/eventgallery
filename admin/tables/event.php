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

jimport('component.com_eventgallery.tables.TableFile');

/**
 * this class is only used for dummy reasons
 *
 * Class TableEvent
 *
 */
class TableEvent extends JTable
{



    function TableEvent($db) {
        parent::__construct('#__eventgallery_folder', 'id', $db);
    }

    public function store($updateNulls = false) {
        $this->modified = date("Y-m-d H:i:s");
        return parent::store($updateNulls);
    }


	/**
	 * Overloaded bind function
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties
	 *                          to ignore while binding.
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
	 *
	 * @see     JTable::bind
	 * @since   11.1
	 */
	public function bind($array, $ignore = '')
	{		

		if (isset($array['attribs']) && is_array($array['attribs']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['attribs']);
			$array['attribs'] = (string) $registry;
		}
		
		return parent::bind($array, $ignore);
	}

}

