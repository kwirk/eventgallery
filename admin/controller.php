<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryController extends JControllerLegacy
{
	
	protected $default_view = 'eventgallery';


    /**
     * constructor (registers additional tasks to methods)
     * @return \EventgalleryController
     */
	function __construct()
	{
		parent::__construct();	
	}
	/*
	 * Standard display method
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  A JControllerLegacy object to support chaining.

	 */
	public function display($cachable = false, $urlparams = false)
	{
		parent::display($cachable, $urlparams);
	}


	/**
	 * function so remove every cache-entry
	 */
	function clearCache()
	{
		
		$path=JPATH_BASE.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'com_eventgallery_images';
		rrmDir($path);
		$path=JPATH_BASE.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'com_eventgallery_picasa';
	    rrmDir($path);
		$path=JPATH_BASE.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'com_eventgallery';
		rrmDir($path);
		$msg = JText::_( 'COM_EVENTGALLERY_CLEAR_CACHE_DONE' );
		$this->setRedirect( 'index.php?option=com_eventgallery', $msg );
	}
	
	/**
     * upaded meta information
     */
    public static function updateMetadata($path, $foldername, $filename) {

        $libPath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'pel'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
        require_once($libPath.'PelJpeg.php');
        require_once($libPath.'PelTiff.php');

        @list($width, $height, $type, $attr) = getimagesize($path);
        $exif = array();

        try {
        $input_jpeg = new PelJpeg($path);

        $app1 = $input_jpeg->getExif();

        if ($app1) {
            $tiff = $app1->getTiff();
            $ifd0 = $tiff->getIfd();
            $exifData = $ifd0->getSubIfd(PelIfd::EXIF);


        
            if ($exifData) {
                if ($data = $exifData->getEntry(PelTag::APERTURE_VALUE)) {
                    $value = $data->getValue();
                    $exif['fstop'] = sprintf('%.01f',pow(2, $value[0]/$value[1]/2));
                }
                if ($data = $exifData->getEntry(PelTag::FOCAL_LENGTH)) {
                    $value = $data->getValue();
                    $exif['focallength'] = sprintf('%.0f',$value[0]/$value[1]);
                }
                if ($data = $ifd0->getEntry(PelTag::MODEL)) {
                    $exif['model'] = $data->getText();
                }
                if ($data = $exifData->getEntry(PelTag::ISO_SPEED_RATINGS)) {
                    $exif['iso'] = $data->getText();
                }
            }


        }
        } catch (Exception $e) {
            
        }

        $exifJson = json_encode($exif);

        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->update("#__eventgallery_file");
        $query->set("width=".$db->quote($width));
        $query->set("height=".$db->quote($height));
        $query->set("exif=".$db->quote($exifJson));
        $query->where('folder='.$db->quote($foldername));
        $query->where('file='.$db->quote($filename));
        $db->setQuery($query);
        $db->execute();

        Pel::clearExceptions();
        unset($input_jpeg);



    }

	
}

function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file);
    }
    rmdir($dir);
}
