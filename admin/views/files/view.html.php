<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;



jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewFiles extends JViewLegacy
{

    protected $item;
    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null)
	{
        $this->item = $this->get('Item');
        $this->state		= $this->get('State');
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();

        parent::display($tpl);
	}

    protected function addToolbar() {
        $text = $this->item->folder;
        JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTS' ).': <small><small>[ ' . $text.' ]</small></small>' );

        JToolBarHelper::cancel('files.cancel', 'Close');


        JToolBarHelper::custom('files.publish', 'eg-published');
        JToolBarHelper::custom('files.unpublish', 'eg-published-inactive');

        JToolBarHelper::custom('files.allowComments', 'eg-comments');
        JToolBarHelper::custom('files.disallowComments', 'eg-comments-inactive');


        JToolBarHelper::custom('files.isMainImage', 'eg-mainimage');
        JToolBarHelper::custom('files.isNotMainImage', 'eg-mainimage-inactive');

        JToolBarHelper::custom('files.isNotMainImageOnly', 'eg-mainimageonly');
        JToolBarHelper::custom('files.isMainImageOnly', 'eg-mainimageonly-inactive');

        JToolBarHelper::spacer(50);

        JToolBarHelper::deleteList(JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_DELETE_ALERT' ), 'files.delete');
    }
}
