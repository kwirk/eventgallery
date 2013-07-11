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

jimport( 'joomla.application.component.modeladmin' );

class EventgalleryModelImagetypeset extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';

    public function getTable($type = 'imagetypeset', $prefix = 'Table', $config = array())
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
        // Get the form.
        $form = $this->loadForm('com_eventgallery.imagetypeset', 'imagetypeset', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.imagetypeset.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
            // Prime some default values.
            if ($this->getState('imagetypeset.id') == 0)
            {
                $app = JFactory::getApplication();
                $data->set('id', $app->input->get('id'));
            }
        }

        $this->preprocessData('com_eventgallery.imagetypeset', $data);

        return $data;
    }


    /**
     * save the imagetypeset
     *
     * @param array $data
     * @return bool|void
     */
    public function save($data) {
        $result = parent::save($data);

        if ($result===false) {
            return $result;
        }

        $id = $data['id'];
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->delete('#__eventgallery_imagetypeset_imagetype_assignment');
        $query->where('imagetypesetid = '.$db->quote($id));
        $db->setQuery($query);
        $db->execute();

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->insert('#__eventgallery_imagetypeset_imagetype_assignment');
        $query->columns('imagetypesetid, imagetypeid, ordering');
        foreach($data['imagetypes'] as $i=>$imagetypeid) {

            $query->values($db->quote($id).','.$db->quote($imagetypeid).','.$i);
        }
        $db->setQuery($query);
        $db->execute();

        return true;
    }



}
