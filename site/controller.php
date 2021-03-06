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

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'buzzwords.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'captcha.php');


/** @noinspection PhpUndefinedClassInspection */
class EventgalleryController extends JControllerLegacy
{


    public function display($cachable = false, $urlparams = array())
    {
        parent::display($cachable, $urlparams);
    }

    function save_comment($cachable = false, $urlparams = array())
    {
        $app = JFactory::getApplication();

        $view = $this->getView('singleimage', 'html');
        /**
         * @var EventgalleryModelSingleimage $model
         */
        $model = $this->getModel('singleimage');
        $view->setModel($model);
        $post = JRequest::get('post');
        $store = true;
        $buzzwords = $model->getBuzzwords();
        $buzzwordsClean = BuzzwordsHelper::validateBuzzwords($buzzwords, JRequest::getVar('text'));
        $captchaHelper = new EventgalleryHelpersCaptcha();


        if ($captchaHelper->validateCaptcha(JRequest::getVar('password')) == false) {
            $store = false;
            $view->setError(JText::_('COM_EVENTGALLERY_COMMENT_ADD_CAPTCHA_ERROR'));
        }

        if (strlen(JRequest::getVar('name')) == 0) {
            $store = false;
            $view->setError(JText::_('COM_EVENTGALLERY_COMMENT_ADD_NAME_MISSING'));
        }

        if (strlen(JRequest::getVar('text')) == 0) {
            $store = false;
            $view->setError(JText::_('COM_EVENTGALLERY_COMMENT_ADD_TEXT_MISSING'));
        }

        if ($store) {


            $row = $model->store_comment($post, $buzzwordsClean ? 1 : 0);
            if ($row && $buzzwordsClean) {

                $msg = JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_SAVE_SUCCESS');
                $this->setRedirect(
                    JRoute::_(
                        "index.php?view=singleimage&success=true&folder=" . JRequest::getVar('folder') . "&file="
                        . JRequest::getVar('file'), false
                    ), $msg, 'success'
                );
            } else {
                $msg = JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_SAVE_FAILED');
                $this->setRedirect(
                    JRoute::_(
                        "index.php?view=singleimage&success=false&folder=" . JRequest::getVar('folder') . "&file="
                        . JRequest::getVar('file'), false
                    ), $msg, 'error'
                );
            }


            $mailer = JFactory::getMailer();
            $params = JComponentHelper::getParams('com_eventgallery');

            $userids = JAccess::getUsersByGroup($params->get('admin_usergroup'));

            if (count($userids) == 0) {
                return;
            }

            foreach ($userids as $userid) {
                $user = JUser::getInstance($userid);
                if ($user->sendEmail==1) {

                    $mailadress = JMailHelper::cleanAddress($user->email);
                    $mailer->addRecipient($mailadress);
                }
            }

            $config = JFactory::getConfig();
            $sender = array(
                $config->get( 'mailfrom' ),
                $config->get( 'fromname' ) );

            $mailer->setSender($sender);

            JRequest::setVar('newCommentId', $row->id);


            $mailview = $this->getView('commentmail', 'html');
            /**
             *
             * @var EventgalleryModelComment $commentModel
             */
            $commentModel = $this->getModel('comment');
            $mailview->setModel($commentModel, true);


            $bodytext = $mailview->loadTemplate();
            #$mailer->LE = "\r\n";
            $mailer->LE = "\n";
            $bodytext = JMailHelper::cleanBody($bodytext);


            $mailer->setSubject(
                JMailHelper::cleanSubject(
                    $row->folder . "|" . $row->file . ' - ' .JText::_('COM_EVENTGALLERY_COMMENT_ADD_MAIL_SUBJECT')
                    . ' - ' .$app->getCfg('sitename')
                )
            );
            $mailer->SetBody($bodytext);

            $mailer->IsHTML(true);
            $mailer->Send();

            #echo "$bodytext";

        } else {
            parent::display($cachable, $urlparams);

        }
    }

    function displayCaptcha()
    {
        $app = JFactory::getApplication();
        $captchaHelper = new EventgalleryHelpersCaptcha();
        $captchaHelper->displayCaptcha();
        $app->close();
    }


}

