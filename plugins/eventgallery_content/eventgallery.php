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
        $canProceed = $context == 'com_content.article' || $context == 'com_content.category';

        if (!$canProceed)
        {
            #return;
        }

        $row->text = $this->replace($row->text, $params);
        $row->introtext = $this->replace($row->introtext, $params);


    }

    private function replace($text, $params) {
        preg_match_all("/\{eventgallery ([^\}]*)\}/", $text, $matches);

        if ($params == NULL) {
            $params = new JRegistry();
        }

        foreach($matches[0] as $key=>$paramString) {
            $result = "";

            preg_match("/event=\"([^\"]*)\"/", $paramString, $folderMatches);
            if (!isset($folderMatches[1])) {
                continue;
            }
            $foldername = $folderMatches[1];

            $max_images = $this->getParameterValue($paramString, 'max_images', 5);

            $params->set('thumb_width', $this->getParameterValue($paramString, 'thumb_width', 50));

            $attr = $this->getParameterValue($paramString, 'attr', 'images');
            $type = $this->getParameterValue($paramString, 'type', null);


            /**
             * @var EventgalleryLibraryManagerFolder $folderMgr
             */
            $folderMgr = EventgalleryLibraryManagerFolder::getInstance();
            $folder = $folderMgr->getFolder($foldername);

            if (isset($folder) && $folder->isPublished() && EventgalleryHelpersFolderprotection::isAccessAllowed($folder) && $folder->isVisible()) {
                $files = $folder->getFiles(0, $max_images, 1);

                if ($attr == 'images') {
                    // Get the path for the layout file
                    $path = JPluginHelper::getLayoutPath('content', 'eventgallery');

                    // Render
                    ob_start();
                    include $path;
                    $result = ob_get_clean();
                }

                if ($attr == 'description') {
                    $result = $folder->getDescription();
                }

                if ($attr == 'text') {
                    if ($type=="intro") {
                        $result = $folder->getIntroText();
                    } else {
                        $result = $folder->getText();
                    }
                }
            }


            $text = str_replace($paramString, $result, $text, $count );
        }




        return $text;
    }

    /**
     * @param $string a string like foo="bar" bar="1223"
     * @param $key a string like foo
     * @param $defaultValue the default result.
     * @return null|String with the key for the result is bar
     */
    protected function getParameterValue($string, $key, $defaultValue) {

        preg_match("/$key=\"?([A-Za-z0-9]+)\"?/", $string, $sizeMatches);
        if (isset($sizeMatches[1])) {
            return $sizeMatches[1];
        }

        return $defaultValue;
    }

   
}