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

jimport('joomla.application.component.modellist');

class EventgalleryModelImagetype extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';

    public function getTable($type = 'imagetype', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param array $data An optional array of data for the form to interogate.
     * @param boolean $loadData True if the form is to load its own data (default case), false if not.
     * @return JForm A JForm object on success, false on failure
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_eventgallery.imagetype', 'imagetype', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.imagetype.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
            // Prime some default values.
            if ($this->getState('imagetype.id') == 0)
            {
                $app = JFactory::getApplication();
                $data->set('id', $app->input->get('id'));
            }
        }

        $this->preprocessData('com_eventgallery.imagetype', $data);

        return $data;
    }





   




}
