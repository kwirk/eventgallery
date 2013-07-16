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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination');

class EventgalleryViewOrders extends EventgalleryLibraryCommonView
{

    protected $items;
    protected $pagination;
    protected $state;
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode("\n", $errors));
                return false;
        }

        $this->addToolbar();
        EventgalleryHelpersEventgallery::addSubmenu('orders');      
        $this->sidebar = JHtmlSidebar::render();

        return parent::display($tpl);
    }

     protected function addToolbar() {
        JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_ORDERS' ), 'generic.png' );            
        
        JToolBarHelper::deleteList('Remove all selected Events?','orders.delete','Remove');
    }

}
