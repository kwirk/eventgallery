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



class EventgalleryModelMethod extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';
    protected $table_type = null;
    protected $table_name = null;
    protected $form_name = null;
    protected $form_source = null;



    public function getTable($type = '', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($this->table_type, $prefix, $config);
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
        $form = $this->loadForm($this->form_name, $this->form_source, array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.'.$this->form_name.'.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
            // Prime some default values.
            if ($this->getState($this->form_name.'.id') == 0)
            {
                $app = JFactory::getApplication();
                $data->set('id', $app->input->get('id'));
            }
        }

        $this->preprocessData($this->form_source, $data);

        return $data;
    }

    public function setDefault($pks, $value) {

        $id = $pks[0];
        if ($value==1) {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update($this->table_name);
            $query->set('`default` = 0');
            $db->setQuery($query);
            $db->execute();

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update($this->table_name);
            $query->set('`default` = 1');
            $query->where('id='.$db->quote($id));

            $db->setQuery($query);
            $db->execute();

        }
        return true;

    }




   




}