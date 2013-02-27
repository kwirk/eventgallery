<?php 

defined('_JEXEC') or die;

class BuzzwordsHelper
{
	function validateBuzzwords($buzzwords,$text)
    {
        foreach($buzzwords as $buzzword) 
        {
            if (strlen($buzzword)>0)
            {
                $buzzword=strtoupper(trim($buzzword));
                if (strpos(strtoupper("  ".$text),strtoupper($buzzword)) > 0 ) {                    
                    return false;
                }
            }
        }
        return true;
    }	
}
?>