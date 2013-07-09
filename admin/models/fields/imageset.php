<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

// The class name must always be the same as the filename (in camel case)
class JFormFieldimageset extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'html5text';


    public function getInput()
    {
        {


            $return  = '<select name='.$this->name.' id='.$this->id.'>';
            $return .= "<option></option>";
            $return .= "</select>";
            return $return;
        }
    }
}