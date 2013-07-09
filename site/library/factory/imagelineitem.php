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

class EventgalleryLibraryFactoryImagelineitem extends EventgalleryLibraryFactoryFactory
{


    /**
     * @param $lineitemcontainerid
     * @param EventgalleryLibraryImagelineitem $lineitem
     *
     * @return EventgalleryLibraryImagelineitem
     */
    public function copyLineItem($lineitemcontainerid, $lineitem) {

        $data = get_object_vars($lineitem->_getInternalDataObject());
        unset($data['id']);
        $data['lineitemcontainerid'] = $lineitemcontainerid;
        $item = $this->store($data, 'Imagelineitem');

        return new EventgalleryLibraryImagelineitem($item);
    }

    /**
     * @param int $lineitemcontainerid
     * @param string $foldername
     * @param string $filename
     * @param int $imagetypeid
     * @param int $quantity
     *
     * @return EventgalleryLibraryImagelineitem
     */
    public function createLineitem($lineitemcontainerid, $foldername, $filename, $imagetypeid, $quantity) {

        $file = new EventgalleryLibraryFile($foldername, $filename);


        $imagetype = $file->getImageTypeSet()->getImageType($imagetypeid);
        if ($imagetype==null) {
            $imagetype = $file->getImageTypeSet()->getDefaultImageType();
        }

        $item = array(
            'lineitemcontainerid' => $lineitemcontainerid,
            'folder' => $file->getFolderName(),
            'file' => $file->getFileName(),
            'quantity' => $quantity,
            'singleprice' => $imagetype->getPrice(),
            'price' => $quantity * $imagetype->getPrice(),
            'taxrate' => $imagetype->getTaxrate(),
            'currency' => $imagetype->getCurrency(),
            'imagetypeid' => $imagetype->getId()
        );


        $result = $this->store($item, 'Imagelineitem');

        return new EventgalleryLibraryImagelineitem($result);
    }






}
