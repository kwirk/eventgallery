<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
define('_JEXEC', 1);

// useless, just to satisfy the jedChecker
defined('_JEXEC') or die;

if (file_exists(dirname(__FILE__) . '/defines.php')) {
	include_once dirname(__FILE__) . '/defines.php';
}


if (!defined('_JDEFINES')) {
	// remove the first 3 folders because
	// we're in a subfolder and have not 
	// native Joomla help. Doing this will
	// enable this comonent to run in a subdirectory
	// like http://foo.bar/foobar
	$basefolders = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));			
	$basefolders = array_splice($basefolders, 0, count($basefolders)-3);
	define('JPATH_BASE', implode(DIRECTORY_SEPARATOR, $basefolders));
	require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';

$width=JRequest::getInt('width',-1);
$height=JRequest::getInt('height',-1);
			
$cachebasedir=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR ;
$cachedir=$cachebasedir.DIRECTORY_SEPARATOR.'blanks';
	

if (!is_dir($cachebasedir))
{
	//mkdir($cachebasedir, 0777);
	mkdir($cachebasedir);
	
}

if (!is_dir($cachedir))
{
	//mkdir($cachedir, 0777);
	mkdir($cachedir);
}


if ($width==-1) {
	$width==1;
}

if ($height==-1) {
	$height = $width;
}

$image_thumb_file = $cachedir.DIRECTORY_SEPARATOR.'blank_'.$width.'_'.$height.'.gif';		

$debug = false;
if ($debug || !file_exists($image_thumb_file))
{
    
    $im_output= imagecreate($width,$height);
    $colourBlack = imagecolorallocate($im_output, 0, 0, 0);
    imagefilledrectangle($im_output, 0, 0, $width, $height, $colourBlack);
    imagecolortransparent($im_output, $colourBlack);    
    imagegif($im_output,$image_thumb_file);     
    
}

if (!$debug)
{
	header("Last-Modified: Sun, 14 Apr 2013 11:21:39 GMT");
	header("Content-Type: image/gif");
	
}

echo readfile($image_thumb_file);