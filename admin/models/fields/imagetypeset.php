<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

// The class name must always be the same as the filename (in camel case)
class JFormFieldImagetypeset extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'imagetypeset';


    public function getInput()
    {
        /**
         * @var EventgalleryLibraryManagerImagetypeset $imagetypesetMgr
         */
        $imagetypesetMgr = EventgalleryLibraryManagerImagetypeset::getInstance();

        $imagetypesets = $imagetypesetMgr->getImageTypeSets(true);

        if ($this->value == null) {
            $this->value = $imagetypesetMgr->getDefaultImageTypeSet()->getId();
        }

        $return  = '<select name='.$this->name.' id='.$this->id.'>';
        foreach($imagetypesets as $imagetypeset) {
            /**
             * @var EventgalleryLibraryImagetypeset $imagetypeset
             */

            $this->value==$imagetypeset->getId()?$selected='selected="selected"':$selected ='';

            $return .= '<option '.$selected.' value="'.$imagetypeset->getId().'">'.$imagetypeset->getName().'</option>';
        }
        $return .= "</select>";
        return $return;

    }
}