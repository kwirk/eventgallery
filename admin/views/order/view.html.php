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

/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class EventgalleryViewOrder extends JViewLegacy
{

    protected $form;
    protected $item;
    protected $state;
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
// Initialiase variables.
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
                JError::raiseError(500, implode("\n", $errors));
                return false;
            }
        #$this->addToolbar();
        parent::display($tpl);
    }

}
