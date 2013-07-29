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
    protected $model;
    /**
     * @var JDocument
     */
    public $document;

    function display($tpl = NULL)
    {
        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();

        $this->state = $this->get('State');
        $this->params = $app->getParams();

        $model = $this->getModel('singleimage');
        $model->getData(JRequest::getString('folder'), JRequest::getString('file'));


        /* Default Page fallback*/
        $active = $app->getMenu()->getActive();
        if (NULL == $active) {
            $this->params->merge($app->getMenu()->getDefault()->params);
        }

        $this->model = $model;
        $this->use_comments = $this->params->get('use_comments');


        $folder = $model->folder;

        if (!is_object($folder)) {
            $app->redirect(JRoute::_("index.php?option=com_eventgallery", false));
        }


        if (!isset($model->file) || strlen($model->file->file) == 0) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=event&folder=" . $folder->folder, false),
                JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NO_PUBLISHED_MESSAGE'), 'info'
            );
        }

        $password = JRequest::getString('password', '');
        $accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($folder, $password);
        if (!$accessAllowed) {
            $app->redirect(
                JRoute::_("index.php?option=com_eventgallery&view=password&folder=" . $folder->folder, false)
            );
        }

     
        

        $this->setLayout(JRequest::getString('layout','minipage'));

        parent::display($tpl);
    }

}
