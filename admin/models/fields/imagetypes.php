<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

// The class name must always be the same as the filename (in camel case)
class JFormFieldImagetypes extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'imagetypes';


    public function getInput()
    {
        /**
         * @var EventgalleryLibraryManagerImagetype $imagetypeMgr
         */
        $imagetypeMgr = EventgalleryLibraryManagerImagetype::getInstance();

        $imagetypes = $imagetypeMgr->getImageTypes(false);

        $id = $this->form->getField('id')->value;

        $imagetypeset = null;
        if ($id!=0) {
            $imagetypeset = new EventgalleryLibraryImagetypeset($id);
        }

        $return  = '<select multiple name='.$this->name.' id='.$this->id.'>';
        foreach($imagetypes as $imagetype) {
            /**
             * @var EventgalleryLibraryImagetype $imagetype
             */
            $selected = "";
            if ($imagetypeset != null) {
                $imagetypeset->getImageType($imagetype->getId())!=null?$selected='selected="selected"':$selected ='';
            }
            $return .= '<option '.$selected.' value="'.$imagetype->getId().'">'.$imagetype->getName().'</option>';
        }
        $return .= "</select>";
        return $return;

    }
}