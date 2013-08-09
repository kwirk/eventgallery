<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


require_once JPATH_ADMINISTRATOR.'/components/com_eventgallery/helpers/php_markdown_extra/markdown.php';
require_once JPATH_ADMINISTRATOR.'/components/com_eventgallery/helpers/php_markdown_extra/MarkdownExtra.php';
use \Michelf\MarkdownExtra;

/**
 * Helps to create a help page which can contain nested markdown files.
 *
 * Class EventgalleryHelpersHelppagebuilder
 */
class EventgalleryHelpersHelppagebuilder {

    public function __construct() {

    }

    public function process($file) {
        $content = $this->include_content($file, null);

        $my_html = MarkdownExtra::defaultTransform($content);

        //fix links
        $search  = '<img src="img/';
        $replace = '<img class="nosmartresize" src="'.JURI::root().'administrator/components/com_eventgallery/doc/img/';

        $my_html = str_replace($search, $replace, $my_html);

        return $my_html;
    }

    protected function include_content($file, $content) {
        if ($content==null || strlen($content)==0) {
            $content = file_get_contents(JPATH_ROOT.'/'.$file);
        }

        preg_match_all("/{file:\/\/([^}]*)}/", $content, $matches);

        if (count($matches[0])==0) {
            return $content;
        }

        foreach($matches[1] as $key=>$match) {
            $inline_content = file_get_contents(JPATH_ROOT.'/'.dirname($file).'/'.$match);
            $content = str_replace($matches[0][$key], $inline_content, $content);
        }

        $content = $this->include_content($file, $content);
        return $content;
    }


    public function insertToc($input) {
        $toc = $this->createToc($input);
        $my_html = str_replace('%toc%', $toc, $input);
        return $my_html;

    }

    protected function createToc($input) {
        
        // <h1 id="foo">ffff</h1>
        preg_match_all('/<H([12]+) id="(.*)">(.*)<\/H[12]+>/i', $input, $headlines);
        //echo "<pre>";
        //print_r($headlines);
        //echo "</pre>";
        $output = '<div class="toc"><ul class="nav nav-list">'."\n";
        $lastLevel = 1;
        foreach($headlines[0] as $number=>$headline) {
            $currentLevel = $headlines[1][$number];

            if ($currentLevel>$lastLevel) {
                $output .= '<ul class="nav-list nav">';
            }

            $tempLastLevel = $currentLevel;
            while ($currentLevel<$lastLevel) {
                $output .= "</ul>\n";
                $currentLevel++;
            }


            $output .= '<li class="level'.$currentLevel.'"><a class="anchor-tag" href="#'.$headlines[2][$number].'">'.$headlines[3][$number].'</a></li>'."\n";

            $lastLevel = $tempLastLevel;
        }

        while ($lastLevel>0) {
            $output .= "</ul>\n";
            $lastLevel--;
        }

        $output .="</ul></div>\n";

        return $output;

    }

}