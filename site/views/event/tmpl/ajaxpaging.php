<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');


echo  $this->loadSnippet('cart');

echo  $this->loadSnippet('social');


if ($this->folder->isCartable() && $this->params->get('use_cart', '1')==1) {
    echo $this->loadSnippet('imageset/imagesetselectionajax');
}

echo $this->loadSnippet('event/ajaxpaging');

echo $this->loadSnippet('footer_disclaimer');
