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

class EventgalleryModelFile extends JModelAdmin
{
    protected $text_prefix = 'COM_EVENTGALLERY';

    public function getTable($type = 'file', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        return null;
    }

    /**
     * Method to delete record(s)
     *
     * @access	public
     * @return	boolean	True on success
     */

    function delete(&$pks)
    {


        $row = $this->getTable();

        if (count( $pks ))
        {
            foreach($pks as $cid) {

                $query = ' SELECT * FROM #__eventgallery_file '.
                    '  WHERE id = '.$this->_db->quote($cid);

                $this->_db->setQuery( $query );
                $data = $this->_db->loadObject();

                $path=JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR.JFile::makeSafe($data->folder).DIRECTORY_SEPARATOR ;
                $filename=JFile::makeSafe($data->file);
                $file = $path.$filename;

                if (file_exists($file) && !is_dir($file)) {
                    if (!unlink($file)) {
                        echo $file;
                        return false;
                    }
                }

                if (!$row->delete( $cid )) {
                    $this->setError( $row->getErrorMsg() );
                    return false;
                }



            }
        }
        return true;
    }



    function setCaption($caption, $title) {
        $cid = JRequest::getString('cid');


        $row = $this->getTable('file');

        $query = ' SELECT * FROM #__eventgallery_file '.
            '  WHERE id = '.$this->_db->quote($cid);

        $this->_db->setQuery( $query );
        $data = $this->_db->loadObject();
        $row->bind($data);
        $row->caption = $caption;
        $row->title = $title;
        $row->id=$cid;
        if (!$row->store()) {
            $this->setError( $row->getErrorMsg() );
        }

        return true;

    }  

    function allowComments($pks, $allowcomments)
    {
        $table = $this->getTable();
        $pks = (array) $pks;
        $result = true;

        foreach ($pks as $i => $pk)
        {
            $table->reset();

            if ($table->load($pk))
            {
                $table->allowcomments= $allowcomments;
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
  

    function isMainImageOnly($pks, $ismainimageonly)
    {
        $table = $this->getTable();
        $pks = (array) $pks;
        $result = true;

        foreach ($pks as $i => $pk)
        {
            $table->reset();

            if ($table->load($pk))
            {
                $table->ismainimageonly= $ismainimageonly;
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


    function isMainImage($pks, $ismainimage)
    {
        $table = $this->getTable();
        $pks = (array) $pks;
        $result = true;

        foreach ($pks as $i => $pk)
        {
            $table->reset();

            if ($table->load($pk))
            {
                $table->ismainimage= $ismainimage;
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



}
