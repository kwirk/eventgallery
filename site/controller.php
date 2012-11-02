<?php


jimport('joomla.application.component.controller');
jimport('joomla.mail.mail');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'buzzwords.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'captcha.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'imagehelpers.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'picasa.php');


class EventgalleryController extends JControllerLegacy
{
	
	
	
	public function display($cachable = false, $urlparams = false)
	{

		$viewname = JRequest::getString('view','events');
		JRequest::setVar('view', $viewname);
		
		if ($viewname == 'events')
		{
			
			$app	 = &JFactory::getApplication();
			$params	 = &$app->getParams();
			
			
			$view =& $this->getView($viewname, 'html');
			$view->setModel( $this->getModel('events'),true);
			$view->setModel( $this->getModel('event'),false);	
		}
		
		
		parent::display($cachable, $urlparams);

		
	}

	function save_comment()
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
			if ($row && $buzzwordsClean)
			$this->setRedirect(JRoute::_("index.php?view=singleimage&success=true&folder=".JRequest::getVar('folder')."&file=".JRequest::getVar('file'),false));
			else
			$this->setRedirect(JRoute::_("index.php?view=singleimage&success=false&folder=".JRequest::getVar('folder')."&file=".JRequest::getVar('file'),false));



			$mailer = new JMail();
			$params = &JComponentHelper::getParams('com_eventgallery');
			
		
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
			$view->display();
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
