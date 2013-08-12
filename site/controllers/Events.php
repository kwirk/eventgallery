<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventsController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        $viewLayout = JRequest::getString('layout', 'default');
        $view = $this->getView('events', 'html', '', array('layout' => $viewLayout));

        /**
         * @var EventgalleryModelEvents $eventsModel
         */
        $eventsModel = $this->getModel('events', 'EventsModel');
        /**
         * @var EventgalleryModelEvent $eventModel
         */
        $eventModel = $this->getModel('event', 'EventModel');

        $view->setModel($eventsModel, true);
        $view->setModel($eventModel, false);

        parent::display($cachable, $urlparams);
    }

}
