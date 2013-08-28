<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

jimport('joomla.utilities.utility');
require_once JPATH_SITE . '/components/com_eventgallery/helpers/tags.php';
require_once JPATH_SITE . '/components/com_eventgallery/helpers/route.php';
require_once JPATH_SITE . '/components/com_eventgallery/helpers/textsplitter.php';

/**
 * Eventgallery Content plugin
 *
 */
class PlgContentEventgallery extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;


    public function __construct(&$subject, $config = array())
    {
        //load classes
        JLoader::registerPrefix('Eventgallery', JPATH_BASE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery');

        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_eventgallery/models', 'ContentModel');
        parent::__construct($subject, $config);
    }

    /**
     * Plugin that adds a pagebreak into the text and truncates text at that point
     *
     * @param   string   $context  The context of the content being passed to the plugin.
     * @param   object   &$row     The article object.  Note $article->text is also available
     * @param   mixed    &$params  The article params
     * @param   integer  $page     The 'page' number
     *
     * @return  mixed  Always returns void or true
     *
     * @since   1.6
     */
    public function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        $canProceed = $context == 'com_content.article';

        if (!$canProceed)
        {
            return;
        }

        $foldername = "test";
        $result = "";

        if ($foldername) {
            /**
             * @var EventModelEvent $model
             * */
            $model = JModelLegacy::getInstance('Event', 'EventModel', array('ignore_request' => true));
            $folder = $model->getFolder($foldername);



            if (isset($folder) && $folder->published==1 && EventgalleryHelpersFolderprotection::isAccessAllowed($folder) && EventgalleryHelpersFolderprotection::isVisible($folder)) {
                $files = $model->getEntries($foldername, 0, $params->get('max_images'), 1);

                ob_start();

                // Include the requested template filename in the local scope
                // (this will execute the view logic).
                include __DIR__.'/tmpl/default.php';

                // Done with the requested template; get the buffer and
                // clear it.
                $result = ob_get_contents();
                ob_end_clean();
            }
        }

        $row->text = str_replace("{eventgallery}", $result, $row->text);

        return true;
    }

   
}