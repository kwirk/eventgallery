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


class EventsViewEvents extends EventgalleryLibraryCommonView
{
    protected $params;
    protected $entries;
    protected $fileCount;
    protected $folderCount;
    protected $eventModel;
    protected $pageNav;

    protected $folder;


    /**
     * @var JCacheControllerCallback $cache
     */
    protected $cache;


    function display($tpl = NULL)
    {

        /**
         * @var JCacheControllerCallback $cache
         */
        $cache = JFactory::getCache('com_eventgallery');
        $this->cache = $cache;


        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();

        $this->params = $app->getParams();

        /* Default Page fallback*/
        $active = $app->getMenu()->getActive();
        if (NULL == $active) {
            $this->params->merge($app->getMenu()->getDefault()->params);
        }

        $entriesPerPage = $this->params->get('max_events_per_page', 12);

        if ($this->getLayout()=='magic') {
            $entriesPerPage = 0;
        }

        $model = $this->getModel('events');
        $eventModel = JModelLegacy::getInstance('Event', 'EventModel');


        $user = JFactory::getUser();
        $usergroups = JUserHelper::getUserGroups($user->id);
        //$entries = $model->getEntries(JRequest::getVar('page',1),$entriesPerPage,$params->get('tags'));
        $entries = $this->cache->call(
            array($model, 'getEntries'), JRequest::getVar('start', 0), $entriesPerPage, $this->params->get('tags'),
            $this->params->get('sort_events_by'), $usergroups
        );

        $this->pageNav = $model->getPagination();


        if ($this->getLayout()=='magic') {
            $images = array();

            foreach ($entries as $entry) {
               $result =  $this->cache->call(array($eventModel, 'getEntries'), $entry->getFolderName(), -1, -1, 0);
               $images = array_merge($images, $result);
            }

            $this->entries = $images;
            $this->folder = new DummyFolder($this->params);
            $this->entriesCount = count($images);

        } else {

            $this->entries = $entries;
            $this->eventModel = $eventModel;
        }

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app    = JFactory::getApplication();
        $menus  = $app->getMenu();
        $title  = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if($menu)
        {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        }

        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description'))
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords'))
        {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots'))
        {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}

class DummyFolder {

    protected $_attribs;

    public function __construct($attribs) {
        $this->_attribs = $attribs;
    }

    public function getDate() {
        return "";
    }

    public function getDescription() {
        return "";
    }

    public function getText() {
        return "";
    }

    public function getIntroText() {
        return "";
    }

    public function getFolderName() {
        return "";
    }

    public function isCartable() {
        return false;
    }

    public function getAttribs() {
        return $this->_attribs;
    }

}

