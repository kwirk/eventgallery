<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


jimport('joomla.application.component.view');


class EventgalleryViewMail extends JViewLegacy
{

    
    
    /**
     * @var EventgalleryLibraryCart
     */
    protected $cart;
    /**
     * @var JRegistry
     */
    protected $params;
    protected $state;


    function display($tpl = null)
    {
        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();
        $this->state = $this->get('State');
        $this->params = $app->getParams();

        $this->cart = EventgalleryLibraryManagerCart::getInstance()->getCart();      

        parent::display($tpl);
    }   

}
