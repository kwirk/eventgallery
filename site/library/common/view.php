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

class EventgalleryLibraryCommonView extends JViewLegacy
{

    /**
     * Loads a snippet from the snippets folder. Name can contain a slash:
     *  foo/bar will load component/views/snippets/tmpl/foo/bar.php
     *
     * @param string $tpl
     *
     * @return string the output of the template
     * @throws Exception
     */
    public function loadSnippet($tpl)
    {
        // Clear prior output
        $this->_output = null;


        $baseDir = $this->_basePath . '/views/snippets/tmpl';
        $component = JApplicationHelper::getComponentName();
        $app = JFactory::getApplication();
        $component = preg_replace('/[^A-Z0-9_\.-]/i', '', $component);
        $fallback = JPATH_THEMES . '/' . $app->getTemplate() . '/html/' . $component . '/' . 'snippets';

        $path = array($fallback, $baseDir);

        // Clean the file name
        $file = preg_replace('/[^A-Z0-9_\.-\/]/i', '', $tpl);
        $tpl = isset($tpl) ? preg_replace('/[^A-Z0-9_\.-]/i', '', $tpl) : $tpl;


        // Load the template script
        jimport('joomla.filesystem.path');
        $filetofind = $this->_createFileName('template', array('name' => $file));
        $template = JPath::find($path, $filetofind);

        // If alternate layout can't be found, fall back to default layout
        if ($template == false)
        {
            throw new Exception(JText::sprintf('JLIB_APPLICATION_ERROR_LAYOUTFILE_NOT_FOUND', $file), 500);
        }


        // Unset so as not to introduce into template scope
        unset($tpl);
        unset($file);

        // Never allow a 'this' property
        if (isset($this->this))
        {
            unset($this->this);
        }

        // Start capturing output into a buffer
        ob_start();

        // Include the requested template filename in the local scope
        // (this will execute the view logic).
        include $template;

        // Done with the requested template; get the buffer and
        // clear it.
        $this->_output = ob_get_contents();
        ob_end_clean();

        return $this->_output;


    }

}
