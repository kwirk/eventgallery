<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );


//jimport( 'joomla.application.component.helper' );

class EventgalleryModelComment extends JModelLegacy
{
    
	
	function getData($commentId)
	{
	 	$query = ' SELECT * FROM #__eventgallery_comment '.
				 ' WHERE id='.$this->_db->Quote($commentId);
		$this->_db->setQuery( $query );
		return $this->_db->loadObject();
	}
	
	function getFile($commentId)
	{
		$comment = $this->getData($commentId);
		$query = ' SELECT * FROM #__eventgallery_file '.
			     ' WHERE file='.$this->_db->Quote($comment->file).' and folder='.$this->_db->Quote($comment->folder);
		$this->_db->setQuery( $query );
		return new EventGalleryImage($this->_db->loadObject());
	}
}