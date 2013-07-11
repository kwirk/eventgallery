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

jimport( 'joomla.application.component.modellist' );

class EventgalleryModelFiles extends JModelList
{

    protected $_id = null;
    protected $_item = null;

    public function __construct() {
        $ids = JRequest::getString('folderid');
        $this->_id = $ids;
        parent::__construct();
    }

	function getListQuery()
	{
		// Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);



		$query->select('file.*');
        $query->select('IF (isNull(comment.id),0,sum(1)) commentCount');
		$query->from('#__eventgallery_file AS file');
        $query->join('','#__eventgallery_folder AS folder on folder.folder=file.folder');
        $query->leftJoin('#__eventgallery_comment comment on file.folder=comment.folder and file.file=comment.file');
		$query->where('folder.id='.$this->_db->Quote($this->_id));
		$query->group('file.folder, file.file');
		$query->order('file.ordering DESC, file.folder DESC');

		return $query;
	}

    function getItem()
    {
        // Load the data
        if (empty( $this->_item )) {
            $query = ' SELECT * FROM #__eventgallery_folder '.
                '  WHERE id = \''.$this->_id.'\'';
            $this->_db->setQuery( $query );
            $this->_item = $this->_db->loadObject();
        }

        if (!$this->_item) {

            $this->_item = $this->getTable('folder');
        }
        return $this->_item;
    }

	


}
