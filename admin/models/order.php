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

class EventgalleryModelOrder extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';

    public function getTable($type = 'order', $prefix = 'Table', $config = array())
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
        $form = $this->loadForm('com_eventgallery.order', 'order', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        // Determine correct permissions to check.
        if ($this->getState('subscription.id')) {
        // Existing record. Can only edit in selected categories.
                $form->setFieldAttribute('catid', 'action', 'core.edit');
        } else {
        // New record. Can only create in selected categories.
                $form->setFieldAttribute('catid', 'action', 'core.create');
        }
        // Modify the form based on access controls.
        if (!$this->canEditState((object) $data)) {
        // Disable fields for display.
        $form->setFieldAttribute('published', 'disabled', 'true');
        $form->setFieldAttribute('publish_up', 'disabled', 'true');
        $form->setFieldAttribute('publish_down', 'disabled', 'true');

        // Disable fields while saving.
        // The controller has already verified this is a record you canedit.
        $form->setFieldAttribute('published', 'filter', 'unset');
        $form->setFieldAttribute('publish_up', 'filter', 'unset');
        $form->setFieldAttribute('publish_down', 'filter', 'unset');
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return mixed The data for the form.
     */
    protected function loadFormData()
    {// Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.order.data', array());
        if (empty($data)) {
            $data = $this->getItem();
            // Prime some default values.
            if ($this->getState('order.id') == 0) {
                $app = JFactory::getApplication();
                $data->set('id', JRequest::getVar('id', $app->getUserState('com_eventgallery.orders.filter.category_id')));
            }
        }
        return $data;
    }

    /**
     * @param string $pk
     * @return bool|mixed|object
     */
    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : $this->getState($this->getName() . '.id');
        $table = $this->getTable();

        if ($pk > 0)
        {
            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if ($return === false && $table->getError())
            {
                $this->setError($table->getError());
                return false;
            }
        }

        // Convert to the JObject before adding other data.
        $properties = $table->getProperties(1);
        $item = JArrayHelper::toObject($properties, 'JObject');

        if (property_exists($item, 'params'))
        {
            $registry = new JRegistry;
            $registry->loadString($item->params);
            $item->params = $registry->toArray();
        }

        return $item;


    }

    protected function populateState()
    {
        $table = $this->getTable();
        $key = $table->getKeyName();

        // Get the pk of the record from the request.
        $pk = JFactory::getApplication()->input->getString($key);
        $this->setState($this->getName() . '.id', $pk);

        // Load the parameters.
        $value = JComponentHelper::getParams($this->option);
        $this->setState('params', $value);
    }







}
