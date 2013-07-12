<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

// The class name must always be the same as the filename (in camel case)
class JFormFieldShippingstatustypes extends JFormFieldOrderstatustypes
{

    //The field class must know its own type through the variable $type.
    protected $type = 'shippingstatustypes';
    protected $currentOrderstatusId = EventgalleryLibraryOrderstatus::TYPE_SHIPPING;

}