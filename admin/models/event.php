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
jimport('joomla.html.pagination');
jimport('joomla.filesystem.file');

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryModelEvent extends JModelAdmin
{

    public function getItem($pk = null) {
        $item = parent::getItem($pk);

        if ($item!== false) {
            // Convert the params field to an array.
            $registry = new JRegistry;
            $registry->loadString($item->attribs);
            $item->attribs = $registry->toArray();
        }

        return $item;
    }

    public function getTable($type = 'Event', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }


	function changeFolderName($oldFolder, $newFolder)
	{
		$db = JFactory::getDBO();

        // update the file table
		$query = $db->getQuery(true)
			->update($db->quoteName('#__eventgallery_file'))
			->set('folder=' . $db->quote($newFolder))
			->where('folder=' . $db->quote($oldFolder));
		$db->setQuery($query);
		$db->query();

        // update the comment table
		$query = $db->getQuery(true)
			->update($db->quoteName('#__eventgallery_comment'))
			->set('folder=' . $db->quote($newFolder))
			->where('folder=' . $db->quote($oldFolder));
		$db->setQuery($query);
		$db->query();

        // update the imagelineitem table
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__eventgallery_imagelineitem'))
            ->set('folder=' . $db->quote($newFolder))
            ->where('folder=' . $db->quote($oldFolder));
        $db->setQuery($query);
        $db->query();
	}

	

    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm('com_eventgallery.event', 'event', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)){
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_eventgallery.edit.event.data', array());

        if (empty($data))
        {
            $data = $this->getItem();

            $data->usergroups = explode(',', $data->usergroupids);
            // Prime some default values.
            if ($this->getState('event.id') == 0)
            {
                $app = JFactory::getApplication();
                $data->set('id', $app->input->get('id'));
            }
        }
        
		if (method_exists($this, 'preprocessData')) {
        	$this->preprocessData('com_eventgallery.event', $data);
        }

        return $data;
    }

    function cartable($pks, $iscartable)
    {
        $table = $this->getTable();
        $pks = (array) $pks;
        $result = true;

        foreach ($pks as $i => $pk)
        {
            $table->reset();

            if ($table->load($pk))
            {
                $table->cartable= $iscartable;
                $table->store();
            }
            else
            {
                $this->setError($table->getError());
                unset($pks[$i]);
                $result = false;
            }
        }



        return $result;
    }

    public function validate($form, $data, $group = null) {
        // clean up the folder name if it is no picasa album
        if (strpos($data['folder'], '@')===false  ) {
            $data['folder'] = JFile::makeSafe($data['folder']);
        }

        $validData =  parent::validate($form, $data, $group);

        if (!isset($data['usergroups']) || count($data['usergroups'])==0) {
            $validData['usergroupids'] = '';
        } else {
            $validData['usergroupids'] = implode(',', $data['usergroups']);
        }

        return $validData;
    }

    public function delete(&$pks) {

        $folders = array();
        $db = JFactory::getDBO();
        $pks = (array) $pks;
        $table = $this->getTable();

        // Iterate the items to remember to items which needs to be deleted
        foreach ($pks as $i => $pk)
        {

            if ($table->load($pk))
            {
                $folders[$pk] = $table->folder;
            }
        }

        $result = parent::delete($pks);

        $maindir=JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
        //remove the files and folders
        foreach($folders as $key=>$folder) {
            // if the folder does not longer exist
            if (!$table->load($key)) {

                // remove the physical files
                $this->delTree($maindir.$folder);

                // remove files
                $query = $db->getQuery(true)
                    ->delete($db->quoteName('#__eventgallery_file'))
                    ->where('folder=' . $db->quote($folder));
                $db->setQuery($query);
                $db->query();

                // remove comments
                $query = $db->getQuery(true)
                    ->delete($db->quoteName('#__eventgallery_comment'))
                    ->where('folder=' . $db->quote($folder));
                $db->setQuery($query);
                $db->query();
            }
        }

        return $result;
    }

    /**
     * @param $dir a path to the folder which should be deleted
     * @return bool
     */
    private function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

}
