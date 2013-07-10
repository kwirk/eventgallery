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

class EventgalleryModelOrderstatus extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';

    public function getTable($type = 'orderstatus', $prefix = 'Table', $config = array())
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
        $form = $this->loadForm('com_eventgallery.orderstatus', 'orderstatus', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.orderstatus.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
            // Prime some default values.
            if ($this->getState('orderstatus.id') == 0)
            {
                $app = JFactory::getApplication();
                $data->set('id', $app->input->get('id'));
            }
        }

        $this->preprocessData('com_eventgallery.orderstatus', $data);

        return $data;
    }

     public function setDefault($pks, $value) {

        $id = $pks[0];
        $orderStatus = new EventgalleryLibraryOrderstatus($id);

        if ($value==1) {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update('#__eventgallery_orderstatus');
            $query->set('`default` = 0');
            $query->where('type='.$db->quote($orderStatus->getType()));

            $db->setQuery($query);
            $db->execute();

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update('#__eventgallery_orderstatus');
            $query->set('`default` = 1');
            $query->where('type='.$db->quote($orderStatus->getType()));
            $query->where('id='.$db->quote($id));

            $db->setQuery($query);
            $db->execute();

        }
        return true;

    }





   




}
