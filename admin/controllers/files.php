<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class EventgalleryControllerFiles extends JControllerAdmin
{

    protected $_anchor = "";

    public function __construct($config = array())
    {
        $cids = JRequest::getVar('cid');
        if (isset($cids[0])) {
            $this->_anchor = '#'.$cids[0];
        }

        parent::__construct($config);

    }



    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'File', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    function cancel() {
        $this->setRedirect( 'index.php?option=com_eventgallery');
    }

    /**
     * function to publish a single file/multiple files
     *
     * @return unknown_type
     */
    function publish()
    {
       parent::publish();
       $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor);
    }

    function saveorder()
    {
        parent::saveorder();
        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid'));
    }

    function reorder()
    {
        parent::reorder();
        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor);
    }

    function delete()
    {
        parent::delete();
        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid'));
    }


    /**
     * saves the caption for a file
     */
    function saveCaption() {
        $title = JRequest::getVar( 'title', '', 'post', 'string', JREQUEST_ALLOWHTML );
        $caption = JRequest::getVar( 'caption', '', 'post', 'string', JREQUEST_ALLOWHTML );
        $model = $this->getModel('file');
        $model->setCaption($caption, $title);
        echo "Done";
        die();
    }
    /**
     * function to allow Comments of a single file/multiple files
     *
     * @return unknown_type
     */
    function allowComments()
    {
        $model = $this->getModel();
        $model->allowComments(1);

        $msg = JText::_( 'COM_EVENTGALLERY_COMMENTS_ENABLE_FOR_FILE' );


        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor, $msg );
    }

    /**
     * function to disallow Comments of a single file/multiple files
     *
     * @return unknown_type
     */
    function disallowComments()
    {
        $model = $this->getModel();
        $model->allowComments(0);


        $msg = JText::_( 'COM_EVENTGALLERY_COMMENTS_DISABLE_FOR_FILE' );

        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor, $msg);
    }

    /**
     * function to enable an image as main image for  single file/multiple files
     *
     * @return unknown_type
     */
    function isMainImage()
    {
        $model = $this->getModel();
        $model->setMainImage(1);

        $msg = JText::_( 'COM_EVENTGALLERY_ISMAINIMAGE_ENABLE_FOR_FILE' );

        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor, $msg );
    }

    /**
     * function to enable an image as main image for a single file/multiple files
     *
     * @return unknown_type
     */
    function isNotMainImage()
    {
        $model = $this->getModel();
        $model->setMainImage(0);

        $msg = JText::_( 'COM_EVENTGALLERY_ISMAINIMAGE_DISABLE_FOR_FILE' );

        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor, $msg );
    }

    /**
     * function to enable an image as main image for  single file/multiple files
     *
     * @return unknown_type
     */
    function isMainImageOnly()
    {
        $model = $this->getModel();
        $model->setMainImageOnly(1);

        $msg = JText::_( 'COM_EVENTGALLERY_ISMAINIMAGEONLY_ENABLE_FOR_FILE' );


        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor, $msg );
    }

    /**
     * function to enable an image as main image for a single file/multiple files
     *
     * @return unknown_type
     */
    function isNotMainImageOnly()
    {
        $model = $this->getModel();
        $model->setMainImageOnly(0);

        $msg = JText::_( 'COM_EVENTGALLERY_ISMAINIMAGEONLY_DISABLE_FOR_FILE' );

        $this->setRedirect( 'index.php?option=com_eventgallery&view=files&folderid='.JRequest::getVar('folderid').$this->_anchor, $msg );
    }

    
}