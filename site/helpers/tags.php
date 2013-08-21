<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

class EventgalleryHelpersTags
{

    /**
     * Checks if at least one needle tag is part of the tags string.
     * Returns false if the needle is empty
     *
     *
     * @param String $tags a comma or space separated string (foo, bar, foo2)
     * @param String $needleTags a comma or space separated string (foo, bar, foo2)
     * @return bool returns true if one element in the needeTags is contained in the tags
     */
    public static function checkTags($tags, $needleTags)
    {
        if (strlen($needleTags) == 0) {
            return false;
        }

        // handle space and comma separated lists like "foo bar" or "foo, bar"

        $tempTags = explode(',', str_replace(" ", ",", $needleTags));
        array_walk($tempTags, 'trim');

        $needleTags = Array();

        foreach ($tempTags as $tag) {
            if (strlen($tag) > 0) {
                $needleTags[] = $tag;
            }
        }

        $regex = "/(" . implode($needleTags, '|') . ")/i";

        if (preg_match($regex, $tags)) {
            return true;
        }


        // no match
        return false;

    }

}