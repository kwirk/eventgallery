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
jimport('joomla.mail.mail');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'buzzwords.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'captcha.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'imagehelpers.php');


class EventgalleryController extends JControllerLegacy
{
	
	
	
	public function display($cachable = false, $urlparams = false)
	{

		$viewname = JRequest::getString('view','events');
		JRequest::setVar('view', $viewname);
		
		if ($viewname == 'events')
		{
			
			$app	 = JFactory::getApplication();
			$params	 = $app->getParams();
			$viewLayout = JRequest::getString('layout', 'default');
			$view = $this->getView($viewname, 'html', '', array('base_path' => $this->basePath, 'layout' => $viewLayout));
			$eventsModel = $this->getModel('events');
			$eventModel = $this->getModel('event');
			$view->setModel( $eventsModel,true);
			$view->setModel( $eventModel, false);	
		}

		if ($viewname == 'event') {
			$session = JFactory::getSession();
			$password = JRequest::getString('password','');
			$folder = JRequest::getString('folder','');
			$folder = $this->getModel('event')->getFolder($folder);

			// we need to do this only if someone entered a password.
			// the views will protect themselfs
			$accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($folder, $password);
			if (strlen($password)>0 && !$accessAllowed) {
		    		$msg = JText::_('COM_EVENTGALLERY_PASSWORD_FORM_ERROR');
		    		$this->setRedirect(JRoute::_("index.php?view=password&folder=".$folder->folder, false), $msg);
		    		$this->redirect();
	    	}
			
		}

		// this fixes a strange behavior where the viewType is set to php if url rewriting is enabled.
		if ($viewname == 'resizeimage') {
			$document = JFactory::getDocument();
			$document->setType('html');						
		}

		parent::display($cachable, $urlparams);

		
	}

	function save_comment($cachable = false, $urlparams = false)
	{
		$app = JFactory::getApplication();

		$view = $this->getView('singleimage','html');
		$model = $this->getModel('singleimage');
		$view->setModel($model);
		$post = JRequest::get('post');
		$store= true;
		$buzzwords = $model->getBuzzwords();
		$buzzwordsClean=BuzzwordsHelper::validateBuzzwords($buzzwords,JRequest::getVar('text'));
		$captchaHelper = new CaptchaHelper();


        if ($captchaHelper->validateCaptcha(JRequest::getVar('password'))== false)
        {
            $store = false;
            $view->setError(JText::_('COM_EVENTGALLERY_COMMENT_ADD_CAPTCHA_ERROR'));
        }    
        
		if (strlen(JRequest::getVar('name'))==0)
		{
			$store=false;
			$view->setError(JText::_('COM_EVENTGALLERY_COMMENT_ADD_NAME_MISSING'));
		}

		if (strlen(JRequest::getVar('text'))==0)
		{
			$store=false;
			$view->setError(JText::_('COM_EVENTGALLERY_COMMENT_ADD_TEXT_MISSING'));
		}

		if ($store)
		{
			$row = $model->store_comment($post,$buzzwordsClean?1:0);
			if ($row && $buzzwordsClean)			{
				
				$msg = JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_SAVE_SUCCESS');
				$this->setRedirect(JRoute::_("index.php?view=singleimage&success=true&folder=".JRequest::getVar('folder')."&file=".JRequest::getVar('file'),false),$msg,'success');
			}
			else {
				$msg = JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_SAVE_FAILED');
				$this->setRedirect(JRoute::_("index.php?view=singleimage&success=false&folder=".JRequest::getVar('folder')."&file=".JRequest::getVar('file'),false),$msg, 'error');
			}



			$mailer = new JMail();
			$params = JComponentHelper::getParams('com_eventgallery');
			
		
			$mailadresses = explode(',',$params->get('adminmail'));			
			if (count($mailadresses)==0) return;
			
			$mailadresses = JMailHelper::cleanAddress($mailadresses);
			$mailer->addRecipient($mailadresses);

			JRequest::setVar('newCommentId',$row->id);


			$mailview = $this->getView('commentmail','html');
			$commentModel = $this->getModel('comment');
			$mailview->setModel($commentModel,true);

			

	

			$bodytext = $mailview->loadTemplate();
			#$mailer->LE = "\r\n";
			$mailer->LE = "\n";
			$bodytext = JMailHelper::cleanBody($bodytext);


			$mailer->setSubject( JMailHelper::cleanSubject($row->folder."/".$row->file.JText::_('COM_EVENTGALLERY_COMMENT_ADD_MAIL_SUBJECT').$app->getCfg('sitename')));
			$mailer->SetBody($bodytext);

			
			$image_thumb_file=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR.$row->folder.DIRECTORY_SEPARATOR.'thumbs'.DIRECTORY_SEPARATOR.'-1'.$row->file;

			$mailer->AddEmbeddedImage($image_thumb_file,'image');

			$mailer->IsHTML(true);
			$mailer->Send();

			#echo "$bodytext";

		}
		else
		{
			parent::display($cachable, $urlparams);

		}
	}

	function displayCaptcha() 
	{
	      $app = JFactory::getApplication();
		  $captchaHelper = new CaptchaHelper();
	      $captchaHelper->displayCaptcha();
	      $app->close();
	}


}
?>
