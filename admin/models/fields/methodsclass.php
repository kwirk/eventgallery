<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


// The class name must always be the same as the filename (in camel case)
/**
 * provides a form field where you can select which class should be used. This works only of the ID is not set.
 *
 * Class JFormFieldmethodsclass
 */
class JFormFieldmethodsclass extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'methodsclass';


    public function getInput()
    {
        $required = $this->required ? ' required="required" aria-required="true"' : '';
        $cssclass = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $superclass = (string)$this->element['superclass'];

        $this->getClasses(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR.'plugins');
        if ($this->form->getField('id')->value!=0 && $this->value!="") {
            $class= $this->value;
            return  '<input '.$cssclass.' value="'.$class::getClassName().'" disabled="disabled"">';
        }

        $return  = '<select '.$required.' '.$cssclass.' name='.$this->name.' id='.$this->id.'>';
        foreach(get_declared_classes() as $class) {

            if (strpos($class, 'EventgalleryPlugins')!==false) {

                if (is_subclass_of($class, $superclass)) {
                    $return .= '<option value="'.$class.'">'.$class::getClassName().'</option>';
                }
            }
        }
        $return .= "</select>";

        return $return;

    }

    private function getClasses($path) {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach($regex as $object) {
            require_once($object[0]);
        }
    }


}