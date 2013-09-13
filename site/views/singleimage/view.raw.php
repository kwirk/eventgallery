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


class EventgalleryViewSingleimage extends EventgalleryLibraryCommonView
{
   /**
     * @var JRegistry
     */
    protected $params;
    protected $state;
    protected $use_comments;
    /**
     * @var EventgalleryLibraryFolder
     */
    protected $folder;

    /**
     * @var EventgalleryLibraryFile
     */
    protected $file;

    protected $position;
    protected $imageset;
    protected $model;
    /**
     * @var JDocument
     */
    public $document;


    function display($tpl = NULL)
    {

      EventgalleryHelpersCaptcha::generateData();

        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();

        $this->state = $this->get('State');
        $this->params = $app->getParams();

        $model = $this->getModel('singleimage');
        $model->getData(JRequest::getString('folder'), JRequest::getString('file'));

        $this->model = $model;
        $this->file = $model->file;
        $this->folder = $this->file->getFolder();
        $this->position = $model->position;

        /* Default Page fallback*/
        $active = $app->getMenu()->getActive();
        if (NULL == $active) {
            $this->params->merge($app->getMenu()->getDefault()->params);
        }


        $this->use_comments = $this->params->get('use_comments');




        if (!is_object($this->folder)) {
            $app->redirect(JRoute::_("index.php?option=com_eventgallery", false));
        }


        if (!isset($this->file) || strlen($this->file->getFileName()) == 0) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=event&folder=" . $this->folder->getFolderName(), false),
                JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NO_PUBLISHED_MESSAGE'), 'info'
            );
        }

        if (!$this->folder->isVisible()) {
            $user = JFactory::getUser();
            if ($user->guest) {

                $redirectUrl = JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=" . $this->folder->getFolderName()."&file=".$this->file->getFileName(), false);
                $redirectUrl = urlencode(base64_encode($redirectUrl));
                $redirectUrl = '&return='.$redirectUrl;
                $joomlaLoginUrl = 'index.php?option=com_users&view=login';
                $finalUrl = JRoute::_($joomlaLoginUrl . $redirectUrl, false);
                $app->redirect($finalUrl);
            } else {
                $this->setLayout('noaccess');
            }
        }

        $password = JRequest::getString('password', '');
        $accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($this->folder, $password);
        if (!$accessAllowed) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=password&folder=" . $this->folder->getFolderName(), false)
            );
        }


        

       

        
        $this->setLayout(JRequest::getString('layout','minipage'));

        parent::display($tpl);
    }

        

       

}
