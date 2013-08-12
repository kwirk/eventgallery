<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class ResizeimageController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        // this fixes a strange behavior where the viewType is set to php if url rewriting is enabled.

        $document = JFactory::getDocument();
        $document->setType('html');

        parent::display($cachable, $urlparams);
    }

}
