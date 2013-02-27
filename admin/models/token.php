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

jimport('joomla.application.component.model');


class EventgalleryModelToken extends JModelLegacy
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
	}

	function generateToken($folder)
	{
		$session = JFactory::getSession();
		$session->getId();
		$user = JFactory::getUser();		
		$token = md5($folder.$session->getId());
		$db = JFactory::getDBO();
		$query ="delete from #__eventgallery_token where now()-date>6000";
		$db->setQuery($query);
		$db->query();
		
		$query ="delete from #__eventgallery_token where token=".$db->Quote($token);
		$db->setQuery($query);
		$db->query();
		
    	#$query = "insert into #__eventgallery_token set 
    #					token=".$db->Quote($token).",
   # 					folder=".$db->Quote($folder).",
   # 					userid=".$db->Quote($user->id)."
   # 				on duplicate key update date=now();";		    	
    	
    	$query = "insert into #__eventgallery_token set 
				token=".$db->Quote($token).",
				folder=".$db->Quote($folder).",
				userid=".$db->Quote($user->id);		    	
		
		$db->setQuery($query);
		$db->query();
		echo $db->getErrorMsg();
		return $token;
	}
	
}
?>
